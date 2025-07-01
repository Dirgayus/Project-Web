<?php
// PANDUAN LOGIN SISTEM KRS SKY UNIVERSITY

/*
=== AKUN DEMO YANG TERSEDIA ===

1. ADMINISTRATOR
   Username: admin
   Password: admin123
   Akses: Semua fitur (CRUD semua data)

2. DOSEN
   Username: lecturer  
   Password: lecturer123
   Akses: Kelola mahasiswa dan mata kuliah

3. MAHASISWA
   Username: student
   Password: student123
   Akses: Lihat data saja (read-only)

=== LANGKAH-LANGKAH LOGIN ===

1. Buka http://localhost/krs-system/
2. Sistem akan redirect ke login.php
3. Masukkan username dan password
4. Klik tombol "Masuk"
5. Sistem akan redirect ke beranda sesuai role

=== FITUR BERDASARKAN ROLE ===

ADMINISTRATOR:
- Dashboard lengkap
- Kelola semua data (mahasiswa, dosen, mata kuliah)
- Akses semua menu
- Bisa tambah/edit/hapus semua data

DOSEN:
- Dashboard terbatas
- Kelola mahasiswa dan mata kuliah
- Tidak bisa kelola data dosen lain
- Akses menu akademik

MAHASISWA:
- Dashboard read-only
- Lihat data mata kuliah, dosen, kelas
- Lihat KRS sendiri
- Tidak bisa edit data apapun

=== TROUBLESHOOTING ===

Jika login gagal:
1. Pastikan username/password benar
2. Cek apakah session PHP aktif
3. Cek permission folder project
4. Lihat error di browser console

Jika redirect loop:
1. Clear browser cache
2. Cek konfigurasi session PHP
3. Pastikan file config/auth.php readable

*/
?>
