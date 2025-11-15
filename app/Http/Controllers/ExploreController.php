<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\FamilyTreeService;

class ExploreController extends Controller
{
    /**
     * Show the explore page
     */
    public function index()
    {
        return view('explore.index');
    }

    /**
     * View family diagram (public access with privacy check)
     */
    public function viewFamily(Family $family, FamilyTreeService $familyTreeService)
    {
        $user = Auth::user();
        
        // Check privacy
        if ($family->privacy === 'privat') {
            abort(403, 'Diagram keluarga ini bersifat privat.');
        }
        
        if ($family->privacy === 'friend_only' && !$user) {
            abort(403, 'Anda harus login untuk melihat diagram ini.');
        }
        
        $familyMembers = $family->members()->with(['parent', 'children'])->get();
        
        // Build tree data for D3.js visualization
        $treeJson = null;
        try {
            $tree = $familyTreeService->buildFamilyTree($family->id);
            $treeJson = json_encode($tree, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT);
        } catch (\Exception $e) {
            \Log::error('Error building family tree: ' . $e->getMessage());
            $treeJson = null;
        }
        
        // Pass readonly flag to view (no edit/delete buttons for public view)
        $readonly = true;
        
        return view('user.family.show', compact('family', 'familyMembers', 'treeJson', 'readonly'));
    }

    /**
     * View company diagram (public access with privacy check)
     */
    public function viewCompany(Company $company, FamilyTreeService $familyTreeService)
    {
        $user = Auth::user();
        
        // Check privacy
        if ($company->privacy === 'privat') {
            abort(403, 'Diagram perusahaan ini bersifat privat.');
        }
        
        if ($company->privacy === 'friend_only' && !$user) {
            abort(403, 'Anda harus login untuk melihat diagram ini.');
        }
        
        $companyMembers = $company->members()->with('children')->get();
        
        // Build tree data for D3.js visualization
        $treeJson = null;
        try {
            $tree = $familyTreeService->buildCompanyTree($company->id);
            $treeJson = json_encode($tree, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT);
        } catch (\Exception $e) {
            \Log::error('Error building company tree: ' . $e->getMessage());
            $treeJson = null;
        }
        
        // Map members for view
        $members = $companyMembers->map(function($member) {
            return [
                'id' => $member->id,
                'company_id' => $member->company_id,
                'name' => $member->name,
                'nik' => $member->nik,
                'gender' => $member->gender,
                'birth_date' => $member->birth_date ? $member->birth_date->format('Y-m-d') : null,
                'position' => $member->position,
                'role' => $member->role,
                'photo' => $member->photo,
                'description' => $member->description,
                'parent_id' => $member->parent_id,
            ];
        });
        
        // Pass readonly flag to view (no edit/delete buttons for public view)
        $readonly = true;
        
        return view('user.company.show', compact('company', 'members', 'treeJson', 'readonly'));
    }

    /**
     * Search families (realtime)
     */
    public function searchFamilies(Request $request)
    {
        $query = $request->get('q', '');
        $user = Auth::user();

        // Build query based on privacy
        $familiesQuery = Family::query();

        if ($user) {
            // User is logged in: show public and friend_only
            $familiesQuery->whereIn('privacy', ['publik', 'friend_only']);
        } else {
            // Guest: only show public
            $familiesQuery->where('privacy', 'publik');
        }

        // Search by family name (case-insensitive)
        if ($query) {
            $familiesQuery->whereRaw('LOWER(family_name) LIKE ?', ['%' . strtolower($query) . '%']);
        }

        $families = $familiesQuery->withCount('members')
            ->with('user:id,name')
            ->orderBy('family_name')
            ->limit(20)
            ->get();

        return response()->json([
            'families' => $families->map(function ($family) {
                return [
                    'id' => $family->id,
                    'name' => $family->family_name,
                    'description' => $family->description,
                    'members_count' => $family->members_count,
                    'privacy' => $family->privacy,
                    'owner' => $family->user->name ?? 'Unknown',
                ];
            })
        ]);
    }

    /**
     * Search companies (realtime)
     */
    public function searchCompanies(Request $request)
    {
        $query = $request->get('q', '');
        $user = Auth::user();

        // Build query based on privacy
        $companiesQuery = Company::query();

        if ($user) {
            // User is logged in: show public and friend_only
            $companiesQuery->whereIn('privacy', ['publik', 'friend_only']);
        } else {
            // Guest: only show public
            $companiesQuery->where('privacy', 'publik');
        }

        // Search by company name (case-insensitive)
        if ($query) {
            $companiesQuery->whereRaw('LOWER(company_name) LIKE ?', ['%' . strtolower($query) . '%']);
        }

        $companies = $companiesQuery->withCount('members')
            ->with('user:id,name')
            ->orderBy('company_name')
            ->limit(20)
            ->get();

        return response()->json([
            'companies' => $companies->map(function ($company) {
                return [
                    'id' => $company->id,
                    'name' => $company->company_name,
                    'description' => $company->description,
                    'members_count' => $company->members_count,
                    'privacy' => $company->privacy,
                    'owner' => $company->user->name ?? 'Unknown',
                ];
            })
        ]);
    }
}

