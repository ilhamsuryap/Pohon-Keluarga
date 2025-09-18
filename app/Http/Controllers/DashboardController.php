<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('approved');
    }

    public function index()
    {
        $user = Auth::user();
        $family = $user->families()->first();
        $totalMembers = 0;
        
        if ($family) {
            $totalMembers = $family->members()->count();
        }

        return view('dashboard.index', compact('family', 'totalMembers'));
    }

    public function familyTree()
    {
        $user = Auth::user();
        $family = $user->families()->with('members')->first();
        
        if (!$family) {
            return redirect()->route('dashboard')->with('error', 'Anda belum memiliki keluarga. Silakan buat keluarga terlebih dahulu.');
        }

        $treeData = $this->buildFamilyTree($family);
        
        return view('dashboard.family-tree', compact('family', 'treeData'));
    }

    private function buildFamilyTree($family)
    {
        $members = $family->members;
        $parents = $members->whereIn('relation', ['father', 'mother']);
        $children = $members->where('relation', 'child');

        $tree = [
            'parents' => $parents,
            'children' => $children->groupBy('parent_id')
        ];

        return $tree;
    }
}