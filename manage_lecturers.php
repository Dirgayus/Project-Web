<?php
require_once 'config/auth.php';
require_once 'includes/functions.php';

// Require admin role
$auth->requireRole('admin');

$pageTitle = 'Kelola Dosen';
$krsSystem = new KRSSystem();
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $data = [
            'nidn' => sanitizeInput($_POST['nidn']),
            'nama_dosen' => sanitizeInput($_POST['nama_dosen']),
            'gelar' => sanitizeInput($_POST['gelar']),
            'email' => sanitizeInput($_POST['email']),
            'nomor_telepon' => sanitizeInput($_POST['nomor_telepon'])
        ];
        
        $errors = validateRequired(['nidn', 'nama_dosen'], $data);
        if (!empty($data['email']) && !validateEmail($data['email'])) {
            $errors[] = 'Format email tidak valid';
        }
        
        if (empty($errors)) {
            $result = $krsSystem->createLecturer($data);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
            }
        } else {
            $error = implode(', ', $errors);
        }
    }
    
    if ($action === 'update') {
        $id = $_POST['id'];
        $data = [
            'nidn' => sanitizeInput($_POST['nidn']),
            'nama_dosen' => sanitizeInput($_POST['nama_dosen']),
            'gelar' => sanitizeInput($_POST['gelar']),
            'email' => sanitizeInput($_POST['email']),
            'nomor_telepon' => sanitizeInput($_POST['nomor_telepon'])
        ];
        
        $errors = validateRequired(['nidn', 'nama_dosen'], $data);
        if (!empty($data['email']) && !validateEmail($data['email'])) {
            $errors[] = 'Format email tidak valid';
        }
        
        if (empty($errors)) {
            $result = $krsSystem->updateLecturer($id, $data);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
            }
        } else {
            $error = implode(', ', $errors);
        }
    }
    
    if ($action === 'delete') {
        $id = $_POST['id'];
        $result = $krsSystem->deleteLecturer($id);
        if ($result['success']) {
            $message = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}

$lecturers = $krsSystem->getLecturers();
$editLecturer = null;

// Get lecturer for editing
if (isset($_GET['edit'])) {
    $editLecturer = $krsSystem->getLecturerById($_GET['edit']);
}

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title">Kelola Dosen</h1>
        <p class="sky-page-subtitle">Tambah, edit, dan hapus data dosen</p>
    </div>

    <?php if ($message): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle mr-2"></i>
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle mr-2"></i>
            <?php echo htmlspecialchars($error); ?>
        </div>
    <?php endif; ?>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="lg:col-span-1">
            <div class="sky-card">
                <h3 class="text-lg font-semibold mb-4">
                    <?php echo $editLecturer ? 'Edit Dosen' : 'Tambah Dosen'; ?>
                </h3>
                
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="<?php echo $editLecturer ? 'update' : 'create'; ?>">
                    <?php if ($editLecturer): ?>
                        <input type="hidden" name="id" value="<?php echo $editLecturer['id_dosen']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label class="form-label">NIDN *</label>
                        <input type="text" name="nidn" class="form-input" 
                               value="<?php echo $editLecturer ? htmlspecialchars($editLecturer['nidn']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nama Dosen *</label>
                        <input type="text" name="nama_dosen" class="form-input" 
                               value="<?php echo $editLecturer ? htmlspecialchars($editLecturer['nama_dosen']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Gelar</label>
                        <input type="text" name="gelar" class="form-input" 
                               value="<?php echo $editLecturer ? htmlspecialchars($editLecturer['gelar']) : ''; ?>" 
                               placeholder="S.Kom., M.T., Ph.D.">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" 
                               value="<?php echo $editLecturer ? htmlspecialchars($editLecturer['email']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" class="form-input" 
                               value="<?php echo $editLecturer ? htmlspecialchars($editLecturer['nomor_telepon']) : ''; ?>">
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-1">
                            <i class="fas fa-save mr-2"></i>
                            <?php echo $editLecturer ? 'Update' : 'Simpan'; ?>
                        </button>
                        <?php if ($editLecturer): ?>
                            <a href="manage_lecturers.php" class="btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="lg:col-span-2">
            <div class="sky-card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Daftar Dosen</h3>
                    <span class="badge bg-gray-100 text-gray-800"><?php echo count($lecturers); ?> Total</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="text-left p-3">NIDN</th>
                                <th class="text-left p-3">Nama Dosen</th>
                                <th class="text-left p-3">Gelar</th>
                                <th class="text-left p-3">Email</th>
                                <th class="text-center p-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($lecturers as $lecturer): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-medium"><?php echo htmlspecialchars($lecturer['nidn']); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($lecturer['nama_dosen']); ?></td>
                                <td class="p-3 text-sm text-gray-600"><?php echo htmlspecialchars($lecturer['gelar'] ?? '-'); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($lecturer['email'] ?? '-'); ?></td>
                                <td class="p-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="?edit=<?php echo $lecturer['id_dosen']; ?>" 
                                           class="btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($krsSystem->isDatabaseConnected()): ?>
                                        <form method="POST" class="inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus dosen ini?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $lecturer['id_dosen']; ?>">
                                            <button type="submit" class="btn-sm btn-danger" title="Hapus">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="assets/css/forms.css">

<?php include 'includes/footer.php'; ?>
