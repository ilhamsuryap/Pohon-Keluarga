<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Family;
use App\Models\PaymentSetting;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->middleware('auth');
        $this->middleware('admin');
        $this->whatsappService = $whatsappService;
    }

    public function dashboard()
    {
        $totalUsers = User::where('role', 'user')->count();
        $pendingApprovals = User::where('is_approved', false)->count();
        $totalFamilies = Family::count();
        $pendingPayments = User::where('payment_status', 'pending')->count();

        return view('admin.dashboard', compact(
            'totalUsers', 
            'pendingApprovals', 
            'totalFamilies', 
            'pendingPayments'
        ));
    }

    public function users()
    {
        $users = User::where('role', 'user')->with('families')->paginate(10);
        return view('admin.users', compact('users'));
    }

    public function approveUser(Request $request, User $user)
    {
        $user->update([
            'is_approved' => true,
            'payment_status' => 'approved',
            'payment_date' => now()
        ]);

        // Send WhatsApp notification to user
        $this->whatsappService->notifyUserApproval($user);

        return back()->with('success', 'User berhasil disetujui dan notifikasi WhatsApp telah dikirim.');
    }

    public function paymentSettings()
    {
        $setting = PaymentSetting::first();
        return view('admin.payment-settings', compact('setting'));
    }

    public function updatePaymentSettings(Request $request)
    {
        $request->validate([
            'registration_fee' => 'required|numeric|min:0',
        ]);

        PaymentSetting::updateOrCreate(
            ['id' => 1],
            [
                'registration_fee' => $request->registration_fee,
                'is_active' => true,
            ]
        );

        return back()->with('success', 'Pengaturan pembayaran berhasil diperbarui.');
    }

    public function confirmPayment(Request $request, User $user)
    {
        $user->update([
            'payment_status' => 'paid',
            'payment_date' => now(),
        ]);

        // Send WhatsApp notification
        $message = "Halo {$user->name},\n\n";
        $message .= "Pembayaran Anda telah dikonfirmasi!\n";
        $message .= "Jumlah: Rp " . number_format($user->payment_amount, 0, ',', '.') . "\n\n";
        $message .= "Akun Anda akan segera diaktifkan setelah persetujuan admin.\n";
        $message .= "Terima kasih!";

        $this->whatsappService->sendMessage($user->phone, $message);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi dan notifikasi telah dikirim.');
    }

    public function rejectPayment(Request $request, User $user)
    {
        $request->validate([
            'rejection_reason' => 'nullable|string|max:500'
        ]);

        $user->update([
            'payment_status' => 'rejected',
            'payment_proof' => null,
            'payment_proof_uploaded_at' => null,
        ]);

        // Send WhatsApp notification to user
        $this->whatsappService->notifyUserRejection($user, $request->rejection_reason);

        return back()->with('success', 'Pembayaran berhasil ditolak dan notifikasi WhatsApp telah dikirim.');
    }
}