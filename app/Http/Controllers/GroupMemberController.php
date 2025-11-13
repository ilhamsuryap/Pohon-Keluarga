<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\GroupMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GroupMemberController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('approved');
    }

    protected function authorizeFamily(Family $family)
    {
        if ($family->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }
    }

    public function store(Request $request, Family $family)
    {
        $this->authorizeFamily($family);

        if ($family->type !== 'company') {
            return redirect()->back()->with('error', 'Operasi hanya untuk grup berjenis Perusahaan.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:director,manager,staff,intern',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('group_photos', 'public');
            $validated['photo'] = $photoPath;
        }

        $validated['family_id'] = $family->id;

        GroupMember::create($validated);

        return redirect()->route('user.family.show', $family)->with('success', 'Anggota perusahaan berhasil ditambahkan.');
    }

    public function edit(Family $family, GroupMember $member)
    {
        $this->authorizeFamily($family);
        if ($member->family_id !== $family->id) {
            abort(404);
        }

        // return JSON for modal population
        return response()->json($member);
    }

    public function update(Request $request, Family $family, GroupMember $member)
    {
        $this->authorizeFamily($family);
        if ($member->family_id !== $family->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:director,manager,staff,intern',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            // delete old photo if exists
            if ($member->photo) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($member->photo);
            }
            $validated['photo'] = $request->file('photo')->store('group_photos', 'public');
        }

        $member->update($validated);

        return redirect()->route('user.family.show', $family)->with('success', 'Anggota perusahaan berhasil diperbarui.');
    }

    public function destroy(Family $family, GroupMember $member)
    {
        $this->authorizeFamily($family);
        if ($member->family_id !== $family->id) {
            abort(404);
        }

        if ($member->photo) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()->route('user.family.show', $family)->with('success', 'Anggota perusahaan berhasil dihapus.');
    }
}
