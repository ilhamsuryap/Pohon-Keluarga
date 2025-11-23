# Panduan Mengatur Permission untuk Deployment

## Masalah: CSS/JS Tidak Terload (ERR_CONNECTION_REFUSED)

Error ini terjadi karena:
1. Folder `public/build` tidak ada atau tidak memiliki permission yang benar
2. Laravel mencoba menghubungkan ke Vite dev server (localhost:5173) yang tidak tersedia di production

## Solusi Lengkap

### 1. Pastikan Build Assets Sudah Dibuat

Jalankan di server (melalui SSH atau Terminal aaPanel):

```bash
cd /path/to/your/project
npm run build
```

Ini akan membuat folder `public/build` dengan file-file:
- `manifest.json`
- `assets/app-[hash].js`
- `assets/app-[hash].css`

### 2. Mengatur Permission Folder `public/build`

#### Via Terminal/SSH (Recommended)

```bash
# Masuk ke direktori project
cd /path/to/your/project

# Set permission untuk folder public/build
chmod -R 755 public/build

# Set ownership (ganti 'www' dengan user web server Anda, biasanya 'www' atau 'www-data')
chown -R www:www public/build

# Atau jika menggunakan user lain (cek dengan: ps aux | grep php-fpm)
chown -R www-data:www-data public/build
```

#### Via aaPanel File Manager

1. Buka **File Manager** di aaPanel
2. Navigasi ke folder `public/build`
3. Klik kanan pada folder `build` → **Properties** atau **Permission**
4. Set permission menjadi: **755** (rwxr-xr-x)
5. Centang **Apply to subdirectories and files**
6. Klik **OK**

### 3. Mengatur Permission untuk Seluruh Project (Laravel Standard)

```bash
# Set permission untuk storage dan cache
chmod -R 775 storage bootstrap/cache
chown -R www:www storage bootstrap/cache

# Set permission untuk public/build
chmod -R 755 public/build
chown -R www:www public/build

# Set permission untuk public (folder utama)
chmod -R 755 public
chown -R www:www public
```

### 4. Verifikasi Permission

Cek apakah permission sudah benar:

```bash
ls -la public/build
```

Output yang benar:
```
drwxr-xr-x  www www  build/
-rw-r--r--  www www  manifest.json
drwxr-xr-x  www www  assets/
```

### 5. Pastikan Environment Production

Edit file `.env` dan pastikan:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com
```

### 6. Clear Cache Laravel

Setelah mengatur permission, clear cache:

```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear
```

### 7. Restart Web Server (jika perlu)

```bash
# Untuk Nginx
systemctl restart nginx

# Untuk PHP-FPM
systemctl restart php-fpm

# Atau restart melalui aaPanel
```

## Troubleshooting

### Jika masih error ERR_CONNECTION_REFUSED:

1. **Cek apakah folder `public/build` ada:**
   ```bash
   ls -la public/build
   ```

2. **Jika tidak ada, build ulang:**
   ```bash
   npm run build
   ```

3. **Cek apakah file `manifest.json` ada:**
   ```bash
   ls -la public/build/manifest.json
   ```

4. **Cek permission file manifest:**
   ```bash
   chmod 644 public/build/manifest.json
   chmod 644 public/build/assets/*
   ```

5. **Cek error log Laravel:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

### Jika Permission Masih Bermasalah:

```bash
# Set permission lebih permisif (sementara untuk testing)
chmod -R 777 public/build

# Jika berhasil, kembalikan ke 755 untuk keamanan
chmod -R 755 public/build
```

## Checklist Deployment

- [ ] `npm run build` sudah dijalankan
- [ ] Folder `public/build` ada dan berisi file
- [ ] Permission `public/build` = 755
- [ ] Ownership `public/build` = www:www (atau sesuai web server)
- [ ] Permission `storage` = 775
- [ ] Permission `bootstrap/cache` = 775
- [ ] `.env` sudah di-set ke `APP_ENV=production`
- [ ] Cache Laravel sudah di-clear
- [ ] Web server sudah di-restart (jika perlu)

## Catatan Penting

1. **Jangan set permission 777** di production kecuali untuk testing sementara
2. **Gunakan 755** untuk folder dan **644** untuk file
3. **Pastikan ownership** sesuai dengan user web server (biasanya `www` atau `www-data`)
4. **Folder `public/build` harus ada** sebelum aplikasi diakses, jika tidak Laravel akan mencoba connect ke dev server

## Command Lengkap (Copy-Paste)

```bash
# Masuk ke direktori project
cd /path/to/your/project

# Build assets
npm run build

# Set permission dan ownership
chmod -R 755 public/build
chown -R www:www public/build
chmod -R 775 storage bootstrap/cache
chown -R www:www storage bootstrap/cache

# Clear cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Verifikasi
ls -la public/build
```

Ganti `/path/to/your/project` dengan path sebenarnya ke project Anda di server.

