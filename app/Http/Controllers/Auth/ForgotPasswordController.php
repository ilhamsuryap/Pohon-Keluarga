<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    protected $whatsappService;

    public function __construct(WhatsAppService $whatsappService)
    {
        $this->whatsappService = $whatsappService;
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::where('email', $request->email)->first();
        
        // Generate temporary password
        $tempPassword = Str::random(8);
        $user->password = Hash::make($tempPassword);
        $user->save();

        // Send WhatsApp notification
        $message = "Halo {$user->name},\n\n";
        $message .= "Password sementara Anda: {$tempPassword}\n";
        $message .= "Silakan login dan ubah password Anda segera.\n\n";
        $message .= "Terima kasih!";

        $this->whatsappService->sendMessage($user->phone, $message);

        return back()->with('success', 
            'Password sementara telah dikirim ke WhatsApp Anda. Silakan cek pesan dan login dengan password baru.');
    }
}