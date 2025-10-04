<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Family;
use App\Models\FamilyMember;
use App\Services\FamilyTreeService;

class FamilyTreeServiceTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_merges_families_by_shared_nik()
    {
        // Create two users and families
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $familyA = Family::create(['user_id' => $user1->id, 'family_name' => 'Eko Family']);
        $familyB = Family::create(['user_id' => $user2->id, 'family_name' => 'Setyo Family']);

        // Family A: father, mother, child (NIK A), child2
        $fatherA = FamilyMember::create(['family_id' => $familyA->id, 'name' => 'Bapak Eko', 'nik' => '1111111111111111', 'gender' => 'male', 'birth_date' => '1960-01-01', 'relation' => 'father']);
        $motherA = FamilyMember::create(['family_id' => $familyA->id, 'name' => 'Ibu Eko', 'nik' => '2222222222222222', 'gender' => 'female', 'birth_date' => '1965-01-01', 'relation' => 'mother']);
        $childA = FamilyMember::create(['family_id' => $familyA->id, 'name' => 'Anak A', 'nik' => '3333333333333333', 'gender' => 'male', 'birth_date' => '1990-01-01', 'relation' => 'child', 'parent_id' => $fatherA->id]);
        $childA2 = FamilyMember::create(['family_id' => $familyA->id, 'name' => 'Anak 2', 'nik' => null, 'gender' => 'female', 'birth_date' => '1992-01-01', 'relation' => 'child', 'parent_id' => $fatherA->id]);

        // Family B: member with same NIK as childA (as father), and child NIK B
        $fatherB = FamilyMember::create(['family_id' => $familyB->id, 'name' => 'Bapak Setyo', 'nik' => '3333333333333333', 'gender' => 'male', 'birth_date' => '1990-01-01', 'relation' => 'father']);
        $childB = FamilyMember::create(['family_id' => $familyB->id, 'name' => 'Anak B', 'nik' => '4444444444444444', 'gender' => 'male', 'birth_date' => '2015-01-01', 'relation' => 'child', 'parent_id' => $fatherB->id]);

        $service = new FamilyTreeService();
        $tree = $service->buildTree($familyA);

        // The merged tree should contain the canonical node for NIK 3333 and have Anak B as its child
        $this->assertIsArray($tree);

        // Convert tree to flat list of NIKs present
        $flat = [];
        $walker = function ($node) use (&$walker, &$flat) {
            if (isset($node['nik'])) {
                $flat[] = $node['nik'];
            }
            foreach ($node['children'] ?? [] as $c) {
                $walker($c);
            }
        };
        $walker($tree);

        $this->assertContains('3333333333333333', $flat);
        $this->assertContains('4444444444444444', $flat);

        // Ensure Anak B (4444) is a descendant of node 3333
        $found3333 = null;
        $search = function ($node, $nik) use (&$search, &$found3333) {
            if (($node['nik'] ?? null) === $nik) {
                $found3333 = $node;
                return true;
            }
            foreach ($node['children'] ?? [] as $c) {
                if ($search($c, $nik)) return true;
            }
            return false;
        };
        $search($tree, '3333333333333333');
        $this->assertNotNull($found3333);

        $hasChild4444 = false;
        $checkChildren = function ($node) use (&$checkChildren, &$hasChild4444) {
            foreach ($node['children'] ?? [] as $c) {
                if (($c['nik'] ?? null) === '4444444444444444') {
                    $hasChild4444 = true;
                    return;
                }
                $checkChildren($c);
            }
        };
        $checkChildren($found3333);
        $this->assertTrue($hasChild4444, 'Expected NIK 4444 to be a descendant of NIK 3333');
    }
}
