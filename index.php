<?php
require_once 'includes/functions.php';

$pageTitle = 'Dashboard';
$krsSystem = new KRSSystem();
$stats = $krsSystem->getDashboardStats();
$isDbConnected = $krsSystem->isDatabaseConnected();

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title"> Dasboard</h1>
        <div class="sky-tabs">
            <div class="sky-tab active">Beranda</div>
            <div class="sky-tab" onclick="window.location = '/Wano%20Saga%20University/courses.php'">Mata Kuliah</div>
            <div class="sky-tab" onclick="window.location='/Wano%20Saga%20University/lecturers.php'">Dosen</div>
            <div class="sky-tab" onclick="window.location = '/Wano%20Saga%20University/students.php'">Mahasiswa</div>
            <div class="sky-tab"onclick="window.location ='/Wano%20Saga%20University/krs.php'">KRS</div>
            <div class="sky-tab"onclick="window.location ='/Wano%20Saga%20University/classes.php'">Kelas</div>
        </div>
    </div>

    <div class="sky-page-subtitle">Selamat datang di dashboard Wano Saga University.</div>

    <?php if (!$isDbConnected): ?>
    <div class="alert alert-warning">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <div>
                <strong>Peringatan</strong><br>
                Sistem sedang berjalan dalam mode tidak terkoneksi xammp mySQL.<br/> Aktifkan kembali untuk menghubungkan kembali <br/>Beberapa fitur mungkin tidak tersedia.
            </div>
        </div>
    </div>
    <?php endif; ?>

    <div class="sky-stats-grid">
        <div class="sky-stat-card">
            <div class="sky-stat-number"><?php echo $stats['totalStudents']; ?></div>
            <div class="sky-stat-label">Total Mahasiswa</div>
        </div>
        <div class="sky-stat-card">
            <div class="sky-stat-number"><?php echo $stats['totalCourses']; ?></div>
            <div class="sky-stat-label">Mata Kuliah</div>
        </div>
        <div class="sky-stat-card">
            <div class="sky-stat-number"><?php echo $stats['totalLecturers']; ?></div>
            <div class="sky-stat-label">Dosen</div>
        </div>
        <div class="sky-stat-card">
            <div class="sky-stat-number"><?php echo $stats['activeRegistrations']; ?></div>
            <div class="sky-stat-label">KRS Aktif</div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="sky-card">
            <h2>🎓 Fitur Sistem Informasi Akademik</h2>
  <p>Sistem ini dirancang untuk mendukung kelancaran pengelolaan kegiatan akademik di lingkungan kampus. Berikut adalah fitur-fitur utama yang tersedia:</p>
  <ul>
    <li>
      <strong>📋 Manajemen Data Mahasiswa</strong><br>
      Mengelola informasi mahasiswa secara terpusat dan terintegrasi.
    </li>
    <li>
      <strong>📚 Pengelolaan Mata Kuliah & Kurikulum</strong><br>
      Menyusun dan memperbarui struktur kurikulum serta mata kuliah dengan mudah.
    </li>
    <li>
      <strong>👨‍🏫 Tracking Penugasan Dosen</strong><br>
      Memantau penugasan dosen pada setiap mata kuliah secara efisien.
    </li>
    <li>
      <strong>🗓️ Manajemen Jadwal & Kapasitas Kelas</strong><br>
      Menyusun jadwal kuliah dan mengatur kapasitas ruang kelas secara fleksibel.
    </li>
    <li>
      <strong>📝 Pemrosesan Kartu Rencana Studi (KRS)</strong><br>
      Memfasilitasi mahasiswa dalam pengisian dan validasi KRS secara daring.
    </li>
    <li>
      <strong>📆 Manajemen Tahun & Semester Akademik</strong><br>
      Mengelola periode akademik, kalender kuliah, serta jadwal penting lainnya.
    </li>
  </ul>
        </div>

        <div class="sky-card">
            <h3 class="text-lg font-semibold mb-4">Status Sistem</h3>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium">Status Database</span>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-database <?php echo $isDbConnected ? 'text-green-600' : 'text-yellow-600'; ?>"></i>
                        <span class="text-sm <?php echo $isDbConnected ? 'text-green-600' : 'text-yellow-600'; ?>">
                            <?php echo $isDbConnected ? 'Terhubung' : 'Mode Demo'; ?>
                        </span>
                    </div>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium">Sumber Data</span>
                    <span class="text-sm text-gray-600">
                        <?php echo $isDbConnected ? 'MySQL Database' : 'Data Sampel'; ?>
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium">Versi Sistem</span>
                    <span class="text-sm text-gray-600">v1.1.1.0</span>
                </div>
                <?php if ($auth->hasRole('admin')): ?>
                <div class="flex justify-between items-center">
                    <span class="text-sm font-medium">Akses Admin</span>
                    <span class="text-sm text-green-600">Aktif</span>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php if ($auth->hasRole('admin')): ?>
    
    <div class="sky-card mt-6">
        <h3 class="text-lg font-semibold mb-4">Aksi Cepat Administrator</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="manage_users.php" class="flex flex-col items-center p-4 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors text-decoration-none">
                <i class="fas fa-users-cog text-2xl text-blue-600 mb-2"></i>
                <span class="text-sm font-medium text-blue-800">Kelola Pengguna</span>
            </a>
            <a href="manage_students.php" class="flex flex-col items-center p-4 bg-green-50 rounded-lg hover:bg-green-100 transition-colors text-decoration-none">
                <i class="fas fa-user-graduate text-2xl text-green-600 mb-2"></i>
                <span class="text-sm font-medium text-green-800">Kelola Mahasiswa</span>
            </a>
            <a href="manage_courses.php" class="flex flex-col items-center p-4 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors text-decoration-none">
                <i class="fas fa-book-open text-2xl text-purple-600 mb-2"></i>
                <span class="text-sm font-medium text-purple-800">Kelola Mata Kuliah</span>
            </a>
            <a href="manage_lecturers.php" class="flex flex-col items-center p-4 bg-orange-50 rounded-lg hover:bg-orange-100 transition-colors text-decoration-none">
                <i class="fas fa-chalkboard-teacher text-2xl text-orange-600 mb-2"></i>
                <span class="text-sm font-medium text-orange-800">Kelola Dosen</span>
            </a>
        </div>
    </div>
    <?php endif; ?>

    <div class="sky-footer">Copyright © Wano Saga University 2025/2026</div>
</div>

<style>
.text-decoration-none {
    text-decoration: none;
}
</style>

<?php include 'includes/footer.php'; ?>
