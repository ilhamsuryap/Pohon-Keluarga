# WhatsApp Integration Setup Guide

Sistem ini menggunakan Quods.id API untuk mengirim notifikasi WhatsApp kepada admin dan user.

## Konfigurasi

### 1. Environment Variables

Tambahkan konfigurasi berikut ke file `.env`:

```env
# WhatsApp API Configuration (Quods.id)
WHATSAPP_API_URL=https://api.quods.id
WHATSAPP_API_KEY=your_quods_api_key_here
WHATSAPP_DEVICE_KEY=your_device_key_here
```

### 2. Mendapatkan API Key dan Device Key dari Quods.id

1. Daftar di [Quods.id](https://quods.id)
2. Buat device WhatsApp baru
3. Dapatkan API Key dan Device Key
4. Masukkan ke file `.env`

### 3. Setup Admin Phone Numbers

Admin phone numbers diambil dari database, bukan dari file `.env`. Pastikan:

1. **Admin users memiliki nomor telepon:**
   - Login sebagai admin
   - Update profile dan isi nomor telepon
   - Atau update langsung di database pada tabel `users` dengan `role = 'admin'`

2. **Format nomor telepon admin:**
   - Gunakan format internasional tanpa tanda +
   - Contoh: `628123456789` untuk nomor Indonesia
   - Sistem akan otomatis menambahkan kode negara 62 jika diperlukan

3. **Multiple admin support:**
   - Sistem akan mengirim notifikasi ke semua admin yang memiliki nomor telepon
   - Jika ada admin tanpa nomor telepon, mereka tidak akan mendapat notifikasi WhatsApp

### 4. Format Nomor Telepon

- Gunakan format internasional tanpa tanda +
- Contoh: `628123456789` untuk nomor Indonesia
- Sistem akan otomatis menambahkan kode negara 62 jika diperlukan

## Fitur WhatsApp Notifications

### 1. Notifikasi untuk Admin

Admin akan mendapat notifikasi WhatsApp ketika:
- User baru mengupload bukti pembayaran

Format pesan:
```
🔔 NOTIFIKASI PEMBAYARAN BARU

Ada bukti pembayaran baru yang perlu diverifikasi:

👤 Nama: [Nama User]
📧 Email: [Email User]
📱 Phone: [Nomor HP User]
💰 Jumlah: Rp [Jumlah Pembayaran]
🔢 Kode Unik: [Kode Unik]
📅 Tanggal Upload: [Tanggal Upload]

Silakan login ke admin panel untuk memverifikasi pembayaran.
```

### 2. Notifikasi untuk User

User akan mendapat notifikasi WhatsApp ketika:
- Pembayaran disetujui admin
- Pembayaran ditolak admin

#### Format pesan persetujuan:
```
🎉 SELAMAT! AKUN ANDA TELAH DISETUJUI

Halo [Nama User],

Pembayaran Anda telah diverifikasi dan akun Anda telah disetujui!

✅ Status: Akun Aktif
💰 Jumlah Dibayar: Rp [Jumlah]

Anda sekarang dapat mengakses semua fitur aplikasi Pohon Keluarga.

Terima kasih telah bergabung dengan kami! 🙏
```

#### Format pesan penolakan:
```
❌ PEMBAYARAN DITOLAK

Halo [Nama User],

Maaf, bukti pembayaran Anda tidak dapat diverifikasi.

📝 Alasan: [Alasan jika ada]

Silakan upload ulang bukti pembayaran yang valid atau hubungi admin untuk bantuan.

💰 Jumlah yang harus dibayar: Rp [Jumlah]
🔢 Kode Unik: [Kode Unik]
```

## Testing

### Test WhatsApp Service

Gunakan command berikut untuk test pengiriman pesan:

```bash
php artisan whatsapp:test 628123456789 "Test message"
```

### Test Flow Lengkap

1. **Registrasi User Baru**
   - Daftar dengan akun baru
   - Sistem akan redirect ke halaman upload bukti pembayaran

2. **Upload Bukti Pembayaran**
   - Upload gambar bukti transfer
   - Admin akan mendapat notifikasi WhatsApp

3. **Admin Approval**
   - Login sebagai admin
   - Lihat daftar user di `/admin/users`
   - Klik "Lihat Bukti Pembayaran" untuk melihat bukti
   - Klik "Setujui Pembayaran" atau "Tolak Pembayaran"
   - User akan mendapat notifikasi WhatsApp

## Troubleshooting

### 1. Pesan WhatsApp Tidak Terkirim

- Periksa API Key dan Device Key di `.env`
- Pastikan device WhatsApp di Quods.id dalam status aktif
- Periksa log Laravel di `storage/logs/laravel.log`

### 2. Format Nomor Telepon

- Pastikan nomor menggunakan format internasional
- Hapus karakter non-numerik (spasi, tanda hubung, dll)
- Sistem akan otomatis menambahkan kode negara 62 untuk nomor Indonesia

### 3. Upload Bukti Pembayaran Gagal

- Pastikan direktori `storage/app/public/payment-proofs` dapat ditulis
- Periksa ukuran file (maksimal 5MB)
- Format file harus JPEG, PNG, atau JPG

## File Structure

```
app/
├── Services/
│   └── WhatsAppService.php          # Service untuk WhatsApp API
├── Http/Controllers/
│   ├── PaymentProofController.php   # Controller upload bukti bayar
│   └── AdminController.php          # Controller admin (updated)
└── Console/Commands/
    └── TestWhatsAppCommand.php      # Command untuk test WhatsApp

resources/views/
├── payment-proof/
│   ├── upload.blade.php             # Form upload bukti bayar
│   └── view.blade.php               # View detail bukti bayar
├── user/
│   └── pending-approval.blade.php   # Halaman pending (updated)
└── admin/
    └── users.blade.php              # Halaman admin users (updated)

database/migrations/
└── 2025_09_18_040002_add_payment_proof_to_users_table.php
```

## API Endpoints

### User Routes
- `GET /payment-proof/upload` - Form upload bukti pembayaran
- `POST /payment-proof/upload` - Submit bukti pembayaran
- `GET /payment-proof/view` - Lihat detail bukti pembayaran
- `DELETE /payment-proof` - Hapus bukti pembayaran

### Admin Routes
- `POST /admin/users/{user}/approve` - Setujui user dan kirim notifikasi
- `POST /admin/users/{user}/reject-payment` - Tolak pembayaran dan kirim notifikasi

## Security Notes

- API Key dan Device Key harus disimpan dengan aman
- Jangan commit file `.env` ke repository
- Gunakan HTTPS untuk production
- Validasi input file upload dengan ketat