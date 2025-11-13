<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\FamilyMember;
use App\Services\FamilyTreeService;
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

    public function familyTree(FamilyTreeService $familyTreeService)
    {
        $user = Auth::user();
        $family = $user->families()->with('members')->first();
        
        if (!$family) {
            return redirect()->route('dashboard')->with('error', 'Anda belum memiliki keluarga. Silakan buat keluarga terlebih dahulu.');
        }

        // Build recursive tree by NIK linking across families
        $tree = $familyTreeService->buildFamilyTree($family->id);
        $treeJson = json_encode($tree, JSON_UNESCAPED_UNICODE);
        
        return view('dashboard.family-tree', compact('family', 'treeJson'));
    }
}