<?php

namespace App\Http\Controllers;

use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PaymentProofController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->middleware('auth');
        $this->whatsappService = $whatsappService;
    }

    /**
     * Show the payment proof upload form
     */
    public function show()
    {
        $user = Auth::user();
        
        // Redirect admin to dashboard
        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Redirect approved users to dashboard
        if ($user->isApproved()) {
            return redirect()->route('user.dashboard');
        }

        return view('payment-proof.upload', compact('user'));
    }

    /**
     * Handle payment proof upload
     */
    public function upload(Request $request)
    {
        $user = Auth::user();

        // Validate the request
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:5120', // 5MB max
        ], [
            'payment_proof.required' => 'Bukti pembayaran harus diupload.',
            'payment_proof.image' => 'File harus berupa gambar.',
            'payment_proof.mimes' => 'Format file harus JPEG, PNG, atau JPG.',
            'payment_proof.max' => 'Ukuran file maksimal 5MB.',
        ]);

        try {
            // Delete old payment proof if exists
            if ($user->payment_proof) {
                Storage::disk('public')->delete($user->payment_proof);
            }

            // Store the new payment proof
            $path = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Update user record
            $user->update([
                'payment_proof' => $path,
                'payment_proof_uploaded_at' => now(),
                'payment_status' => 'pending'
            ]);

            // Send WhatsApp notification to admin
            $this->whatsappService->notifyAdminNewPayment($user);

            return redirect()->route('pending-approval')
                ->with('success', 'Bukti pembayaran berhasil diupload! Admin akan memverifikasi pembayaran Anda dan Anda akan mendapat notifikasi WhatsApp setelah disetujui.');

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengupload bukti pembayaran. Silakan coba lagi.');
        }
    }

    /**
     * Show uploaded payment proof
     */
    public function view()
    {
        $user = Auth::user();
        
        if (!$user->hasUploadedPaymentProof()) {
            return redirect()->route('payment-proof.upload')
                ->with('error', 'Anda belum mengupload bukti pembayaran.');
        }

        return view('payment-proof.view', compact('user'));
    }

    /**
     * Delete payment proof and allow re-upload
     */
    public function delete()
    {
        $user = Auth::user();
        
        if ($user->payment_proof) {
            Storage::disk('public')->delete($user->payment_proof);
            
            $user->update([
                'payment_proof' => null,
                'payment_proof_uploaded_at' => null,
                'payment_status' => 'pending'
            ]);
        }

        return redirect()->route('payment-proof.upload')
            ->with('success', 'Bukti pembayaran berhasil dihapus. Silakan upload ulang.');
    }
}