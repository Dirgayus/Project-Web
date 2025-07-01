<?php
require_once __DIR__ . '/../config/auth.php';

// Check if user is logged in
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$currentUser = $auth->getCurrentUser();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($pageTitle) ? $pageTitle . ' - ' : ''; ?>Wano Saga University - KRS Management System</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/forms.css" rel="stylesheet">
</head>
<body>
    <div class="relative min-h-screen">
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-btn" class="lg:hidden fixed top-4 left-4 z-50 bg-primary text-white p-2 rounded-md">
            <i class="fas fa-bars"></i>
        </button>

        <!-- Sidebar -->
        <div id="sidebar" class="sky-sidebar">
            <div class="sky-sidebar-header">
                <a href="index.php" class="sky-sidebar-brand">
                    <i class="fas fa-graduation-cap text-2xl"></i>
                    <div>   
                        <div class="font-bold text-lg">WANO SAGA</div>
                        <div class="text-sm font-medium">UNIVERSITY</div>
                    </div>
                </a>
            </div>

            <nav class="sky-sidebar-nav">
                <div class="sky-sidebar-section">
                    <a href="index.php" class="sky-sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : ''; ?>">
                        <i class="fas fa-home sky-sidebar-icon"></i>
                        <span>Beranda</span>
                    </a>
                </div>

                <div class="sky-sidebar-section">
                    <div class="sky-sidebar-section-title">AKADEMIK</div>
                    <a href="courses.php" class="sky-sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'courses.php' ? 'active' : ''; ?>">
                        <i class="fas fa-book sky-sidebar-icon"></i>
                        <span>Mata Kuliah</span>
                    </a>
                    <a href="lecturers.php" class="sky-sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'lecturers.php' ? 'active' : ''; ?>">
                        <i class="fas fa-user-check sky-sidebar-icon"></i>
                        <span>Dosen</span>
                    </a>
                    <a href="krs.php" class="sky-sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'krs.php' ? 'active' : ''; ?>">
                        <i class="fas fa-clipboard-list sky-sidebar-icon"></i>
                        <span>Kartu Rencana Studi</span>
                    </a>
                </div>

                <div class="sky-sidebar-section">
                    <div class="sky-sidebar-section-title">INFORMASI</div>
                    <a href="students.php" class="sky-sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'students.php' ? 'active' : ''; ?>">
                        <i class="fas fa-users sky-sidebar-icon"></i>
                        <span>Mahasiswa</span>
                    </a>
                    <a href="classes.php" class="sky-sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'classes.php' ? 'active' : ''; ?>">
                        <i class="fas fa-calendar sky-sidebar-icon"></i>
                        <span>Kelas</span>
                    </a>
                </div>

                <?php if ($auth->hasAnyRole(['admin', 'lecturer'])): ?>
                <div class="sky-sidebar-section">
                    <div class="sky-sidebar-section-title">KELOLA DATA</div>
                    <?php if ($auth->hasAnyRole(['admin', 'lecturer'])): ?>
                    <a href="manage_students.php" class="sky-sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_students.php' ? 'active' : ''; ?>">
                        <i class="fas fa-user-graduate sky-sidebar-icon"></i>
                        <span>Kelola Mahasiswa</span>
                    </a>
                    <a href="manage_courses.php" class="sky-sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_courses.php' ? 'active' : ''; ?>">
                        <i class="fas fa-book-open sky-sidebar-icon"></i>
                        <span>Kelola Mata Kuliah</span>
                    </a>
                    <?php endif; ?>
                    <?php if ($auth->hasRole('admin')): ?>
                    <a href="manage_lecturers.php" class="sky-sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_lecturers.php' ? 'active' : ''; ?>">
                        <i class="fas fa-chalkboard-teacher sky-sidebar-icon"></i>
                        <span>Kelola Dosen</span>
                    </a>
                    <a href="manage_users.php" class="sky-sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'manage_users.php' ? 'active' : ''; ?>">
                        <i class="fas fa-users-cog sky-sidebar-icon"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- User Section -->
                <div class="sky-sidebar-section" style="margin-top: auto; padding-top: 2rem;">
                    <div class="sky-sidebar-section-title">AKUN</div>
                    <a href="profile.php" class="sky-sidebar-item <?php echo basename($_SERVER['PHP_SELF']) == 'profile.php' ? 'active' : ''; ?>">
                        <i class="fas fa-user sky-sidebar-icon"></i>
                        <span>Profil</span>
                    </a>
                    <a href="logout.php" class="sky-sidebar-item" onclick="return confirm('Yakin ingin logout?')">
                        <i class="fas fa-sign-out-alt sky-sidebar-icon"></i>
                        <span>Logout</span>
                    </a>
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="sky-main-content">
            <!-- Header -->
            <header class="sky-header">
                <div class="sky-search-container">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Cari..." class="sky-search-input">
                    </div>
                </div>
                <div class="sky-user-profile">
                    <div class="flex items-center space-x-3">
                        <div class="text-right">
                            <div class="font-medium text-sm"><?php echo htmlspecialchars($currentUser['full_name']); ?></div>
                            <div class="text-xs text-gray-500 capitalize"><?php echo htmlspecialchars($currentUser['role']); ?></div>
                        </div>
                        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                            <?php echo strtoupper(substr($currentUser['full_name'], 0, 1)); ?>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Content Area -->
            <div class="sky-content-area">
