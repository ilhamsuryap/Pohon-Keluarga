<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class GroupMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('approved');
    }

    protected function authorizeCompany(Company $company)
    {
        if ($company->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(Request $request, Company $company)
    {
        $this->authorizeCompany($company);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|size:16|unique:group_members,nik,NULL,id,company_id,' . $company->id,
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'position' => 'nullable|string|max:255',
            'role' => 'nullable|in:director,manager,staff,intern',
            'parent_id' => 'nullable|exists:group_members,id',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Validate parent_id belongs to same company
        if ($request->filled('parent_id')) {
            $parent = GroupMember::find($request->parent_id);
            if (!$parent || $parent->company_id !== $company->id) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Parent tidak valid atau tidak berada di perusahaan yang sama.');
            }
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('group_photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $validated['company_id'] = $company->id;

        GroupMember::create($validated);

        return redirect()->route('user.company.show', $company)->with('success', 'Anggota perusahaan berhasil ditambahkan.');
    }

    public function edit(Company $company, GroupMember $member)
    {
        $this->authorizeCompany($company);
        if ($member->company_id !== $company->id) {
            abort(404);
        }

        // return JSON for modal population
        return response()->json($member);
    }

    public function update(Request $request, Company $company, GroupMember $member)
    {
        $this->authorizeCompany($company);
        if ($member->company_id !== $company->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|size:16|unique:group_members,nik,' . $member->id . ',id,company_id,' . $company->id,
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'position' => 'nullable|string|max:255',
            'role' => 'nullable|in:director,manager,staff,intern',
            'parent_id' => 'nullable|exists:group_members,id',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Validate parent_id belongs to same company and not creating circular reference
        if ($request->filled('parent_id')) {
            $parent = GroupMember::find($request->parent_id);
            if (!$parent || $parent->company_id !== $company->id) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Parent tidak valid atau tidak berada di perusahaan yang sama.');
            }
            // Prevent circular reference (parent cannot be a descendant of this member)
            if ($this->isDescendant($member->id, $request->parent_id)) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Tidak dapat menetapkan parent karena akan menciptakan referensi melingkar.');
            }
        }

        if ($request->hasFile('photo')) {
            // delete old photo if exists
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $request->file('photo')->store('group_photos', 'public');
        }

        $member->update($validated);

        return redirect()->route('user.company.show', $company)->with('success', 'Anggota perusahaan berhasil diperbarui.');
    }

    public function destroy(Company $company, GroupMember $member)
    {
        $this->authorizeCompany($company);
        if ($member->company_id !== $company->id) {
            abort(404);
        }

        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->route('user.company.show', $company)->with('success', 'Anggota perusahaan berhasil dihapus.');
    }

    /**
     * Add child to a company member
     */
    public function addChild(Request $request, Company $company, GroupMember $member)
    {
        $this->authorizeCompany($company);
        
        if ($member->company_id !== $company->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|size:16|unique:group_members,nik,NULL,id,company_id,' . $company->id,
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'position' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('group_photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $validated['company_id'] = $company->id;
        $validated['parent_id'] = $member->id;

        GroupMember::create($validated);

        return redirect()->route('user.company.show', $company)->with('success', 'Anggota perusahaan berhasil ditambahkan sebagai child.');
    }

    /**
     * Get list of positions used in the company
     */
    public function getPositions(Company $company)
    {
        $this->authorizeCompany($company);

        $positions = GroupMember::where('company_id', $company->id)
            ->whereNotNull('position')
            ->distinct()
            ->pluck('position')
            ->filter()
            ->values();

        return response()->json($positions);
    }

    /**
     * Check if a member is a descendant of another member (prevent circular reference)
     */
    private function isDescendant($memberId, $potentialAncestorId)
    {
        $current = GroupMember::find($potentialAncestorId);
        while ($current && $current->parent_id) {
            if ($current->parent_id == $memberId) {
                return true;
            }
            $current = $current->parent;
        }
        return false;
    }
}
