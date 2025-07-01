<?php
require_once 'includes/functions.php';

$pageTitle = 'Mahasiswa';
$krsSystem = new KRSSystem();
$students = $krsSystem->getStudents();

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title">Mahasiswa</h1>
        <div class="flex items-center justify-between mb-4">
            <span class="badge bg-gray-100 text-gray-800"><?php echo count($students); ?> Total Mahasiswa</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($students as $student): ?>
        <div class="sky-card">
            <div class="sky-card-header">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-user text-blue-600"></i>
                    <div>
                        <h3 class="sky-card-title"><?php echo htmlspecialchars($student['nama']); ?></h3>
                        <p class="sky-card-description"><?php echo htmlspecialchars($student['nim']); ?></p>
                    </div>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-calendar text-gray-400"></i>
                    <span><?php echo formatDate($student['tanggal_lahir']); ?></span>
                    <span class="badge <?php echo $student['jenis_kelamin'] === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'; ?> ml-auto">
                        <?php echo $student['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?>
                    </span>
                </div>
                
                <?php if (!empty($student['email'])): ?>
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-envelope text-gray-400"></i>
                    <span class="truncate"><?php echo htmlspecialchars($student['email']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($student['nomor_telepon'])): ?>
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-phone text-gray-400"></i>
                    <span><?php echo htmlspecialchars($student['nomor_telepon']); ?></span>
                </div>
                <?php endif; ?>
                
                <?php if (!empty($student['alamat'])): ?>
                <div class="flex items-start space-x-2 text-sm">
                    <i class="fas fa-map-marker-alt text-gray-400 mt-1"></i>
                    <span class="text-gray-600"><?php echo htmlspecialchars($student['alamat']); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
