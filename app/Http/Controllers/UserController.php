<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use App\Services\FamilyTreeService;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('approved')->except('pendingApproval');
    }

    public function dashboard()
    {
        $user = Auth::user();
        $families = Family::where('user_id', $user->id)
            ->where('type', 'family')
            ->with('members')->get();
        $totalMembers = $families->sum(function($family) {
            return $family->members->count();
        });

        $companies = Family::where('user_id', $user->id)
            ->where('type', 'company')
            ->with('members')->get();
        $totalCompanyMembers = $companies->sum(function($company) {
            return $company->members->count();
        });

        return view('user.dashboard', compact('families', 'totalMembers', 'companies', 'totalCompanyMembers'));
    }

    public function pendingApproval()
    {
        return view('user.pending-approval');
    }

    public function familyIndex()
    {
        $user = Auth::user();
        $families = Family::where('user_id', $user->id)
            ->where('type', 'family')
            ->withCount('members')
            ->get();
        $companies = Family::where('user_id', $user->id)
            ->where('type', 'company')
            ->withCount('members')
            ->get();
        return view('user.family.index', compact('families', 'companies'));
    }

    public function familyCreate()
    {
        $user = Auth::user();
        if (Family::where('user_id', $user->id)->exists()) {
            return redirect()->route('user.family.index')
                           ->with('error', 'Anda hanya dapat membuat satu keluarga.');
        }
        return view('user.family.create');
    }

    public function familyStore(Request $request)
    {
        $user = Auth::user();
        if (Family::where('user_id', $user->id)->exists()) {
            return redirect()->route('user.family.index')
                           ->with('error', 'Anda hanya dapat membuat satu keluarga.');
        }

        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => ['required', Rule::in(['family','company'])],
        ]);

        // create the family/group
        $family = Family::create(array_merge($validated, ['user_id' => $user->id]));

        // If creating a family, optionally create father/mother/children entries (simple initial creation)
        if ($validated['type'] === 'family') {
            // father
            if ($request->filled('father_name')) {
                $family->members()->create([
                    'name' => $request->input('father_name'),
                    'relation' => 'father',
                    'gender' => 'male'
                ]);
            }
            // mother
            if ($request->filled('mother_name')) {
                $family->members()->create([
                    'name' => $request->input('mother_name'),
                    'relation' => 'mother',
                    'gender' => 'female'
                ]);
            }
            // children
            if ($request->filled('children')) {
                foreach ((array) $request->input('children') as $childName) {
                    if (trim($childName) === '') continue;
                    $family->members()->create([
                        'name' => $childName,
                        'relation' => 'child',
                        'gender' => 'male'
                    ]);
                }
            }
        }

        // If creating a company, create group members in group_members table
        if ($validated['type'] === 'company') {
            // director
            if ($request->filled('company_director')) {
                \App\Models\GroupMember::create([
                    'family_id' => $family->id,
                    'name' => $request->input('company_director'),
                    'role' => 'director'
                ]);
            }

            // managers
            if ($request->filled('company_managers')) {
                foreach ((array) $request->input('company_managers') as $m) {
                    if (trim($m) === '') continue;
                    \App\Models\GroupMember::create([
                        'family_id' => $family->id,
                        'name' => $m,
                        'role' => 'manager'
                    ]);
                }
            }

            // staffs
            if ($request->filled('company_staffs')) {
                foreach ((array) $request->input('company_staffs') as $s) {
                    if (trim($s) === '') continue;
                    \App\Models\GroupMember::create([
                        'family_id' => $family->id,
                        'name' => $s,
                        'role' => 'staff'
                    ]);
                }
            }

            // interns
            if ($request->filled('company_interns')) {
                foreach ((array) $request->input('company_interns') as $i) {
                    if (trim($i) === '') continue;
                    \App\Models\GroupMember::create([
                        'family_id' => $family->id,
                        'name' => $i,
                        'role' => 'intern'
                    ]);
                }
            }
        }

        return redirect()->route('user.family.show', $family)
                        ->with('success', 'Grup berhasil dibuat.');
    }

    public function familyShow(Family $family)
    {
        $this->authorize('view', $family);
        $familyMembers = $family->members()->get();
        return view('user.family.show', compact('family', 'familyMembers'));
    }

    public function familyTree(Request $request, FamilyTreeService $familyTreeService)
    {
        $user = Auth::user();
        $family = Family::where('user_id', $user->id)->with('members')->first();

        if (!$family) {
            return redirect()->route('user.family.index')->with('error', 'Anda belum memiliki keluarga.');
        }

        // Build recursive tree by NIK linking across families
        $tree = $familyTreeService->buildFamilyTree($family->id);
        $treeJson = json_encode($tree, JSON_UNESCAPED_UNICODE);

        return view('dashboard.family-tree', compact('family', 'treeJson'));
    }

    public function familyEdit(Family $family)
    {
        $this->authorize('update', $family);
        return view('user.family.edit', compact('family'));
    }

    public function familyUpdate(Request $request, Family $family)
    {
        $this->authorize('update', $family);
        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $family->update($validated);
        return redirect()->route('user.family.show', $family)
                        ->with('success', 'Informasi keluarga berhasil diperbarui.');
    }

    public function familyDestroy(Family $family)
    {
        $this->authorize('delete', $family);
        $family->delete();
        return redirect()->route('user.family.index')
                        ->with('success', 'Keluarga berhasil dihapus.');
    }

    public function familyMemberStore(Request $request, Family $family)
    {
        $this->authorize('update', $family);

        // Check if there's already a father or mother
        if (in_array($request->relation, ['father', 'mother'])) {
            $exists = $family->members()
                            ->where('relation', $request->relation)
                            ->exists();
            if ($exists) {
                return back()->with('error', 'Sudah ada ' .
                    ($request->relation === 'father' ? 'Ayah' : 'Ibu') .
                    ' dalam keluarga ini.');
            }
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'relation' => ['required', Rule::in(['father', 'mother', 'child'])],
            'gender' => ['required', Rule::in(['male', 'female'])],
            'birth_date' => 'required|date',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Check if this person already exists in the system
        $existingMember = FamilyMember::where('name', $validated['name'])
            ->where('birth_date', $validated['birth_date'])
            ->first();

        if ($existingMember) {
            return back()->with('error', 'Anggota keluarga ini sudah terdaftar di sistem.');
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('family-photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $family->members()->create($validated);
        return back()->with('success', 'Anggota keluarga berhasil ditambahkan.');
    }

    public function familyMemberUpdate(Request $request, Family $family, FamilyMember $member)
    {
        $this->authorize('update', $family);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'description' => 'nullable|string',
        ]);

        // Check if this would create a duplicate
        $exists = FamilyMember::where('name', $validated['name'])
            ->where('birth_date', $validated['birth_date'])
            ->where('id', '!=', $member->id)
            ->exists();

        if ($exists) {
            return response()->json(['error' => 'Anggota keluarga ini sudah terdaftar di sistem.'], 422);
        }

        $member->update($validated);
        return response()->json(['success' => true]);
    }

    public function familyMemberDestroy(Family $family, FamilyMember $member)
    {
        $this->authorize('update', $family);

        if ($member->has_children) {
            return back()->with('error', 'Tidak dapat menghapus anggota keluarga yang memiliki anak.');
        }

        $member->delete();
        return back()->with('success', 'Anggota keluarga berhasil dihapus.');
    }
}
