# Pohon Keluarga - Platform Silsilah Keluarga Digital

Platform digital terpercaya untuk menyimpan, mengelola, dan berbagi silsilah keluarga. Lestarikan warisan leluhur untuk generasi mendatang dengan teknologi modern.

## Fitur Utama

### 🌳 Pohon Keluarga Visual
- Visualisasi pohon keluarga yang interaktif dan mudah dipahami
- Tampilan yang menarik dengan foto dan informasi lengkap anggota keluarga
- Sistem hierarki yang jelas (ayah, ibu, anak)

### 🔐 Sistem Keamanan
- Autentikasi user dengan role admin dan user
- Sistem persetujuan admin untuk aktivasi akun
- Data keluarga tersimpan dengan aman

### 💬 Notifikasi WhatsApp
- Integrasi dengan API Quods.id untuk notifikasi WhatsApp
- Notifikasi otomatis untuk pendaftaran, pembayaran, dan persetujuan akun
- Reset password melalui WhatsApp

### 💰 Sistem Pembayaran
- Biaya pendaftaran dengan kode unik otomatis
- Admin dapat mengatur biaya pendaftaran
- Konfirmasi pembayaran oleh admin

### 👥 Manajemen Keluarga
- Setiap user hanya dapat memiliki 1 keluarga
- Deteksi otomatis anggota keluarga duplikat berdasarkan nama dan tanggal lahir
- Sistem pembatasan: anak yang sudah punya anak tidak bisa menambah anak lagi
- Upload foto anggota keluarga
- Deskripsi lengkap untuk setiap anggota keluarga

### 🎨 SEO Optimized Landing Page
- Meta title, description, keywords yang optimal
- Open Graph dan Twitter Card meta tags
- Struktur HTML yang SEO-friendly
- Design responsif dengan Tailwind CSS dan font Poppins

## Teknologi yang Digunakan

- **Backend**: Laravel 11
- **Frontend**: Blade Templates, Tailwind CSS, Alpine.js
- **Database**: MySQL/SQLite
- **Font**: Poppins (Google Fonts)
- **WhatsApp API**: Quods.id
- **CSS Framework**: Tailwind CSS

## Instalasi

### Persyaratan Sistem
- PHP 8.2 atau lebih tinggi
- Composer
- Node.js dan NPM
- MySQL atau SQLite

### Langkah Instalasi

1. **Clone Repository**
   ```bash
   git clone <repository-url>
   cd pohon-keluarga
   ```

2. **Install Dependencies**
   ```bash
   composer install
   npm install
   ```

3. **Environment Configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Setup**
   - Buat database MySQL atau gunakan SQLite
   - Update konfigurasi database di `.env`
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=pohon_keluarga
   DB_USERNAME=root
   DB_PASSWORD=
   ```

5. **WhatsApp API Configuration**
   - Daftar di [Quods.id](https://quods.id) untuk mendapatkan API key
   - Update konfigurasi di `.env`
   ```env
   WHATSAPP_API_URL=https://api.quods.id
   WHATSAPP_API_KEY=your_api_key_here
   ```

6. **Run Migrations dan Seeder**
   ```bash
   php artisan migrate
   php artisan db:seed --class=AdminSeeder
   ```

7. **Build Assets**
   ```bash
   npm run build
   ```

8. **Storage Link**
   ```bash
   php artisan storage:link
   ```

9. **Start Development Server**
   ```bash
   php artisan serve
   ```

## Akun Default

Setelah menjalankan seeder, akun admin default akan tersedia:

- **Email**: admin@pohonkeluarga.com
- **Password**: admin123
- **Role**: Admin

## Struktur Database

### Users
- Menyimpan data user dengan role (admin/user)
- Status persetujuan dan pembayaran
- Informasi kontak (email, phone)

### Families
- Data keluarga yang dimiliki user
- Nama keluarga dan deskripsi

### Family Members
- Anggota keluarga dengan relasi (father/mother/child)
- Data lengkap: nama, gender, tanggal lahir, foto, deskripsi
- Sistem hierarki dengan parent_id

### Payment Settings
- Pengaturan biaya pendaftaran yang dapat diubah admin

## Fitur Admin

### Dashboard Admin
- Statistik user, keluarga, dan pembayaran
- Notifikasi untuk user yang perlu persetujuan

### Kelola User
- Daftar semua user dengan status
- Approve/reject pendaftaran user
- Konfirmasi pembayaran

### Pengaturan Pembayaran
- Atur biaya pendaftaran
- Sistem kode unik otomatis (3 digit random)

## Fitur User

### Dashboard User
- Overview keluarga dan statistik
- Quick actions untuk mengelola keluarga

### Manajemen Keluarga
- Buat keluarga baru (hanya 1 per user)
- Tambah anggota keluarga
- Upload foto anggota keluarga

### Pohon Keluarga
- Visualisasi pohon keluarga yang interaktif
- Informasi lengkap setiap anggota
- Indikator status (hidup/meninggal, sudah punya anak)

## Aturan Bisnis

1. **Satu Keluarga per User**: Setiap user hanya dapat memiliki satu keluarga
2. **Deteksi Duplikat**: Sistem mendeteksi anggota keluarga dengan nama dan tanggal lahir yang sama
3. **Pembatasan Anak**: Anggota yang sudah memiliki anak tidak dapat menambah anak lagi
4. **Sistem Persetujuan**: User harus disetujui admin setelah pembayaran untuk dapat menggunakan sistem
5. **Kode Unik Pembayaran**: Setiap pendaftaran mendapat kode unik 3 digit untuk memudahkan verifikasi

## API WhatsApp (Quods.id)

Sistem menggunakan API Quods.id untuk mengirim notifikasi WhatsApp:

- **Pendaftaran**: Informasi pembayaran dengan kode unik
- **Reset Password**: Password sementara
- **Persetujuan Admin**: Konfirmasi aktivasi akun
- **Konfirmasi Pembayaran**: Notifikasi pembayaran berhasil

## SEO Features

Landing page telah dioptimasi untuk SEO dengan:

- Meta title dan description yang relevan
- Meta keywords untuk pencarian
- Open Graph tags untuk social media sharing
- Twitter Card meta tags
- Struktur HTML semantik
- Schema markup ready

## Kontribusi

1. Fork repository
2. Buat feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## Lisensi

Project ini menggunakan lisensi MIT. Lihat file `LICENSE` untuk detail lengkap.

## Support

Untuk pertanyaan atau dukungan, silakan hubungi:
- Email: info@pohonkeluarga.com
- WhatsApp: +62 812-3456-7890

## Roadmap

- [ ] Export pohon keluarga ke PDF
- [ ] Sistem backup otomatis
- [ ] Multi-language support
- [ ] Mobile app (React Native)
- [ ] Advanced family tree visualization
- [ ] Integration dengan social media
- [ ] Sistem notifikasi email
- [ ] Advanced search dan filter