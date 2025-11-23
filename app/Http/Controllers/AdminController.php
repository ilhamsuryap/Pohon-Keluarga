<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Family;
use App\Models\PaymentSetting;
use App\Models\Config;
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
        // Setujui user dan pembayaran sekaligus
        $user->update([
            'is_approved' => true,
            'payment_status' => 'approved',
            'payment_date' => now()
        ]);

        // Send WhatsApp notification to user
        $this->whatsappService->notifyUserApproval($user);

        return back()->with('success', 'User dan pembayaran berhasil disetujui. Notifikasi WhatsApp telah dikirim.');
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

    public function configSettings()
    {
        $configKeys = [
            'whatsapp_api_url',
            'whatsapp_api_key',
            'whatsapp_device_key',
            'whatsapp_admin_phones',
        ];

        $configs = Config::whereIn('key', $configKeys)->get()->keyBy('key');
        
        // Ensure all keys exist, create empty config objects if missing
        foreach ($configKeys as $key) {
            if (!isset($configs[$key])) {
                $configs[$key] = new \App\Models\Config([
                    'key' => $key,
                    'value' => $key === 'whatsapp_api_url' ? 'https://api.quods.id/api' : '',
                    'type' => 'text'
                ]);
            }
        }

        return view('admin.config-settings', compact('configs'));
    }

    public function updateConfigSettings(Request $request)
    {
        $request->validate([
            'whatsapp_api_url' => 'required|url',
            'whatsapp_api_key' => 'required|string',
            'whatsapp_device_key' => 'required|string',
            'whatsapp_admin_phones' => 'required|string',
        ]);

        Config::set('whatsapp_api_url', $request->whatsapp_api_url, 'URL API WhatsApp Quods', 'text');
        Config::set('whatsapp_api_key', $request->whatsapp_api_key, 'Bearer Token API WhatsApp Quods', 'text');
        Config::set('whatsapp_device_key', $request->whatsapp_device_key, 'Device Key API WhatsApp Quods', 'text');
        Config::set('whatsapp_admin_phones', $request->whatsapp_admin_phones, 'Nomor WhatsApp Admin (pisahkan dengan koma jika lebih dari satu)', 'text');

        return back()->with('success', 'Pengaturan konfigurasi berhasil diperbarui.');
    }

    public function profile(Request $request)
    {
        return view('admin.profile', [
            'user' => $request->user(),
        ]);
    }
}