<?php
// Debug page to check system status
require_once 'config/auth.php';
require_once 'includes/functions.php';

$pageTitle = 'System Debug';
$krsSystem = new KRSSystem();

// Check authentication
if (!$auth->isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$currentUser = $auth->getCurrentUser();

// Get system information
$dbStatus = $krsSystem->isDatabaseConnected();
$courses = $krsSystem->getCourses();
$students = $krsSystem->getStudents();
$lecturers = $krsSystem->getLecturers();
$classes = $krsSystem->getClasses();
$krsEntries = $krsSystem->getKRSEntries();
$stats = $krsSystem->getDashboardStats();

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title">System Debug</h1>
        <p class="sky-page-subtitle">Informasi status sistem dan data</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- System Status -->
        <div class="sky-card">
            <h3 class="text-lg font-semibold mb-4">Status Sistem</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span>Database Connection:</span>
                    <span class="badge <?php echo $dbStatus ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                        <?php echo $dbStatus ? 'Connected' : 'Disconnected'; ?>
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Authentication:</span>
                    <span class="badge bg-green-100 text-green-800">Active</span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Current User:</span>
                    <span><?php echo htmlspecialchars($currentUser['full_name']); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span>User Role:</span>
                    <span class="badge bg-blue-100 text-blue-800 capitalize"><?php echo htmlspecialchars($currentUser['role']); ?></span>
                </div>
            </div>
        </div>

        <!-- Data Counts -->
        <div class="sky-card">
            <h3 class="text-lg font-semibold mb-4">Data Statistics</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span>Total Courses:</span>
                    <span class="font-semibold"><?php echo count($courses); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Total Students:</span>
                    <span class="font-semibold"><?php echo count($students); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Total Lecturers:</span>
                    <span class="font-semibold"><?php echo count($lecturers); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Total Classes:</span>
                    <span class="font-semibold"><?php echo count($classes); ?></span>
                </div>
                <div class="flex justify-between items-center">
                    <span>Total KRS Entries:</span>
                    <span class="font-semibold"><?php echo count($krsEntries); ?></span>
                </div>
            </div>
        </div>

        <!-- Sample Data -->
        <div class="lg:col-span-2">
            <div class="sky-card">
                <h3 class="text-lg font-semibold mb-4">Sample Course Data</h3>
                <?php if (!empty($courses)): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b">
                                    <th class="text-left p-2">Kode</th>
                                    <th class="text-left p-2">Nama Mata Kuliah</th>
                                    <th class="text-left p-2">SKS</th>
                                    <th class="text-left p-2">Semester</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($courses, 0, 5) as $course): ?>
                                <tr class="border-b">
                                    <td class="p-2"><?php echo htmlspecialchars($course['kode_matakuliah']); ?></td>
                                    <td class="p-2"><?php echo htmlspecialchars($course['nama_matakuliah']); ?></td>
                                    <td class="p-2"><?php echo $course['sks']; ?></td>
                                    <td class="p-2"><?php echo $course['semester']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">No course data available</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- PHP Info -->
        <div class="lg:col-span-2">
            <div class="sky-card">
                <h3 class="text-lg font-semibold mb-4">PHP Environment</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                    <div>
                        <strong>PHP Version:</strong><br>
                        <?php echo PHP_VERSION; ?>
                    </div>
                    <div>
                        <strong>Session Status:</strong><br>
                        <?php echo session_status() === PHP_SESSION_ACTIVE ? 'Active' : 'Inactive'; ?>
                    </div>
                    <div>
                        <strong>PDO Available:</strong><br>
                        <?php echo extension_loaded('pdo') ? 'Yes' : 'No'; ?>
                    </div>
                    <div>
                        <strong>MySQL PDO:</strong><br>
                        <?php echo extension_loaded('pdo_mysql') ? 'Yes' : 'No'; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-6 text-center">
        <a href="index.php" class="btn-primary">
            <i class="fas fa-arrow-left mr-2"></i>
            Kembali ke Dashboard
        </a>
    </div>
</div>

<style>
.btn-primary {
    background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    transition: all 0.2s ease;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
}
</style>

<?php include 'includes/footer.php'; ?>
