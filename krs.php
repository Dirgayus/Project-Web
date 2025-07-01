<?php
require_once 'includes/functions.php';

$pageTitle = 'KRS Management';
$krsSystem = new KRSSystem();
$krsEntries = $krsSystem->getKRSEntries();

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title">KRS Management</h1>
        <div class="flex items-center justify-between mb-4">
            <span class="badge bg-gray-100 text-gray-800"><?php echo count($krsEntries); ?> Total Registrasi</span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <?php foreach ($krsEntries as $krs): ?>
        <div class="sky-card">
            <div class="sky-card-header">
                <div class="flex items-start justify-between">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-clipboard-list text-indigo-600"></i>
                        <div>
                            <h3 class="sky-card-title"><?php echo htmlspecialchars($krs['nama_matakuliah']); ?></h3>
                            <p class="sky-card-description">
                                <?php echo htmlspecialchars($krs['kode_matakuliah']); ?> - Kelas <?php echo htmlspecialchars($krs['nama_kelas']); ?>
                            </p>
                        </div>
                    </div>
                    <span class="badge <?php echo getStatusColor($krs['status_krs']); ?>">
                        <?php echo htmlspecialchars($krs['status_krs']); ?>
                    </span>
                </div>
            </div>
            
            <div class="space-y-4">
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-user text-gray-400"></i>
                    <span><?php echo htmlspecialchars($krs['nama_mahasiswa']); ?></span>
                    <span class="badge bg-gray-100 text-gray-800 ml-auto">
                        <?php echo htmlspecialchars($krs['nim']); ?>
                    </span>
                </div>
                
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-user-check text-gray-400"></i>
                    <span><?php echo htmlspecialchars($krs['nama_dosen']); ?></span>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2 text-sm">
                        <i class="fas fa-hashtag text-gray-400"></i>
                        <span><?php echo $krs['sks']; ?> SKS</span>
                    </div>
                    <?php if (!empty($krs['nilai_huruf'])): ?>
                    <span class="badge <?php echo getGradeColor($krs['nilai_huruf']); ?>">
                        Grade: <?php echo htmlspecialchars($krs['nilai_huruf']); ?>
                    </span>
                    <?php endif; ?>
                </div>
                
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-calendar text-gray-400"></i>
                    <span class="text-gray-600">
                        <?php echo htmlspecialchars($krs['tahun_akademik']); ?> - <?php echo htmlspecialchars($krs['semester_akademik']); ?>
                    </span>
                </div>
                
                <?php if (!empty($krs['nilai_angka'])): ?>
                <div class="flex items-center space-x-2 text-sm">
                    <i class="fas fa-award text-gray-400"></i>
                    <span class="text-gray-600">Score: <?php echo $krs['nilai_angka']; ?>/4.00</span>
                </div>
                <?php endif; ?>
                
                <div class="text-xs text-gray-600">
                    Registered: <?php echo formatDate($krs['tanggal_ambil']); ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
