<?php

namespace App\Services;

use App\Models\Family;
use App\Models\FamilyMember;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class FamilyTreeService
{
    /**
     * Build a canonical merged tree for the given family, merging members by NIK across connected families.
     * - Canonicalize person by NIK (one node per NIK). If no NIK, fallback to id-based unique.
     * - Attributes (name, photo, etc.) prefer the active family's record; otherwise keep another.
     * - Structure (parent/child) is taken from the most complete lineage (prefer the family where member is a child).
     * - Mark nodes as editable if they belong to the active family; others readonly.
     * - Output JSON-like hierarchy suitable for d3.tree.
     */
    public function buildTree(Family $activeFamily): array
    {
        // 1) Gather connected families transitively by shared NIKs
        $includedFamilyIds = $this->collectConnectedFamilyIds($activeFamily->id);
        Log::info('FamilyTree connected families', [
            'active_family_id' => $activeFamily->id,
            'included_family_ids' => $includedFamilyIds->all(),
        ]);

        // 2) Load all members from connected families
        $members = FamilyMember::whereIn('family_id', $includedFamilyIds)
            ->get(['id','family_id','name','nik','gender','birth_date','death_date','relation','parent_id','photo','description']);

        if ($members->isEmpty()) {
            return [ 'name' => $activeFamily->family_name, 'children' => [] ];
        }

        // 3) Canonicalize by key (nik or fallback id)
        [$canonicalByKey, $keyById] = $this->canonicalizeMembers($members, $activeFamily->id);

        // 4) Build edges parent->child using canonical ids, prefer lineage where a person is a child
        $edges = $this->buildCanonicalEdges($members, $keyById);

        // 5) Build parent pairing hints (father+mother within same family when available)
        $parentPairs = $this->buildParentPairs($members, $keyById);

        // 6) Choose primary root(s) and order with active family roots first
        $rootKeys = $this->selectRoots($canonicalByKey, $edges, $activeFamily->id);

        // 7) Construct hierarchy
        $tree = $this->buildHierarchy($canonicalByKey, $edges, $parentPairs, $rootKeys, $activeFamily->id);

        return $tree;
    }

    /**
     * Build a recursive family tree starting from a family_id.
     * Returns nested array matching the requested shape: name, spouse, children (array)
     * This method will traverse connected families when a child becomes a parent in another family (by NIK).
     * Uses $visitedFamilies to avoid infinite recursion.
     */
    public function buildFamilyTree(int $familyId): array
    {
        $visited = [];
        $node = $this->buildFamilyNodeFromFamily($familyId, $visited);
        return $node ?: ['name' => "Keluarga $familyId", 'spouse' => null, 'children' => []];
    }

    private function buildFamilyNodeFromFamily(int $familyId, array &$visited): ?array
    {
        if (in_array($familyId, $visited)) {
            // already processed this family in the current traversal => prevent cycles
            return null;
        }
        $visited[] = $familyId;

        $members = FamilyMember::where('family_id', $familyId)->get();
        if ($members->isEmpty()) {
            return null;
        }

        $father = $members->firstWhere('role', 'father');
        $mother = $members->firstWhere('role', 'mother');

        // choose primary (prefer father, else mother)
        $primary = $father ?? $mother;
        $primaryName = $primary->name ?? ("Keluarga $familyId");
        $spouseName = null;
        if ($primary) {
            if ($primary->relation === 'father') {
                $spouseName = $mother ? $mother->name : null;
            } else {
                $spouseName = $father ? $father->name : null;
            }
        }

        $childrenList = $members->where('role', 'child');
        $children = [];

        foreach ($childrenList as $child) {
            // find other families where this child is a parent (by NIK)
            $otherParentFamilies = FamilyMember::where('nik', $child->nik)
                ->where('family_id', '<>', $familyId)
                ->whereIn('role', ['father', 'mother'])
                ->pluck('family_id')
                ->unique();

            if ($otherParentFamilies->isNotEmpty()) {
                // attach subtree(s) for each other family
                // If multiple, merge them as separate spouse/children entries under this child
                foreach ($otherParentFamilies as $ofId) {
                    $subNode = $this->buildFamilyNodeFromFamily($ofId, $visited);
                    if ($subNode) {
                        $children[] = [
                            'name' => $child->name,
                            'spouse' => $subNode['spouse'] ?? null,
                            'children' => $subNode['children'] ?? [],
                        ];
                    } else {
                        // already visited or empty: attach as leaf
                        $children[] = [
                            'name' => $child->name,
                            'spouse' => null,
                            'children' => [],
                        ];
                    }
                }
            } else {
                // simple leaf child
                $children[] = [
                    'name' => $child->name,
                    'spouse' => null,
                    'children' => [],
                ];
            }
        }

        return [
            'name' => $primaryName,
            'spouse' => $spouseName,
            'children' => $children,
        ];
    }

    private function collectConnectedFamilyIds(int $startFamilyId): Collection
    {
        $included = collect([$startFamilyId]);
        $visitedNiks = collect();
        do {
            $changed = false;
            $niks = FamilyMember::whereIn('family_id', $included)->whereNotNull('nik')->pluck('nik')->unique();
            $newNiks = $niks->diff($visitedNiks);
            if ($newNiks->isNotEmpty()) {
                $visitedNiks = $visitedNiks->merge($newNiks)->unique();
                $extraFamilies = FamilyMember::whereIn('nik', $newNiks)->pluck('family_id')->unique();
                $before = $included->count();
                $included = $included->merge($extraFamilies)->unique();
                if ($included->count() > $before) { $changed = true; }
            }
        } while ($changed);
        return $included->values();
    }

    /** @return array [canonicalByKey, keyById] */
    private function canonicalizeMembers(Collection $members, int $activeFamilyId): array
    {
        $canonicalByKey = [];
        $keyById = [];
        foreach ($members as $m) {
            $key = $m->nik ? ('nik:'.$m->nik) : ('id:'.$m->id);
            $keyById[$m->id] = $key;
            if (!isset($canonicalByKey[$key])) {
                $canonicalByKey[$key] = [
                    'idKey' => $key,
                    'ids' => [$m->id],
                    'nik' => $m->nik,
                    'dataFromActive' => null,
                    'fallbackData' => $this->toNodeData($m, false),
                    'families' => [$m->family_id],
                    'relations' => [$m->relation],
                ];
            } else {
                $canonicalByKey[$key]['ids'][] = $m->id;
                $canonicalByKey[$key]['families'][] = $m->family_id;
                $canonicalByKey[$key]['relations'][] = $m->relation;
            }
            if ($m->family_id === $activeFamilyId) {
                $canonicalByKey[$key]['dataFromActive'] = $this->toNodeData($m, true);
            }
        }
        // finalize preferred data per key
        foreach ($canonicalByKey as &$node) {
            $node['families'] = array_values(array_unique($node['families']));
            $node['relations'] = array_values(array_unique($node['relations']));
            $data = $node['dataFromActive'] ?? $node['fallbackData'];
            $node['node'] = array_merge($data, [
                'editable' => $data['family_id'] === $activeFamilyId,
                'readonly' => $data['family_id'] !== $activeFamilyId,
            ]);
        }
        unset($node);
        return [$canonicalByKey, $keyById];
    }

    private function toNodeData(FamilyMember $m, bool $fromActive): array
    {
        return [
            'member_id' => $m->id,
            'family_id' => $m->family_id,
            'name' => $m->name,
            'nik' => $m->nik,
            'gender' => $m->gender,
            'birth_date' => optional($m->birth_date)->format('Y-m-d'),
            'death_date' => optional($m->death_date)->format('Y-m-d'),
            'relation' => $m->relation,
            'photo' => $m->photo,
            'description' => $m->description,
        ];
    }

    private function buildCanonicalEdges(Collection $members, array $keyById): array
    {
        $edges = [];
        foreach ($members as $m) {
            if ($m->parent_id) {
                $childKey = $keyById[$m->id] ?? null;
                $parentKey = $keyById[$m->parent_id] ?? null;
                if ($childKey && $parentKey && $childKey !== $parentKey) {
                    $edges[$parentKey][$childKey] = true; // set
                }
            }
        }

        // Heuristic: if a child has no parent_id, but the family has a single father (or mother),
        // attach the child under that parent to keep hierarchy usable across datasets without explicit links.
        $byFamily = $members->groupBy('family_id');
        foreach ($byFamily as $familyId => $list) {
            $fathers = $list->where('relation', 'father')->values();
            $mothers = $list->where('relation', 'mother')->values();
            $singleFather = $fathers->count() === 1 ? $fathers->first() : null;
            $singleMother = $mothers->count() === 1 ? $mothers->first() : null;

            foreach ($list->where('relation', 'child') as $child) {
                if (!$child->parent_id) {
                    $parent = $singleFather ?? $singleMother;
                    if ($parent) {
                        $childKey = $keyById[$child->id] ?? null;
                        $parentKey = $keyById[$parent->id] ?? null;
                        if ($childKey && $parentKey && $childKey !== $parentKey) {
                            $edges[$parentKey][$childKey] = true; // inferred link
                        }
                    }
                }
            }
        }
        // convert sets to lists
        foreach ($edges as $pk => $childrenSet) {
            $edges[$pk] = array_keys($childrenSet);
        }
        return $edges;
    }

    private function buildParentPairs(Collection $members, array $keyById): array
    {
        // For each child, record father & mother keys if present under the same family
        $pairs = [];
        // group by family
        $byFamily = $members->groupBy('family_id');
        foreach ($byFamily as $familyId => $list) {
            $fathers = $list->where('relation', 'father');
            $mothers = $list->where('relation', 'mother');
            foreach ($list as $child) {
                if ($child->relation !== 'child' || !$child->parent_id) continue;
                // We cannot reliably infer couple by child->parent_id alone here without spouse links; keep simple
                // This function can be extended later to create a virtual pair node if needed.
            }
        }
        return $pairs;
    }

    private function selectRoots(array $canonicalByKey, array $edges, int $activeFamilyId): array
    {
        // root candidates: nodes with no incoming edge
        $hasIncoming = [];
        foreach ($edges as $p => $children) {
            foreach ($children as $c) { $hasIncoming[$c] = true; }
        }
        $roots = [];
        foreach ($canonicalByKey as $key => $data) {
            if (!isset($hasIncoming[$key])) { $roots[] = $key; }
        }
        $roots = $roots ?: array_keys($canonicalByKey);

        // Order roots: those whose preferred node comes from active family first
        usort($roots, function ($a, $b) use ($canonicalByKey, $activeFamilyId) {
            $aActive = ($canonicalByKey[$a]['node']['family_id'] ?? null) === $activeFamilyId ? 1 : 0;
            $bActive = ($canonicalByKey[$b]['node']['family_id'] ?? null) === $activeFamilyId ? 1 : 0;
            if ($aActive !== $bActive) { return $bActive <=> $aActive; }
            // Secondary: older birth_date first if available
            $aDate = $canonicalByKey[$a]['node']['birth_date'] ?? null;
            $bDate = $canonicalByKey[$b]['node']['birth_date'] ?? null;
            if ($aDate === $bDate) { return 0; }
            if ($aDate === null) { return 1; }
            if ($bDate === null) { return -1; }
            return strcmp($aDate, $bDate);
        });

        return $roots;
    }

    private function buildHierarchy(array $canonicalByKey, array $edges, array $parentPairs, array $rootKeys, int $activeFamilyId): array
    {
        // Build a forest; if multiple roots, wrap under a synthetic root
        $buildNode = function($key) use (&$buildNode, $canonicalByKey, $edges) {
            $data = $canonicalByKey[$key]['node'];
            $children = [];

            // Collect child keys and sort by birth_date (oldest-first). Nulls last.
            $childKeys = $edges[$key] ?? [];
            usort($childKeys, function ($a, $b) use ($canonicalByKey) {
                $aDate = $canonicalByKey[$a]['node']['birth_date'] ?? null;
                $bDate = $canonicalByKey[$b]['node']['birth_date'] ?? null;

                if ($aDate === $bDate) { return 0; }
                if ($aDate === null) { return 1; } // nulls last
                if ($bDate === null) { return -1; }

                // Earlier date means older person should come first
                return strcmp($aDate, $bDate);
            });

            foreach ($childKeys as $ck) {
                $children[] = $buildNode($ck);
            }
            return [
                'id' => $key,
                'name' => $data['name'],
                'nik' => $data['nik'],
                'gender' => $data['gender'],
                'relation' => $data['relation'],
                'birth_date' => $data['birth_date'] ?? null,
                'photo' => $data['photo'],
                'editable' => $data['editable'],
                'readonly' => $data['readonly'],
                'children' => $children,
            ];
        };

        $roots = array_map($buildNode, $rootKeys);
        if (count($roots) === 1) {
            return $roots[0];
        }
        return [ 'name' => 'Keluarga', 'children' => $roots ];
    }
}

