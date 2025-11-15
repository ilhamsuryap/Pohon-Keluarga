<?php

namespace App\Services;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Models\GroupMember;
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
     * Returns nested array with separate nodes for each family member including profile photos.
     * Couples are positioned as separate but horizontally aligned nodes.
     */
    public function buildFamilyTree(int $familyId): array
    {
        // 1) Kumpulkan semua keluarga yang terhubung transitif berdasarkan NIK
        $connectedFamilyIds = $this->collectConnectedFamilyIds($familyId);
        $members = FamilyMember::whereIn('family_id', $connectedFamilyIds)->get([
            'id','family_id','name','nik','gender','birth_date','death_date','relation','parent_id','child_order','photo','description'
        ]);

        if ($members->isEmpty()) {
            return ['name' => "Keluarga $familyId", 'type' => 'family', 'children' => []];
        }

        // 2) Group per keluarga dan petakan ayah/ibu/anak (gunakan field 'relation', bukan 'role')
        $byFamily = $members->groupBy('family_id');
        $familyInfo = [];
        foreach ($byFamily as $fid => $list) {
            $father = $list->firstWhere('relation', 'father');
            // allow multiple mothers per family; keep key name 'mother' but store collection
            $mothers = $list->where('relation', 'mother')->values();
            $children = $list->where('relation', 'child')->values();
            // store mothers collection under 'mother' key for backward compatibility in other code
            $familyInfo[$fid] = ['father' => $father, 'mother' => $mothers, 'children' => $children];
        }

        // 3) Index NIK -> anggota (untuk memetakan hubungan antar keluarga)
        $nikIndex = $members->filter(fn ($m) => !empty($m->nik))->groupBy('nik');

        // 4) Bangun graf antar-keluarga: parentFamily -> childFamily
        //    Jika seorang anak di family P punya NIK yang sama dengan ayah/ibu di family C,
        //    maka edge P -> C (P adalah leluhur dari C)
        $edges = [];
        $incoming = [];
        foreach ($connectedFamilyIds as $fid) { $incoming[$fid] = 0; }

        foreach ($familyInfo as $fid => $info) {
            foreach (['father', 'mother'] as $role) {
                $p = $info[$role] ?? null;
                // if $p is a collection (multiple mothers), iterate; otherwise treat as single member
                if (is_iterable($p)) {
                    foreach ($p as $pp) {
                        if ($pp && ($pp->nik ?? null)) {
                            $sameNikMembers = $nikIndex->get($pp->nik) ?? collect();
                            foreach ($sameNikMembers as $m) {
                                if ($m->family_id !== $fid && $m->relation === 'child') {
                                    $parentFamilyId = $m->family_id; // keluarga tempat orang ini menjadi anak
                                    $edges[$parentFamilyId][$fid] = true; // parent -> current
                                    $incoming[$fid] = ($incoming[$fid] ?? 0) + 1;
                                    $incoming[$parentFamilyId] = $incoming[$parentFamilyId] ?? 0;
                                }
                            }
                        }
                    }
                } else {
                    if ($p && ($p->nik ?? null)) {
                        $sameNikMembers = $nikIndex->get($p->nik) ?? collect();
                        foreach ($sameNikMembers as $m) {
                            if ($m->family_id !== $fid && $m->relation === 'child') {
                                $parentFamilyId = $m->family_id; // keluarga tempat orang ini menjadi anak
                                $edges[$parentFamilyId][$fid] = true; // parent -> current
                                $incoming[$fid] = ($incoming[$fid] ?? 0) + 1;
                                $incoming[$parentFamilyId] = $incoming[$parentFamilyId] ?? 0;
                            }
                        }
                    }
                }
            }
        }

        // 5) Temukan akar (keluarga tanpa incoming edge). Jika tidak ada, fallback ke familyId
        $roots = collect($connectedFamilyIds)
            ->filter(fn ($fid) => ($incoming[$fid] ?? 0) === 0)
            ->values()
            ->all();
        if (empty($roots)) { $roots = [$familyId]; }

        // 6) DFS membangun subtree dari setiap root, membuat node terpisah untuk setiap anggota
        $visitedFamilies = [];
        $buildSubtree = function (int $fid) use (&$buildSubtree, &$visitedFamilies, $familyInfo, $edges) {
            if (in_array($fid, $visitedFamilies, true)) { return null; }
            $visitedFamilies[] = $fid;

            $info = $familyInfo[$fid] ?? ['father' => null, 'mother' => null, 'children' => collect()];
            $father = $info['father'];
            // 'mother' is a collection (may be empty)
            $mother = $info['mother'] ?? collect();

            // Petakan anak yang "naik kelas" menjadi orang tua di keluarga lain
            $childFamilies = array_keys($edges[$fid] ?? []);
            $childNikToFamily = [];
            foreach ($childFamilies as $cfid) {
                $childInfo = $familyInfo[$cfid] ?? null;
                if (!$childInfo) { continue; }
                foreach (['father', 'mother'] as $role) {
                    $p = $childInfo[$role] ?? null;
                    if (is_iterable($p)) {
                        foreach ($p as $pp) {
                            if ($pp && ($pp->nik ?? null)) {
                                $childNikToFamily[$pp->nik] = $cfid;
                            }
                        }
                    } else {
                        if ($p && ($p->nik ?? null)) {
                            $childNikToFamily[$p->nik] = $cfid;
                        }
                    }
                }
            }

            // Bangun anak: kelompokkan berdasarkan parent_id
            // Children dengan parent_id akan ditambahkan ke mother yang sesuai
            // Children tanpa parent_id akan ditambahkan ke couple node (default)
            $childrenByParentId = [];
            $childrenWithoutParent = [];
            
            foreach (($info['children'] ?? collect()) as $child) {
                if ($child->parent_id) {
                    if (!isset($childrenByParentId[$child->parent_id])) {
                        $childrenByParentId[$child->parent_id] = [];
                    }
                    $childrenByParentId[$child->parent_id][] = $child;
                } else {
                    $childrenWithoutParent[] = $child;
                }
            }

            // Helper function untuk membangun child node
            $buildChildNode = function($child) use ($buildSubtree, $childNikToFamily) {
                $nextFamilyId = ($child->nik && isset($childNikToFamily[$child->nik])) ? $childNikToFamily[$child->nik] : null;
                if ($nextFamilyId) {
                    $sub = $buildSubtree($nextFamilyId);
                    if ($sub) {
                        // If the child becomes a parent in another family, use that family structure
                        $childNode = $sub;
                        $childNode['birth_date'] = optional($child->birth_date)->format('Y-m-d');
                        return $childNode;
                    }
                }
                // leaf child
                $childNode = $this->createPersonNode($child);
                $childNode['birth_date'] = optional($child->birth_date)->format('Y-m-d');
                $childNode['child_order'] = $child->child_order;
                return $childNode;
            };

            // Build children nodes for each mother (based on parent_id)
            $motherChildrenMap = [];
            foreach ($childrenByParentId as $parentId => $children) {
                $childrenNodes = [];
                foreach ($children as $child) {
                    $childrenNodes[] = $buildChildNode($child);
                }
                // Sort by child_order first, then by birth_date
                usort($childrenNodes, function ($a, $b) {
                    $aOrder = $a['child_order'] ?? null;
                    $bOrder = $b['child_order'] ?? null;
                    // If both have child_order, sort by it
                    if ($aOrder !== null && $bOrder !== null) {
                        return $aOrder <=> $bOrder;
                    }
                    // If only one has child_order, prioritize it
                    if ($aOrder !== null) return -1;
                    if ($bOrder !== null) return 1;
                    // If neither has child_order, sort by birth_date
                    $ad = $a['birth_date'] ?? null; $bd = $b['birth_date'] ?? null;
                    if ($ad === $bd) return 0;
                    if ($ad === null) return 1;
                    if ($bd === null) return -1;
                    return strcmp($ad, $bd);
                });
                // Remove birth_date from output
                foreach ($childrenNodes as &$cn) { unset($cn['birth_date']); }
                unset($cn);
                $motherChildrenMap[$parentId] = $childrenNodes;
            }

            // Build children nodes without parent_id (default children for couple)
            $childrenNodes = [];
            foreach ($childrenWithoutParent as $child) {
                $childrenNodes[] = $buildChildNode($child);
            }
            // Sort by birth_date
            usort($childrenNodes, function ($a, $b) {
                $ad = $a['birth_date'] ?? null; $bd = $b['birth_date'] ?? null;
                if ($ad === $bd) return 0;
                if ($ad === null) return 1;
                if ($bd === null) return -1;
                return strcmp($ad, $bd);
            });
            // Remove birth_date from output
            foreach ($childrenNodes as &$cn) { unset($cn['birth_date']); }
            unset($cn);

            // Create couple node or single parent node. Support multiple mothers.
            // Attach children to specific mothers based on parent_id
            $motherCount = is_countable($mother) ? count($mother) : ($mother ? 1 : 0);
            if ($father && $motherCount > 0) {
                // build mother_data as single object if one, or array of objects if more
                if ($motherCount === 1) {
                    $mObj = $mother->first();
                    $motherData = $this->createPersonNode($mObj);
                    // Attach children to this mother if they have parent_id pointing to her
                    if (isset($motherChildrenMap[$mObj->id])) {
                        $motherData['children'] = $motherChildrenMap[$mObj->id];
                    }
                    $id = 'couple_' . $father->id . '_' . $mObj->id;
                } else {
                    $motherData = [];
                    $ids = [];
                    foreach ($mother as $mObj) {
                        $mNode = $this->createPersonNode($mObj);
                        // Attach children to this mother if they have parent_id pointing to her
                        if (isset($motherChildrenMap[$mObj->id])) {
                            $mNode['children'] = $motherChildrenMap[$mObj->id];
                        }
                        $motherData[] = $mNode;
                        $ids[] = $mObj->id;
                    }
                    $id = 'couple_' . $father->id . '_' . implode('_', $ids);
                }

                return [
                    'type' => 'couple',
                    'name' => $father->name . ' & ' . ($motherCount === 1 ? $motherData['name'] : ('Ibu x' . $motherCount)),
                    'father_data' => $this->createPersonNode($father),
                    'mother_data' => $motherData,
                    'id' => $id,
                    'children' => $childrenNodes, // Default children (without parent_id)
                ];
            } elseif ($father) {
                $node = $this->createPersonNode($father);
                $node['children'] = $childrenNodes;
                return $node;
            } elseif ($motherCount > 0) {
                // no father, but one or more mothers: expose mother_data (single or array) so the view can render them
                if ($motherCount === 1) {
                    $mObj = $mother->first();
                    $motherData = $this->createPersonNode($mObj);
                    // Attach children to this mother if they have parent_id pointing to her
                    if (isset($motherChildrenMap[$mObj->id])) {
                        $motherData['children'] = $motherChildrenMap[$mObj->id];
                    }
                    $id = 'mother_only_' . $mObj->id;
                } else {
                    $motherData = [];
                    $ids = [];
                    foreach ($mother as $mObj) {
                        $mNode = $this->createPersonNode($mObj);
                        // Attach children to this mother if they have parent_id pointing to her
                        if (isset($motherChildrenMap[$mObj->id])) {
                            $mNode['children'] = $motherChildrenMap[$mObj->id];
                        }
                        $motherData[] = $mNode;
                        $ids[] = $mObj->id;
                    }
                    $id = 'mother_only_' . implode('_', $ids);
                }
                return [
                    'type' => 'couple',
                    'name' => 'Ibu',
                    'father_data' => null,
                    'mother_data' => $motherData,
                    'id' => $id,
                    'children' => $childrenNodes, // Default children (without parent_id)
                ];
            } else {
                return [
                    'type' => 'family',
                    'name' => "Keluarga $fid",
                    'children' => $childrenNodes,
                ];
            }
        };

        $forest = [];
        foreach ($roots as $r) {
            $sub = $buildSubtree($r);
            if ($sub) { $forest[] = $sub; }
        }

        // Jika hanya satu root, kembalikan langsung; jika banyak, bungkus agar D3 tetap bisa render
        if (count($forest) === 1) { return $forest[0]; }
        return [ 'name' => 'Keluarga', 'type' => 'family', 'children' => $forest ];
    }

    /**
     * Create a person node with all necessary data including photo
     */
    private function createPersonNode(FamilyMember $member): array
    {
        return [
            'type' => 'person',
            'id' => $member->id,
            'name' => $member->name,
            'gender' => $member->gender,
            'photo' => $member->photo,
            'description' => $member->description,
            'nik' => $member->nik,
            'birth_date' => optional($member->birth_date)->format('Y-m-d'),
            'death_date' => optional($member->death_date)->format('Y-m-d'),
            'relation' => $member->relation,
            'children' => [],
        ];
    }

    private function buildFamilyNodeFromFamily(int $familyId, array &$visited): ?array
    {
        // Metode lama tidak lagi dipakai oleh buildFamilyTree; dipertahankan untuk kompatibilitas.
        if (in_array($familyId, $visited)) { return null; }
        $visited[] = $familyId;

        $members = FamilyMember::where('family_id', $familyId)->get();
        if ($members->isEmpty()) { return null; }

        $father = $members->firstWhere('relation', 'father');
        $mother = $members->firstWhere('relation', 'mother');

        $primary = $father ?? $mother;
        $primaryName = $primary->name ?? ("Keluarga $familyId");
        $spouseName = null;
        if ($father && $mother) {
            $spouseName = $primary === $father ? $mother->name : $father->name;
        }

        $childrenList = $members->where('relation', 'child');
        $children = [];

        foreach ($childrenList as $child) {
            $otherParentFamilies = FamilyMember::where('nik', $child->nik)
                ->where('family_id', '<>', $familyId)
                ->whereIn('relation', ['father', 'mother'])
                ->pluck('family_id')
                ->unique();

            if ($otherParentFamilies->isNotEmpty()) {
                foreach ($otherParentFamilies as $ofId) {
                    $subNode = $this->buildFamilyNodeFromFamily($ofId, $visited);
                    if ($subNode) {
                        $children[] = [
                            'name' => $child->name,
                            'spouse' => $subNode['spouse'] ?? null,
                            'children' => $subNode['children'] ?? [],
                        ];
                    } else {
                        $children[] = [
                            'name' => $child->name,
                            'spouse' => null,
                            'children' => [],
                        ];
                    }
                }
            } else {
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

    /**
     * Build a company tree structure based on parent_id relationships
     * Returns nested array suitable for d3.tree visualization
     */
    public function buildCompanyTree(int $companyId): array
    {
        $members = GroupMember::where('company_id', $companyId)->get([
            'id', 'company_id', 'name', 'nik', 'gender', 'birth_date', 'position', 'photo', 'description', 'parent_id'
        ]);

        if ($members->isEmpty()) {
            return ['name' => "Perusahaan $companyId", 'type' => 'company', 'children' => []];
        }

        // Build tree structure based on parent_id
        $memberMap = [];
        $roots = [];

        // Create map of all members
        foreach ($members as $member) {
            $memberMap[$member->id] = [
                'type' => 'person',
                'id' => $member->id,
                'name' => $member->name,
                'nik' => $member->nik,
                'gender' => $member->gender,
                'birth_date' => $member->birth_date ? $member->birth_date->format('Y-m-d') : null,
                'position' => $member->position,
                'photo' => $member->photo,
                'description' => $member->description,
                'company_id' => $member->company_id,
                'parent_id' => $member->parent_id,
                'children' => []
            ];
        }

        // Build tree structure
        foreach ($members as $member) {
            if ($member->parent_id && isset($memberMap[$member->parent_id])) {
                $memberMap[$member->parent_id]['children'][] = &$memberMap[$member->id];
            } else {
                $roots[] = &$memberMap[$member->id];
            }
        }

        // If only one root, return it directly; otherwise wrap in a container
        if (count($roots) === 1) {
            return $roots[0];
        }
        return ['name' => 'Perusahaan', 'type' => 'company', 'children' => $roots];
    }
}

