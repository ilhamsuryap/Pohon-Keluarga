<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PaymentSetting;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function showRegistrationForm()
    {
        $registrationFee = PaymentSetting::getActiveFee();
        return view('auth.register', compact('registrationFee'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $registrationFee = PaymentSetting::getActiveFee();

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'is_approved' => false,
            'payment_status' => 'pending',
        ]);

        // Generate unique payment code
        $user->payment_amount = $registrationFee;
        $paymentAmount = $user->generatePaymentCode();

        // Send WhatsApp notification
        $message = "Halo {$user->name},\n\n";
        $message .= "Terima kasih telah mendaftar di Pohon Keluarga.\n";
        $message .= "Silakan lakukan pembayaran sebesar Rp " . number_format($paymentAmount, 0, ',', '.') . "\n";
        $message .= "Kode unik pembayaran: {$user->payment_code}\n\n";
        $message .= "Setelah pembayaran, akun Anda akan diverifikasi oleh admin.\n";
        $message .= "Terima kasih!";

        $this->whatsappService->sendMessage($user->phone, $message);

        return redirect()->route('login')->with('success', 
            'Pendaftaran berhasil! Silakan cek WhatsApp untuk informasi pembayaran. Akun akan diaktifkan setelah pembayaran dan persetujuan admin.');
    }
}