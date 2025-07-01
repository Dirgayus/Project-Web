<?php
require_once 'includes/functions.php';

$pageTitle = 'Mata Kuliah';
$krsSystem = new KRSSystem();
$courses = $krsSystem->getCourses();

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title">Mata Kuliah</h1>
        <div class="flex items-center justify-between mb-4">
            <span class="badge bg-gray-100 text-gray-800"><?php echo count($courses); ?> Total Mata Kuliah</span>
        </div>
    </div>

    <?php if (empty($courses)): ?>
        <div class="sky-card text-center py-12">
            <i class="fas fa-book text-4xl text-gray-300 mb-4"></i>
            <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Mata Kuliah</h3>
            <p class="text-gray-500 mb-4">Sistem belum memiliki data mata kuliah. Silakan hubungi administrator untuk menambahkan data.</p>
            <?php if ($auth->hasRole('admin')): ?>
                <button class="btn-primary" onclick="alert('Fitur tambah mata kuliah akan segera tersedia')">
                    <i class="fas fa-plus mr-2"></i>
                    Tambah Mata Kuliah
                </button>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($courses as $course): ?>
            <div class="sky-card">
                <div class="sky-card-header">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-book text-green-600"></i>
                            <div>
                                <h3 class="sky-card-title"><?php echo htmlspecialchars($course['nama_matakuliah']); ?></h3>
                                <p class="sky-card-description"><?php echo htmlspecialchars($course['kode_matakuliah']); ?></p>
                            </div>
                        </div>
                        <span class="badge <?php echo getSemesterColor($course['semester']); ?>">
                            Semester <?php echo $course['semester']; ?>
                        </span>
                    </div>
                </div>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-2 text-sm">
                            <i class="fas fa-hashtag text-gray-400"></i>
                            <span>SKS: <?php echo $course['sks']; ?></span>
                        </div>
                        <span class="badge bg-gray-100 text-gray-800"><?php echo $course['sks']; ?> Credits</span>
                    </div>
                    
                    <?php if (!empty($course['deskripsi'])): ?>
                    <div class="flex items-start space-x-2 text-sm">
                        <i class="fas fa-file-text text-gray-400 mt-1"></i>
                        <span class="text-gray-600 text-xs leading-relaxed"><?php echo htmlspecialchars($course['deskripsi']); ?></span>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
