<?php
// PANDUAN INSTALASI LENGKAP

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <title>Panduan Instalasi - Wano Saga University KRS</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1000px; margin: 0 auto; padding: 2rem; }
        .step { background: #f8f9fa; padding: 1.5rem; margin: 1rem 0; border-radius: 0.5rem; border-left: 4px solid #4f46e5; }
        .code { background: #1f2937; color: #f9fafb; padding: 1rem; border-radius: 0.5rem; font-family: monospace; overflow-x: auto; }
        .warning { background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 0.5rem; }
        .success { background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 1rem; border-radius: 0.5rem; }
    </style>
</head>
<body>
    <h1>🚀 Panduan Instalasi Sky University KRS System</h1>
    
    <div class='step'>
        <h3>📋 Langkah 1: Persiapan Environment</h3>
        <p>Pastikan sistem Anda memiliki:</p>
        <ul>
            <li>PHP 7.4+ atau PHP 8.x</li>
            <li>Web Server (Apache/Nginx) atau XAMPP/WAMP/LAMP</li>
            <li>MySQL 5.7+ (opsional - sistem bisa jalan tanpa database)</li>
            <li>Browser modern (Chrome, Firefox, Safari, Edge)</li>
        </ul>
    </div>

    <div class='step'>
        <h3>📁 Langkah 2: Setup Project</h3>
        <p>Copy semua file project ke folder web server:</p>
        <div class='code'>
# Untuk XAMPP Windows:
C:\\xampp\\htdocs\\krs-system\\

# Untuk XAMPP Linux/Mac:
/opt/lampp/htdocs/krs-system/

# Untuk server Ubuntu:
/var/www/html/krs-system/
        </div>
    </div>

    <div class='step'>
        <h3>🔧 Langkah 3: Konfigurasi (Opsional)</h3>
        <p>Jika ingin menggunakan database MySQL, edit file <code>config/database.php</code>:</p>
        <div class='code'>
\$host = 'localhost';        // Host database
\$dbname = 'krs_system';     // Nama database
\$username = 'root';         // Username MySQL
\$password = '';             // Password MySQL (kosong untuk XAMPP default)
        </div>
        <p>Kemudian import file <code>database/krs_system.sql</code> ke MySQL.</p>
    </div>

    <div class='step'>
        <h3>🌐 Langkah 4: Menjalankan Sistem</h3>
        <p>Buka browser dan akses:</p>
        <div class='code'>
http://localhost/krs-system/
        </div>
        <p>Sistem akan otomatis redirect ke halaman login.</p>
    </div>

    <div class='step'>
        <h3>🔐 Langkah 5: Login Pertama Kali</h3>
        <p>Gunakan salah satu akun demo berikut:</p>
        <div class='code'>
ADMINISTRATOR:
Username: admin
Password: admin123

DOSEN:
Username: lecturer
Password: lecturer123

MAHASISWA:
Username: student
Password: student123
        </div>
    </div>

    <div class='success'>
        <h4>✅ Sistem Berhasil Dijalankan!</h4>
        <p>Setelah login, Anda akan diarahkan ke dashboard sesuai dengan role pengguna.</p>
    </div>

    <div class='warning'>
        <h4>⚠️ Troubleshooting</h4>
        <p><strong>Jika mengalami masalah:</strong></p>
        <ul>
            <li>Pastikan web server sudah running</li>
            <li>Cek permission folder (755 untuk folder, 644 untuk file)</li>
            <li>Pastikan PHP session aktif</li>
            <li>Clear browser cache jika ada masalah redirect</li>
            <li>Cek error log di browser console (F12)</li>
        </ul>
    </div>

    <div style='text-align: center; margin-top: 2rem;'>
        <a href='demo-accounts.php' style='background: #4f46e5; color: white; padding: 1rem 2rem; text-decoration: none; border-radius: 0.5rem; margin: 0.5rem;'>
            👥 Lihat Demo Accounts
        </a>
        <a href='login.php' style='background: #059669; color: white; padding: 1rem 2rem; text-decoration: none; border-radius: 0.5rem; margin: 0.5rem;'>
            🔐 Mulai Login
        </a>
    </div>
</body>
</html>";
?>
