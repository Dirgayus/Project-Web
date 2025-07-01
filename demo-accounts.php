<?php
// FILE DEMO - INFORMASI AKUN UNTUK TESTING

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Demo Accounts - Sky University</title>
    <link href='assets/css/style.css' rel='stylesheet'>
    <style>
        .demo-container {
            max-width: 800px;
            margin: 2rem auto;
            padding: 2rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .account-card {
            border: 2px solid #e5e7eb;
            border-radius: 0.5rem;
            padding: 1.5rem;
            margin: 1rem 0;
            transition: all 0.3s;
        }
        .account-card:hover {
            border-color: #4f46e5;
            transform: translateY(-2px);
        }
        .role-admin { border-left: 4px solid #dc2626; }
        .role-lecturer { border-left: 4px solid #059669; }
        .role-student { border-left: 4px solid #2563eb; }
        .credentials {
            background: #f3f4f6;
            padding: 1rem;
            border-radius: 0.5rem;
            font-family: monospace;
            margin: 1rem 0;
        }
        .quick-login {
            background: #4f46e5;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            border-radius: 0.25rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-top: 0.5rem;
        }
        .quick-login:hover {
            background: #3730a3;
        }
    </style>
</head>
<body>
    <div class='demo-container'>
        <h1 style='text-align: center; color: #1f2937; margin-bottom: 2rem;'>
            🎓 Demo Accounts - Sky University KRS
        </h1>
        
        <div class='account-card role-admin'>
            <h3 style='color: #dc2626; margin: 0 0 1rem 0;'>👨‍💼 ADMINISTRATOR</h3>
            <p><strong>Nama:</strong> Administrator</p>
            <p><strong>Email:</strong> admin@skyuniversity.ac.id</p>
            <div class='credentials'>
                <strong>Username:</strong> admin<br>
                <strong>Password:</strong> admin123
            </div>
            <p><strong>Akses:</strong></p>
            <ul>
                <li>✅ Kelola semua data mahasiswa</li>
                <li>✅ Kelola semua mata kuliah</li>
                <li>✅ Kelola semua data dosen</li>
                <li>✅ Akses penuh ke semua fitur</li>
                <li>✅ Dashboard lengkap dengan statistik</li>
            </ul>
            <a href='login.php' class='quick-login'>Login sebagai Admin</a>
        </div>

        <div class='account-card role-lecturer'>
            <h3 style='color: #059669; margin: 0 0 1rem 0;'>👨‍🏫 DOSEN</h3>
            <p><strong>Nama:</strong> Dr. Ahmad Wijaya</p>
            <p><strong>Email:</strong> ahmad.wijaya@university.ac.id</p>
            <div class='credentials'>
                <strong>Username:</strong> lecturer<br>
                <strong>Password:</strong> lecturer123
            </div>
            <p><strong>Akses:</strong></p>
            <ul>
                <li>✅ Kelola data mahasiswa</li>
                <li>✅ Kelola mata kuliah</li>
                <li>❌ Tidak bisa kelola dosen lain</li>
                <li>✅ Lihat semua data akademik</li>
                <li>✅ Dashboard dengan statistik terbatas</li>
            </ul>
            <a href='login.php' class='quick-login'>Login sebagai Dosen</a>
        </div>

        <div class='account-card role-student'>
            <h3 style='color: #2563eb; margin: 0 0 1rem 0;'>👨‍🎓 MAHASISWA</h3>
            <p><strong>Nama:</strong> Andi Pratama</p>
            <p><strong>Email:</strong> andi.pratama@student.ac.id</p>
            <div class='credentials'>
                <strong>Username:</strong> student<br>
                <strong>Password:</strong> student123
            </div>
            <p><strong>Akses:</strong></p>
            <ul>
                <li>✅ Lihat data mata kuliah</li>
                <li>✅ Lihat data dosen</li>
                <li>✅ Lihat jadwal kelas</li>
                <li>✅ Lihat KRS pribadi</li>
                <li>❌ Tidak bisa edit data apapun</li>
            </ul>
            <a href='login.php' class='quick-login'>Login sebagai Mahasiswa</a>
        </div>

        <div style='background: #fef3c7; border: 1px solid #f59e0b; padding: 1rem; border-radius: 0.5rem; margin-top: 2rem;'>
            <h4 style='color: #92400e; margin: 0 0 0.5rem 0;'>⚡ Tips Cepat:</h4>
            <p style='margin: 0; color: #92400e;'>
                Gunakan keyboard shortcut saat di halaman login:<br>
                <strong>Ctrl + 1</strong> = Auto-fill Admin<br>
                <strong>Ctrl + 2</strong> = Auto-fill Dosen<br>
                <strong>Ctrl + 3</strong> = Auto-fill Mahasiswa
            </p>
        </div>

        <div style='text-align: center; margin-top: 2rem;'>
            <a href='index.php' style='background: #6b7280; color: white; padding: 0.75rem 1.5rem; text-decoration: none; border-radius: 0.5rem;'>
                🏠 Kembali ke Beranda
            </a>
        </div>
    </div>
</body>
</html>";
?>
