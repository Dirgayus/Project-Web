<?php
require_once 'includes/functions.php';

$pageTitle = 'Dosen';
$krsSystem = new KRSSystem();
$lecturers = $krsSystem->getLecturers();

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title">Dosen</h1>
        <div class="flex items-center justify-between mb-4">
            <span class="badge bg-gray-100 text-gray-800"><?php echo count($lecturers); ?> Total Dosen</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($lecturers as $lecturer): ?>
        <div class="sky-card">
            <div class="sky-card-header">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-user-check text-purple-600"></i>
                    <div>
                        <h3 class="sky-card-title"><?php echo htmlspecialchars($lecturer['nama_dosen']); ?></h3>
                        <p class="sky-card-description"><?php echo htmlspecialchars($lecturer['nidn']); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <?php if (!empty($lecturer['gelar'])): ?>
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-award text-gray-400"></i>
                    <span><?php echo htmlspecialchars($lecturer['gelar']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($lecturer['email'])): ?>
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-envelope text-gray-400"></i>
                    <span class="truncate"><?php echo htmlspecialchars($lecturer['email']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($lecturer['nomor_telepon'])): ?>
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-phone text-gray-400"></i>
                    <span><?php echo htmlspecialchars($lecturer['nomor_telepon']); ?></span>
                </div>
                <?php endif; ?>
                
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-hashtag text-gray-400"></i>
                    <span class="text-gray-600">NIDN: <?php echo htmlspecialchars($lecturer['nidn']); ?></span>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
