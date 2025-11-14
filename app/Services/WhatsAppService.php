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

    private function formatPhoneNumber(string $phoneNumber): string
    {
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (!str_starts_with($phoneNumber, '62')) {
            if (str_starts_with($phoneNumber, '0')) {
                $phoneNumber = '62' . substr($phoneNumber, 1);
            } else {
                $phoneNumber = '62' . $phoneNumber;
            }
        }
        return $phoneNumber;
    }

    /**
     * Send WhatsApp message via Quods direct-send (Bearer token)
     */
    public function sendDirect(string $phoneNumber, string $message, ?string $fileUrl = null): bool
    {
        try {
            $phoneNumber = $this->formatPhoneNumber($phoneNumber);

            $payload = [
                'device_key' => $this->deviceKey,
                'phone' => $phoneNumber,
                'message' => $message,
            ];
            if (!empty($fileUrl)) {
                $payload['file_url'] = $fileUrl;
            }

            Log::info('Sending WhatsApp direct-send', [
                'api_url' => $this->apiUrl . '/direct-send',
                'payload' => $payload,
            ]);

            $response = Http::withToken($this->apiKey)
                ->acceptJson()
                ->post($this->apiUrl . '/direct-send', $payload);

            Log::info('WhatsApp direct-send response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return $response->ok() && data_get($response->json(), 'status') === 'success';
        } catch (\Exception $e) {
            Log::error('WhatsApp direct-send error', [
                'phone' => $phoneNumber ?? null,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Backward-compatible wrapper to direct-send without file
     */
    public function sendMessage($phoneNumber, $message)
    {
        return $this->sendDirect((string)$phoneNumber, (string)$message);
    }

    /**
     * Send notification to admin about new payment proof (hardcoded phone)
     */
    public function notifyAdminNewPayment($user)
    {
        // Hardcoded admin phone number as requested
        $adminPhones = ['6285941051469'];

        $fileUrl = $user->getPaymentProofUrl();

        $message = "\xF0\x9F\x94\x94 *NOTIFIKASI PEMBAYARAN BARU*\n\n";
        $message .= "Ada bukti pembayaran baru yang perlu diverifikasi:\n\n";
        $message .= "\xF0\x9F\x91\xA4 *Nama:* {$user->name}\n";
        $message .= "\xF0\x9F\x93\xA7 *Email:* {$user->email}\n";
        if (!empty($user->phone)) {
            $message .= "\xF0\x9F\x93\xB1 *Phone:* {$user->phone}\n";
        }
        if (!empty($user->payment_amount)) {
            $message .= "\xF0\x9F\x92\xB0 *Jumlah:* Rp " . number_format($user->payment_amount, 0, ',', '.') . "\n";
        }
        if (!empty($user->payment_code)) {
            $message .= "\xF0\x9F\x94\xA2 *Kode Unik:* {$user->payment_code}\n";
        }
        if ($user->payment_proof_uploaded_at) {
            $message .= "\xF0\x9F\x93\x85 *Tanggal Upload:* " . $user->payment_proof_uploaded_at->format('d/m/Y H:i') . "\n";
        }
        if ($fileUrl) {
            $message .= "\nLihat bukti: {$fileUrl}\n\n";
        } else {
            $message .= "\nBukti tersimpan di sistem.\n\n";
        }
        $message .= "Silakan login ke admin panel untuk memverifikasi pembayaran.";

        $success = true;
        foreach ($adminPhones as $adminPhone) {
            $result = $this->sendDirect($adminPhone, $message, $fileUrl);
            if (!$result) {
                $success = false;
                Log::error('Failed to send WhatsApp notification to hardcoded admin', [
                    'admin_phone' => $adminPhone,
                ]);
            }
        }

        return $success;
    }

    /**
     * Send notification to user that payment proof is being verified
     */
    public function notifyUserVerificationPending($user)
    {
        $message = "\xF0\x9F\x93\xA6 *BUKTI PEMBAYARAN DITERIMA*\n\n";
        $message .= "Halo {$user->name},\n\n";
        $message .= "\xE2\x9C\x85 Bukti pembayaran Anda telah berhasil kami terima.\n\n";
        $message .= "\xF0\x9F\x92\xB0 *Jumlah Pembayaran:* Rp " . number_format($user->payment_amount, 0, ',', '.') . "\n";
        if (!empty($user->payment_code)) {
            $message .= "\xF0\x9F\x94\xA2 *Kode Unik:* {$user->payment_code}\n";
        }
        $message .= "\n";
        $message .= "\xF0\x9F\x93\x9D Akun Anda saat ini sedang dalam proses *verifikasi oleh admin*.\n\n";
        $message .= "\xE2\x8F\xB3 Mohon menunggu, kami akan mengirimkan notifikasi melalui WhatsApp setelah akun Anda disetujui.\n\n";
        $message .= "Terima kasih atas kesabaran Anda! \xF0\x9F\x99\x8F";

        return $this->sendDirect($user->phone, $message);
    }

    /**
     * Send approval notification to user
     */
    public function notifyUserApproval($user)
    {
        // Determine greeting based on name (simple approach)
        $sapaan = "Bapak/Ibu";
        
        $message = "\xF0\x9F\x8E\x89 *SELAMAT! AKUN ANDA TELAH DISETUJUI*\n\n";
        $message .= "Terima kasih {$sapaan} {$user->name} sudah melakukan pendaftaran akun,\n\n";
        $message .= "\xF0\x9F\x93\x9D Berikut ini data pendaftaran anda :\n\n";
        $message .= "\xF0\x9F\x91\xA4 *Nama :* {$user->name}\n";
        $message .= "\xF0\x9F\x93\xB1 *Phone :* " . ($user->phone ?? '-') . "\n\n";
        $message .= "\xF0\x9F\x8C\x90 *Login:*\n";
        $message .= "https://famtreee.co.id/login\n\n";
        $message .= "\xF0\x9F\x94\x92 *Username:* {$user->email}\n\n";
        $message .= "\xF0\x9F\x8C\x9F Selamat menebar manfaat untuk kebaikan \xF0\x9F\x99\x8F";

        return $this->sendDirect($user->phone, $message);
    }

    /**
     * Send rejection notification to user
     */
    public function notifyUserRejection($user, $reason = null)
    {
        $message = "\xE2\x9D\x8C *PEMBAYARAN DITOLAK*\n\n";
        $message .= "Halo {$user->name},\n\n";
        $message .= "Maaf, bukti pembayaran Anda tidak dapat diverifikasi.\n\n";
        
        if ($reason) {
            $message .= "\xF0\x9F\x93\x9D *Alasan:* {$reason}\n\n";
        }
        
        $message .= "Silakan upload ulang bukti pembayaran yang valid atau hubungi admin untuk bantuan.\n\n";
        $message .= "\xF0\x9F\x92\xB0 *Jumlah yang harus dibayar:* Rp " . number_format($user->payment_amount, 0, ',', '.') . "\n";
        $message .= "\xF0\x9F\x94\xA2 *Kode Unik:* {$user->payment_code}";

        return $this->sendDirect($user->phone, $message);
    }
}