<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    private $apiUrl;
    private $apiKey;
    private $deviceKey;

    public function __construct()
    {
        $this->apiUrl = 'https://api.quods.id/api';
        $this->apiKey = 'TMeTyUimv75LmlHRlCutowWU2z86QW';
        $this->deviceKey = 'UMSZSzMyen40UdD';
    }

    /**
     * Send WhatsApp message
     */
    public function sendMessage($phoneNumber, $message)
    {
        try {
            // Format phone number (remove + and spaces)
            $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
            
            // Add country code if not present (assuming Indonesia +62)
            if (!str_starts_with($phoneNumber, '62')) {
                if (str_starts_with($phoneNumber, '0')) {
                    $phoneNumber = '62' . substr($phoneNumber, 1);
                } else {
                    $phoneNumber = '62' . $phoneNumber;
                }
            }

            // Try different payload formats for Quods API
            $payload1 = [
                'api_key' => $this->apiKey,
                'device_key' => $this->deviceKey,
                'destination' => $phoneNumber,
                'message' => $message
            ];

            $payload2 = [
                'api_key' => $this->apiKey,
                'device_key' => $this->deviceKey,
                'phone' => $phoneNumber,
                'message' => $message
            ];

            $payload3 = [
                'api_key' => $this->apiKey,
                'device_key' => $this->deviceKey,
                'number' => $phoneNumber,
                'text' => $message
            ];

            Log::info('Sending WhatsApp message', [
                'phone' => $phoneNumber,
                'api_url' => $this->apiUrl,
                'payload1' => $payload1,
                'payload2' => $payload2,
                'payload3' => $payload3
            ]);

            $response = null;
            $endpoints = ['/send-message', '/send_message', '/sendmessage', '/message/send'];
            $payloads = [$payload1, $payload2, $payload3];

            foreach ($endpoints as $endpoint) {
                foreach ($payloads as $index => $payload) {
                    Log::info("Trying endpoint: {$endpoint} with payload" . ($index + 1));
                    
                    // Try form data
                    $response = Http::asForm()->post($this->apiUrl . $endpoint, $payload);
                    
                    if ($response->successful()) {
                        Log::info("Success with endpoint: {$endpoint} and payload" . ($index + 1));
                        break 2;
                    }
                    
                    // Try JSON
                    $response = Http::withHeaders([
                        'Content-Type' => 'application/json',
                    ])->post($this->apiUrl . $endpoint, $payload);
                    
                    if ($response->successful()) {
                        Log::info("Success with JSON endpoint: {$endpoint} and payload" . ($index + 1));
                        break 2;
                    }
                }
            }

            Log::info('WhatsApp API Response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'headers' => $response->headers()
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                if (isset($responseData['status']) && $responseData['status'] === 'success') {
                    Log::info('WhatsApp message sent successfully', [
                        'phone' => $phoneNumber,
                        'response' => $responseData
                    ]);
                    return true;
                } else {
                    Log::error('WhatsApp API returned error', [
                        'phone' => $phoneNumber,
                        'response' => $responseData
                    ]);
                    return false;
                }
            } else {
                Log::error('Failed to send WhatsApp message', [
                    'phone' => $phoneNumber,
                    'status' => $response->status(),
                    'response' => $response->body()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp service error', [
                'phone' => $phoneNumber,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Send notification to admin about new payment proof
     */
    public function notifyAdminNewPayment($user)
    {
        // Get all admin users from database
        $admins = \App\Models\User::where('role', 'admin')->whereNotNull('phone')->get();
        
        if ($admins->isEmpty()) {
            Log::warning('No admin users with phone numbers found for WhatsApp notifications');
            return false;
        }

        $message = "🔔 *NOTIFIKASI PEMBAYARAN BARU*\n\n";
        $message .= "Ada bukti pembayaran baru yang perlu diverifikasi:\n\n";
        $message .= "👤 *Nama:* {$user->name}\n";
        $message .= "📧 *Email:* {$user->email}\n";
        $message .= "📱 *Phone:* {$user->phone}\n";
        $message .= "💰 *Jumlah:* Rp " . number_format($user->payment_amount, 0, ',', '.') . "\n";
        $message .= "🔢 *Kode Unik:* {$user->payment_code}\n";
        $message .= "📅 *Tanggal Upload:* " . $user->payment_proof_uploaded_at->format('d/m/Y H:i') . "\n\n";
        $message .= "Silakan login ke admin panel untuk memverifikasi pembayaran.";

        $success = true;
        foreach ($admins as $admin) {
            $result = $this->sendMessage($admin->phone, $message);
            if (!$result) {
                $success = false;
                Log::error('Failed to send WhatsApp notification to admin', [
                    'admin_id' => $admin->id,
                    'admin_phone' => $admin->phone
                ]);
            }
        }

        return $success;
    }

    /**
     * Send approval notification to user
     */
    public function notifyUserApproval($user)
    {
        $message = "🎉 *SELAMAT! AKUN ANDA TELAH DISETUJUI*\n\n";
        $message .= "Halo {$user->name},\n\n";
        $message .= "Pembayaran Anda telah diverifikasi dan akun Anda telah disetujui!\n\n";
        $message .= "✅ *Status:* Akun Aktif\n";
        $message .= "💰 *Jumlah Dibayar:* Rp " . number_format($user->payment_amount, 0, ',', '.') . "\n\n";
        $message .= "Anda sekarang dapat mengakses semua fitur aplikasi Pohon Keluarga.\n\n";
        $message .= "Terima kasih telah bergabung dengan kami! 🙏";

        return $this->sendMessage($user->phone, $message);
    }

    /**
     * Send rejection notification to user
     */
    public function notifyUserRejection($user, $reason = null)
    {
        $message = "❌ *PEMBAYARAN DITOLAK*\n\n";
        $message .= "Halo {$user->name},\n\n";
        $message .= "Maaf, bukti pembayaran Anda tidak dapat diverifikasi.\n\n";
        
        if ($reason) {
            $message .= "📝 *Alasan:* {$reason}\n\n";
        }
        
        $message .= "Silakan upload ulang bukti pembayaran yang valid atau hubungi admin untuk bantuan.\n\n";
        $message .= "💰 *Jumlah yang harus dibayar:* Rp " . number_format($user->payment_amount, 0, ',', '.') . "\n";
        $message .= "🔢 *Kode Unik:* {$user->payment_code}";

        return $this->sendMessage($user->phone, $message);
    }
}