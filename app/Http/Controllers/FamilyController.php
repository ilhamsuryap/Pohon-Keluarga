<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\FamilyMember;
use Dompdf\Dompdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FamilyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('approved');
    }

    public function exportPdf(Family $family)
    {
        // Ensure user has access to this family
        if ($family->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get all family members with relationships loaded
        $familyMembers = $family->members()
            ->with(['parent', 'children'])
            ->orderBy('relation')
            ->get();

        // Generate the HTML content with the diagram
        $html = view('exports.family-tree-diagram', [
            'family' => $family,
            'familyMembers' => $familyMembers
        ])->render();

        // Initialize dompdf with options
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true); // Enable loading remote images
        $dompdf = new \Dompdf\Dompdf($options);

        // Load HTML content
        $dompdf->loadHtml($html);

        // Set paper size and orientation (use landscape for better diagram display)
        $dompdf->setPaper('A4', 'landscape');

        // Render the PDF
        $dompdf->render();

        // Generate file name
        $fileName = $family->family_name . '_silsilah.pdf';

        // Download the PDF
        return response($dompdf->output(), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"'
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        // Check if user already has a family
        if ($user->families()->exists()) {
            return redirect()->route('dashboard')->with('error', 'Anda sudah memiliki keluarga. Setiap user hanya dapat memiliki satu keluarga.');
        }

        return view('family.create');
    }

    public function store(Request $request, Family $family)
    {
        // Ensure user has access to this family
        if ($family->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            // Allow same NIK across different families (to enable merging),
            // but prevent duplicate NIK within the same family
            'nik' => 'nullable|string|size:16|unique:family_members,nik,NULL,id,family_id,' . $family->id,
            'gender' => 'required|in:male,female',
            'relation' => 'required|in:father,mother,child',
            'birth_date' => 'required|date',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if father or mother already exists for this family
        if ($request->relation === 'father') {
            $existingFather = $family->members()->where('relation', 'father')->first();
            if ($existingFather) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Keluarga ini sudah memiliki ayah. Setiap keluarga hanya dapat memiliki satu ayah.');
            }
        }

        if ($request->relation === 'mother') {
            $existingMother = $family->members()->where('relation', 'mother')->first();
            if ($existingMother) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Keluarga ini sudah memiliki ibu. Setiap keluarga hanya dapat memiliki satu ibu.');
            }
        }

        // Handle photo upload if provided
        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('family_photos', 'public');
        }

        // Optional info: if this NIK exists in other family, inform user that trees will be connected
        if ($request->filled('nik')) {
            $existsInOtherFamily = FamilyMember::where('nik', $request->nik)
                ->where('family_id', '<>', $family->id)
                ->exists();
            if ($existsInOtherFamily) {
                session()->flash('info', 'NIK yang sama ditemukan di keluarga lain. Pohon akan terhubung secara otomatis.');
            }
        }

        // Create the new family member
        try {
            $member = FamilyMember::create([
                'family_id' => $family->id,
                'name' => $request->name,
                'nik' => $request->nik,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'relation' => $request->relation,
                'description' => $request->description,
                'photo' => $photoPath,
            ]);

            return redirect()
                ->route('user.family.show', $family)
                ->with('success', 'Anggota keluarga berhasil ditambahkan');
        } catch (\Exception $e) {
            // Delete uploaded photo if member creation fails
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function addChild(Request $request)
    {
        $user = Auth::user();
        $family = $user->families()->first();

        if (!$family) {
            return redirect()->route('dashboard')->with('error', 'Anda belum memiliki keluarga.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required|date',
            'parent_id' => 'nullable|exists:family_members,id',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check for duplicate
        $duplicate = FamilyMember::findDuplicate($request->name, $request->birth_date);
        if ($duplicate && $duplicate->family_id !== $family->id) {
            // Auto-connect to existing family member
            return redirect()->back()->with('info',
                'Anggota keluarga dengan nama dan tanggal lahir yang sama sudah ada. Silakan hubungi admin untuk menghubungkan keluarga.');
        }

        // Check if parent can have children
        if ($request->parent_id) {
            $parent = FamilyMember::find($request->parent_id);
            if ($parent && !$parent->canHaveChildren()) {
                return redirect()->back()->with('error', 'Orang tua ini sudah memiliki anak dan tidak dapat menambah anak lagi.');
            }
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('family_photos', 'public');
        }

        $child = FamilyMember::create([
            'family_id' => $family->id,
            'name' => $request->name,
            'gender' => $request->gender,
            'birth_date' => $request->birth_date,
            'relation' => 'child',
            'parent_id' => $request->parent_id,
            'description' => $request->description,
            'photo' => $photoPath,
        ]);

        // Update parent's has_children status
        if ($request->parent_id) {
            $parent = FamilyMember::find($request->parent_id);
            $parent->update(['has_children' => true]);
        }

        return redirect()->route('family.tree')->with('success', 'Anak berhasil ditambahkan!');
    }

    public function showAddChildForm()
    {
        $user = Auth::user();
        $family = $user->families()->first();

        if (!$family) {
            return redirect()->route('dashboard')->with('error', 'Anda belum memiliki keluarga.');
        }

        $parents = $family->members()->whereIn('relation', ['father', 'mother'])->get();
        $availableParents = $family->members()->where('has_children', false)->get();

        return view('family.add-child', compact('family', 'parents', 'availableParents'));
    }

    public function show(Family $family)
    {
        // Ensure user has access to this family
        if ($family->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Get all family members with relationships loaded
        $familyMembers = $family->members()
            ->with(['parent', 'children'])
            ->orderBy('relation')
            ->get();

        return view('user.family.show', compact('family', 'familyMembers'));
    }

    public function edit(Family $family, FamilyMember $member)
    {
        // Ensure user has access to this family
        if ($family->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure member belongs to this family
        if ($member->family_id !== $family->id) {
            abort(404, 'Member not found in this family.');
        }

        // Get all family members for the tree view
        $familyMembers = $family->members()
            ->with(['parent', 'children'])
            ->orderBy('relation')
            ->get();

        // Pass selected member for edit form
        return view('user.family.show', [
            'family' => $family,
            'familyMembers' => $familyMembers,
            'selectedMember' => $member
        ]);
    }

    public function update(Request $request, Family $family, FamilyMember $member)
    {
        // Ensure user has access to this family
        if ($family->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure member belongs to this family
        if ($member->family_id !== $family->id) {
            abort(404, 'Member not found in this family.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'nullable|string|size:16|unique:family_members,nik,' . $member->id,
            'gender' => 'required|in:male,female',
            'relation' => 'required|in:father,mother,child',
            'birth_date' => 'required|date',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Check if trying to change relation to father/mother when one already exists
        if ($request->relation !== $member->relation) {
            if ($request->relation === 'father') {
                $existingFather = $family->members()->where('relation', 'father')->where('id', '!=', $member->id)->first();
                if ($existingFather) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Keluarga ini sudah memiliki ayah. Setiap keluarga hanya dapat memiliki satu ayah.');
                }
            }

            if ($request->relation === 'mother') {
                $existingMother = $family->members()->where('relation', 'mother')->where('id', '!=', $member->id)->first();
                if ($existingMother) {
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Keluarga ini sudah memiliki ibu. Setiap keluarga hanya dapat memiliki satu ibu.');
                }
            }
        }

        $data = $request->except('photo');

        // Handle photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($member->photo) {
                Storage::disk('public')->delete($member->photo);
            }

            $data['photo'] = $request->file('photo')->store('family_photos', 'public');
        }

        try {
            $member->update($data);

            return redirect()
                ->route('user.family.show', $family)
                ->with('success', 'Anggota keluarga berhasil diperbarui');
        } catch (\Exception $e) {
            // Delete uploaded photo if update fails
            if ($request->hasFile('photo') && isset($data['photo'])) {
                Storage::disk('public')->delete($data['photo']);
            }
            
            return redirect()->back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(Family $family, FamilyMember $member)
    {
        // Ensure user has access to this family
        if ($family->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure member belongs to this family
        if ($member->family_id !== $family->id) {
            abort(404, 'Member not found in this family.');
        }

        // Delete photo if exists
        if ($member->photo) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        return redirect()
            ->route('user.family.show', $family)
            ->with('success', 'Anggota keluarga berhasil dihapus');
    }

    /**
     * Get family suggestions based on NIK
     */
    public function getFamilySuggestions(Family $family, FamilyMember $member)
    {
        // Ensure user has access to this family
        if ($family->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure member belongs to this family
        if ($member->family_id !== $family->id) {
            abort(404, 'Member not found in this family.');
        }

        $suggestions = $member->getFamilySuggestions();

        return response()->json([
            'suggestions' => $suggestions,
            'member' => $member->only(['id', 'name', 'nik'])
        ]);
    }

    /**
     * Connect family member to another family based on NIK suggestion
     */
    public function connectFamily(Request $request, Family $family, FamilyMember $member)
    {
        // Ensure user has access to this family
        if ($family->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Ensure member belongs to this family
        if ($member->family_id !== $family->id) {
            abort(404, 'Member not found in this family.');
        }

        $request->validate([
            'target_member_id' => 'required|exists:family_members,id',
            'relationship' => 'required|in:father,mother,child,sibling'
        ]);

        $targetMember = FamilyMember::findOrFail($request->target_member_id);

        // Verify NIK compatibility
        if (!FamilyMember::isSameFamily($member->nik, $targetMember->nik)) {
            return response()->json([
                'success' => false,
                'message' => 'NIK tidak kompatibel untuk koneksi keluarga'
            ], 400);
        }

        try {
            // Handle different relationship types
            switch ($request->relationship) {
                case 'father':
                case 'mother':
                    // Set target as parent of current member
                    $member->update(['parent_id' => $targetMember->id]);
                    break;
                
                case 'child':
                    // Set current member as parent of target
                    $targetMember->update(['parent_id' => $member->id]);
                    $member->update(['has_children' => true]);
                    break;
                
                case 'sibling':
                    // If one has a parent, set the same parent for the other
                    if ($member->parent_id) {
                        $targetMember->update(['parent_id' => $member->parent_id]);
                    } elseif ($targetMember->parent_id) {
                        $member->update(['parent_id' => $targetMember->parent_id]);
                    }
                    break;
            }

            return response()->json([
                'success' => true,
                'message' => 'Koneksi keluarga berhasil dibuat'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat koneksi: ' . $e->getMessage()
            ], 500);
        }
    }
}
