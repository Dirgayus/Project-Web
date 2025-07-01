<?php
require_once 'includes/functions.php';

$pageTitle = 'Kelas';
$krsSystem = new KRSSystem();
$classes = $krsSystem->getClasses();

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title">Kelas</h1>
        <div class="flex items-center justify-between mb-4">
            <span class="badge bg-gray-100 text-gray-800"><?php echo count($classes); ?> Total Kelas</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($classes as $class): ?>
        <div class="sky-card">
            <div class="sky-card-header">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-calendar text-orange-600"></i>
                        <div>
                            <h3 class="sky-card-title">
                                <?php echo htmlspecialchars($class['nama_matakuliah']); ?> - <?php echo htmlspecialchars($class['nama_kelas']); ?>
                            </h3>
                            <p class="sky-card-description"><?php echo htmlspecialchars($class['kode_matakuliah']); ?></p>
                        </div>
                    </div>
                    <span class="badge <?php echo $class['semester_akademik'] === 'Ganjil' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800'; ?>">
                        <?php echo htmlspecialchars($class['semester_akademik']); ?>
                    </span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-user-check text-gray-400"></i>
                    <span><?php echo htmlspecialchars($class['nama_dosen']); ?></span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-sm">
                        <i class="fas fa-users text-gray-400"></i>
                        <span>Kapasitas: <?php echo $class['kapasitas']; ?></span>
                    </div>
                    <span class="badge bg-gray-100 text-gray-800"><?php echo $class['sks']; ?> SKS</span>
                </div>
                
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-hashtag text-gray-400"></i>
                    <span class="text-gray-600"><?php echo htmlspecialchars($class['tahun_akademik']); ?></span>
                </div>
                
                <?php if (!empty($class['tanggal_mulai']) && !empty($class['tanggal_selesai'])): ?>
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-clock text-gray-400"></i>
                    <span class="text-gray-600 text-xs">
                        <?php echo formatDate($class['tanggal_mulai']); ?> - <?php echo formatDate($class['tanggal_selesai']); ?>
                    </span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
