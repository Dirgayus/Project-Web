# 🎓 Wano University - Sistem KRS Management

Sistem Kartu Rencana Studi (KRS) untuk Sky University dengan fitur lengkap manajemen akademik.

## 🚀 Cara Menjalankan Sistem

### 1. Persiapan Environment
- **XAMPP/WAMP/LARAGON** (PHP 7.4+ dan MySQL)
- **Web Browser** (Chrome, Firefox, Edge)
- **Text Editor** (VS Code, Sublime, dll.)

### 2. Setup Project
1. **Extract/Copy** folder sistem ke direktori web server:
   \`\`\`
   C:\xampp\htdocs\Sky University\
   \`\`\`

2. **Start Services:**
   - Apache Web Server
   - MySQL Database

### 3. Konfigurasi Database

#### A. Buat Database
\`\`\`sql
CREATE DATABASE krs_system;
USE krs_system;
\`\`\`

#### B. Import Tabel (Opsional - jika belum ada)
Jalankan script SQL di `scripts/01-create-tables.sql` dan `scripts/02-seed-data.sql`

#### C. Sesuaikan Koneksi
Edit file `config/database.php`:
\`\`\`php
private $host = 'localhost';
private $db_name = 'krs_system';
private $username = 'root';
private $password = '';  // Sesuaikan dengan password MySQL Anda
\`\`\`

### 4. Akses Sistem

#### 🔗 URL Utama:
- **Login:** `http://localhost/Sky University/login.php`
- **Dashboard:** `http://localhost/Sky University/index.php`
- **Test Database:** `http://localhost/Sky University/database-connection-test.php`

#### 🔐 Akun Demo:
| Role | Username | Password | Akses |
|------|----------|----------|-------|
| **Administrator** | `admin` | `admin123` | Full Access |
| **Dosen** | `lecturer` | `lecturer123` | Kelola Mahasiswa & Mata Kuliah |
| **Mahasiswa** | `student` | `student123` | View Only |

### 5. Fitur Sistem

#### 👨‍💼 **Administrator:**
- ✅ Kelola semua data (Mahasiswa, Dosen, Mata Kuliah)
- ✅ Kelola pengguna sistem
- ✅ Lihat statistik lengkap
- ✅ Akses semua menu

#### 👨‍🏫 **Dosen:**
- ✅ Kelola data mahasiswa
- ✅ Kelola mata kuliah
- ✅ Lihat kelas yang diampu
- ✅ Akses data akademik

#### 👨‍🎓 **Mahasiswa:**
- ✅ Lihat profil pribadi
- ✅ Lihat mata kuliah tersedia
- ✅ Lihat KRS pribadi
- ✅ Lihat jadwal kelas

### 6. Struktur Database

#### Tabel Utama:
- `mahasiswa` - Data mahasiswa
- `matakuliah` - Data mata kuliah
- `dosen` - Data dosen
- `kelas` - Data kelas
- `krs` - Kartu Rencana Studi
- `users` - Akun pengguna sistem

### 7. Troubleshooting

#### ❌ **Database Tidak Terhubung:**
1. Pastikan MySQL service berjalan
2. Cek konfigurasi di `config/database.php`
3. Pastikan database `krs_system` sudah dibuat
4. Test koneksi di: `http://localhost/Sky University/database-connection-test.php`

#### ❌ **Error 404:**
1. Pastikan folder berada di `htdocs/Sky University/`
2. Pastikan Apache service berjalan
3. Cek URL: `http://localhost/Sky University/`

#### ❌ **Login Gagal:**
1. Gunakan akun demo yang tersedia
2. Pastikan tabel `users` sudah ada
3. Cek di halaman registrasi untuk membuat akun baru

### 8. Mode Demo

Jika database tidak tersedia, sistem akan otomatis menggunakan **Mode Demo** dengan:
- ✅ Data sampel mahasiswa, dosen, mata kuliah
- ✅ Semua fitur tetap berfungsi
- ✅ Data tidak tersimpan permanen

### 9. Keamanan

- ✅ Password di-hash dengan `password_hash()`
- ✅ Session management yang aman
- ✅ Role-based access control
- ✅ Input sanitization
- ✅ SQL injection protection

### 10. Support

Untuk bantuan teknis:
- 📧 Email: support@skyuniversity.ac.id
- 📱 WhatsApp: +62-xxx-xxxx-xxxx
- 🌐 Website: https://skyuniversity.ac.id

---

**© 2025 Sky University. Sistem KRS Management v1.0.0**
