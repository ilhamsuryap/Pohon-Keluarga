<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\FamilyMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

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
        $families = $user->families()->with('members')->get();
        $totalMembers = $families->sum(function($family) {
            return $family->members->count();
        });

        return view('user.dashboard', compact('families', 'totalMembers'));
    }

    public function pendingApproval()
    {
        return view('user.pending-approval');
    }

    public function familyIndex()
    {
        $user = Auth::user();
        $families = $user->families()->withCount('members')->get();
        return view('user.family.index', compact('families'));
    }

    public function familyCreate()
    {
        $user = Auth::user();
        if ($user->families()->exists()) {
            return redirect()->route('user.family.index')
                           ->with('error', 'Anda hanya dapat membuat satu keluarga.');
        }
        return view('user.family.create');
    }

    public function familyStore(Request $request)
    {
        $user = Auth::user();
        if ($user->families()->exists()) {
            return redirect()->route('user.family.index')
                           ->with('error', 'Anda hanya dapat membuat satu keluarga.');
        }

        $validated = $request->validate([
            'family_name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $family = $user->families()->create($validated);
        return redirect()->route('user.family.show', $family)
                        ->with('success', 'Keluarga berhasil dibuat.');
    }

    public function familyShow(Family $family)
    {
        $this->authorize('view', $family);
        $familyMembers = $family->members()->get();
        return view('user.family.show', compact('family', 'familyMembers'));
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
