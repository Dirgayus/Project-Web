<?php
// Test koneksi database untuk Sky University
require_once 'config/database.php';

echo "<!DOCTYPE html>
<html lang='id'>
<head>
    <meta charset='UTF-8'>
    <title>Test Koneksi Database - Sky University</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 2rem auto; padding: 2rem; }
        .header { text-align: center; margin-bottom: 2rem; }
        .logo { font-size: 2rem; color: #4f46e5; margin-bottom: 0.5rem; }
        .success { background: #d4edda; color: #155724; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; }
        .error { background: #f8d7da; color: #721c24; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; }
        .info { background: #d1ecf1; color: #0c5460; padding: 1rem; border-radius: 0.5rem; margin: 1rem 0; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        th, td { border: 1px solid #ddd; padding: 0.5rem; text-align: left; }
        th { background: #f8f9fa; }
        .btn { background: #4f46e5; color: white; padding: 1rem 2rem; text-decoration: none; border-radius: 0.5rem; display: inline-block; margin: 0.5rem; }
        .btn:hover { background: #3b82f6; }
    </style>
</head>
<body>
    <div class='header'>
        <div class='logo'>🎓 SKY UNIVERSITY</div>
        <h1>🔍 Test Koneksi Database KRS System</h1>
    </div>";

$db = new Database();
$conn = $db->getConnection();

if ($conn) {
    echo "<div class='success'>✅ <strong>Koneksi Database BERHASIL!</strong></div>";
    
    try {
        // Test query mahasiswa
        $query = "SELECT COUNT(*) as total FROM mahasiswa";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        echo "<div class='info'>📊 <strong>Data Mahasiswa:</strong> {$result['total']} records</div>";
        
        // Tampilkan beberapa data mahasiswa
        $query = "SELECT nim, nama, jenis_kelamin, email FROM mahasiswa LIMIT 5";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if ($students) {
            echo "<h3>📋 Sample Data Mahasiswa:</h3>";
            echo "<table>";
            echo "<tr><th>NIM</th><th>Nama</th><th>Gender</th><th>Email</th></tr>";
            foreach ($students as $student) {
                echo "<tr>";
                echo "<td>{$student['nim']}</td>";
                echo "<td>{$student['nama']}</td>";
                echo "<td>" . ($student['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan') . "</td>";
                echo "<td>{$student['email']}</td>";
                echo "</tr>";
            }
            echo "</table>";
        }
        
        // Test tabel lain jika ada
        $tables = ['matakuliah', 'dosen', 'kelas', 'krs', 'users'];
        echo "<h3>📊 Status Tabel Database:</h3>";
        echo "<table>";
        echo "<tr><th>Nama Tabel</th><th>Status</th><th>Jumlah Data</th></tr>";
        
        foreach ($tables as $table) {
            try {
                $query = "SELECT COUNT(*) as total FROM $table";
                $stmt = $conn->prepare($query);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                echo "<tr><td>$table</td><td style='color: green;'>✅ Ada</td><td>{$result['total']}</td></tr>";
            } catch(PDOException $e) {
                echo "<tr><td>$table</td><td style='color: red;'>❌ Tidak Ada</td><td>-</td></tr>";
            }
        }
        echo "</table>";
        
    } catch(PDOException $e) {
        echo "<div class='error'>❌ <strong>Error Query:</strong> " . $e->getMessage() . "</div>";
    }
    
} else {
    echo "<div class='error'>❌ <strong>Koneksi Database GAGAL!</strong></div>";
    echo "<div class='info'>ℹ️ Sistem akan menggunakan <strong>Mode Demo</strong> dengan data sampel.</div>";
}

echo "
    <h3>🔧 Langkah Selanjutnya:</h3>
    <ol>
        <li>Pastikan database 'krs_system' sudah dibuat</li>
        <li>Import semua tabel yang diperlukan (mahasiswa, matakuliah, dosen, dll.)</li>
        <li>Sesuaikan konfigurasi di <code>config/database.php</code></li>
        <li>Test login dengan akun demo</li>
    </ol>
    
    <div style='text-align: center; margin-top: 2rem;'>
        <a href='login.php' class='btn'>🔐 Test Login System</a>
        <a href='index.php' class='btn'>🏠 Dashboard</a>
    </div>
    
    <div class='info' style='margin-top: 2rem;'>
        <strong>📍 URL Akses Sky University:</strong><br>
        • Login: <code>http://localhost/Sky University/login.php</code><br>
        • Dashboard: <code>http://localhost/Sky University/index.php</code><br>
        • Test DB: <code>http://localhost/Sky University/database-connection-test.php</code>
    </div>
</body>
</html>";
?>
