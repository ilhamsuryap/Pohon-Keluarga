<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\FamilyTreeService;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('approved');
    }

    public function index()
    {
        $user = Auth::user();
        $companies = Company::where('user_id', $user->id)
            ->withCount('members')
            ->get();
        return view('user.company.index', compact('companies'));
    }

    public function create()
    {
        return view('user.company.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'privacy' => ['nullable', Rule::in(['privat', 'publik', 'friend_only'])],
        ]);

        $company = Company::create(array_merge($validated, ['user_id' => $user->id]));

        return redirect()->route('user.company.show', $company)
                        ->with('success', 'Perusahaan berhasil dibuat.');
    }

    public function show(Company $company, FamilyTreeService $familyTreeService)
    {
        $this->authorize('view', $company);
        
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
        
        return view('user.company.show', compact('company', 'members', 'treeJson'));
    }

    public function edit(Company $company)
    {
        $this->authorize('update', $company);
        return view('user.company.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $this->authorize('update', $company);
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'privacy' => ['nullable', Rule::in(['privat', 'publik', 'friend_only'])],
        ]);

        $company->update($validated);
        return redirect()->route('user.company.show', $company)
                        ->with('success', 'Informasi perusahaan berhasil diperbarui.');
    }

    public function destroy(Company $company)
    {
        $this->authorize('delete', $company);
        $company->delete();
        return redirect()->route('user.company.index')
                        ->with('success', 'Perusahaan berhasil dihapus.');
    }
}
