<?php
require_once 'config/auth.php';
require_once 'includes/functions.php';

$pageTitle = 'Profil';
$currentUser = $auth->getCurrentUser();
$krsSystem = new KRSSystem();

// Get user-specific data based on role
$userStats = [];
if ($currentUser['role'] === 'student') {
    // Get student-specific KRS data
    $krsEntries = $krsSystem->getKRSEntries();
    $userKRS = array_filter($krsEntries, function($krs) use ($currentUser) {
        return stripos($krs['nama_mahasiswa'], $currentUser['full_name']) !== false;
    });
    $userStats = [
        'total_krs' => count($userKRS),
        'active_krs' => count(array_filter($userKRS, function($krs) { return $krs['status_krs'] === 'Aktif'; })),
        'completed_krs' => count(array_filter($userKRS, function($krs) { return $krs['status_krs'] === 'Selesai'; }))
    ];
} elseif ($currentUser['role'] === 'lecturer') {
    // Get lecturer-specific data
    $classes = $krsSystem->getClasses();
    $userClasses = array_filter($classes, function($class) use ($currentUser) {
        return stripos($class['nama_dosen'], $currentUser['full_name']) !== false;
    });
    $userStats = [
        'total_classes' => count($userClasses),
        'total_students' => array_sum(array_column($userClasses, 'kapasitas'))
    ];
} else {
    // Admin stats
    $userStats = $krsSystem->getDashboardStats();
}

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title">Profil Pengguna</h1>
        <p class="sky-page-subtitle">Informasi akun dan aktivitas Anda</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Information -->
        <div class="lg:col-span-1">
            <div class="sky-card">
                <div class="text-center">
                    <div class="w-24 h-24 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-3xl font-bold mx-auto mb-4">
                        <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
                    </div>
                    <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($currentUser['full_name']); ?></h3>
                    <p class="text-gray-600 mb-4 capitalize"><?php echo htmlspecialchars($currentUser['role']); ?></p>
                    
                    <div class="space-y-3 text-left">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-user text-gray-400 w-4"></i>
                            <span class="text-sm"><?php echo htmlspecialchars($currentUser['username']); ?></span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-envelope text-gray-400 w-4"></i>
                            <span class="text-sm"><?php echo htmlspecialchars($currentUser['email']); ?></span>
                        </div>
                        <?php if ($currentUser['last_login']): ?>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-clock text-gray-400 w-4"></i>
                            <span class="text-sm">Login terakhir: <?php echo date('d/m/Y H:i', strtotime($currentUser['last_login'])); ?></span>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="sky-card mt-6">
                <h4 class="font-semibold mb-4">Aksi Cepat</h4>
                <div class="space-y-2">
                    <?php if ($currentUser['role'] === 'student'): ?>
                        <a href="krs.php" class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 transition-colors">
                            <i class="fas fa-clipboard-list text-blue-600"></i>
                            <span class="text-sm">Lihat KRS Saya</span>
                        </a>
                        <a href="courses.php" class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 transition-colors">
                            <i class="fas fa-book text-green-600"></i>
                            <span class="text-sm">Jelajahi Mata Kuliah</span>
                        </a>
                    <?php elseif ($currentUser['role'] === 'lecturer'): ?>
                        <a href="classes.php" class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 transition-colors">
                            <i class="fas fa-calendar text-blue-600"></i>
                            <span class="text-sm">Lihat Kelas Saya</span>
                        </a>
                        <a href="students.php" class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 transition-colors">
                            <i class="fas fa-users text-green-600"></i>
                            <span class="text-sm">Daftar Mahasiswa</span>
                        </a>
                    <?php else: ?>
                        <a href="index.php" class="flex items-center space-x-2 p-2 rounded hover:bg-gray-50 transition-colors">
                            <i class="fas fa-chart-bar text-blue-600"></i>
                            <span class="text-sm">Dashboard Admin</span>
                        </a>
                    <?php endif; ?>
                    <a href="logout.php" class="flex items-center space-x-2 p-2 rounded hover:bg-red-50 transition-colors text-red-600" onclick="return confirm('Yakin ingin logout?')">
                        <i class="fas fa-sign-out-alt"></i>
                        <span class="text-sm">Logout</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics and Activity -->
        <div class="lg:col-span-2">
            <!-- Role-specific Statistics -->
            <div class="sky-card mb-6">
                <h4 class="font-semibold mb-4">
                    <?php if ($currentUser['role'] === 'student'): ?>
                        Statistik Akademik
                    <?php elseif ($currentUser['role'] === 'lecturer'): ?>
                        Statistik Mengajar
                    <?php else: ?>
                        Statistik Sistem
                    <?php endif; ?>
                </h4>
                
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <?php if ($currentUser['role'] === 'student'): ?>
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600"><?php echo $userStats['total_krs']; ?></div>
                            <div class="text-sm text-gray-600">Total KRS</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600"><?php echo $userStats['active_krs']; ?></div>
                            <div class="text-sm text-gray-600">KRS Aktif</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600"><?php echo $userStats['completed_krs']; ?></div>
                            <div class="text-sm text-gray-600">KRS Selesai</div>
                        </div>
                    <?php elseif ($currentUser['role'] === 'lecturer'): ?>
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600"><?php echo $userStats['total_classes']; ?></div>
                            <div class="text-sm text-gray-600">Kelas Diampu</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600"><?php echo $userStats['total_students']; ?></div>
                            <div class="text-sm text-gray-600">Kapasitas Mahasiswa</div>
                        </div>
                    <?php else: ?>
                        <div class="text-center p-4 bg-blue-50 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600"><?php echo $userStats['totalStudents']; ?></div>
                            <div class="text-sm text-gray-600">Total Mahasiswa</div>
                        </div>
                        <div class="text-center p-4 bg-green-50 rounded-lg">
                            <div class="text-2xl font-bold text-green-600"><?php echo $userStats['totalCourses']; ?></div>
                            <div class="text-sm text-gray-600">Mata Kuliah</div>
                        </div>
                        <div class="text-center p-4 bg-purple-50 rounded-lg">
                            <div class="text-2xl font-bold text-purple-600"><?php echo $userStats['activeRegistrations']; ?></div>
                            <div class="text-sm text-gray-600">KRS Aktif</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Activity -->
            <div class="sky-card">
                <h4 class="font-semibold mb-4">Aktivitas Terbaru</h4>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-sign-in-alt text-blue-600 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <div class="text-sm font-medium">Login ke sistem</div>
                            <div class="text-xs text-gray-500">
                                <?php echo $currentUser['last_login'] ? date('d/m/Y H:i', strtotime($currentUser['last_login'])) : 'Sesi saat ini'; ?>
                            </div>
                        </div>
                    </div>
                    
                    <?php if ($currentUser['role'] === 'student' && !empty($userKRS)): ?>
                        <?php foreach (array_slice($userKRS, 0, 3) as $krs): ?>
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 rounded-lg">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-green-600 text-sm"></i>
                            </div>
                            <div class="flex-1">
                                <div class="text-sm font-medium">Mengambil <?php echo htmlspecialchars($krs['nama_matakuliah']); ?></div>
                                <div class="text-xs text-gray-500"><?php echo date('d/m/Y', strtotime($krs['tanggal_ambil'])); ?></div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="text-center py-8 text-gray-500">
                            <i class="fas fa-history text-2xl mb-2"></i>
                            <div class="text-sm">Belum ada aktivitas terbaru</div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
