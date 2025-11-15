<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Company;
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
            ->with('members')->get();
        $totalMembers = $families->sum(function($family) {
            return $family->members->count();
        });

        $companies = Company::where('user_id', $user->id)
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
            ->withCount('members')
            ->get();
        $companies = Company::where('user_id', $user->id)
            ->withCount('members')
            ->get();
        return view('user.family.index', compact('families', 'companies'));
    }

    public function familyCreate()
    {
        $user = Auth::user();
        // Check if user already has a family
        if (Family::where('user_id', $user->id)->exists()) {
            return redirect()->route('user.family.index')
                           ->with('error', 'Anda hanya dapat membuat satu keluarga.');
        }
        return view('user.family.create', ['type' => 'family']);
    }

    public function familyStore(Request $request)
    {
        $user = Auth::user();
        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'privacy' => ['nullable', Rule::in(['privat', 'publik', 'friend_only'])],
        ]);

        // Check if user already has a family
        if (Family::where('user_id', $user->id)->exists()) {
            return redirect()->route('user.family.index')
                           ->with('error', 'Anda hanya dapat membuat satu keluarga.');
        }

        // create the family
        $family = Family::create(array_merge($validated, ['user_id' => $user->id]));

        // Optionally create father/mother/children entries (simple initial creation)
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

        return redirect()->route('user.family.show', $family)
                        ->with('success', 'Keluarga berhasil dibuat.');
    }

    public function familyShow(Family $family, FamilyTreeService $familyTreeService)
    {
        $this->authorize('view', $family);
        
        $familyMembers = $family->members()->get();
        
        // Build tree data for D3.js visualization
        $treeJson = null;
        try {
            $tree = $familyTreeService->buildFamilyTree($family->id);
            $treeJson = json_encode($tree, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS | JSON_HEX_QUOT);
        } catch (\Exception $e) {
            \Log::error('Error building tree: ' . $e->getMessage());
            $treeJson = null;
        }
        
        return view('user.family.show', compact('family', 'familyMembers', 'treeJson'));
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
            'privacy' => ['nullable', Rule::in(['privat', 'publik', 'friend_only'])],
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
