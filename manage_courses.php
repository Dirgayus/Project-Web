<?php
require_once 'config/auth.php';
require_once 'includes/functions.php';

// Require admin or lecturer role
$auth->requireAnyRole(['admin', 'lecturer']);

$pageTitle = 'Kelola Mata Kuliah';
$krsSystem = new KRSSystem();
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $data = [
            'kode_matakuliah' => sanitizeInput($_POST['kode_matakuliah']),
            'nama_matakuliah' => sanitizeInput($_POST['nama_matakuliah']),
            'sks' => (int)$_POST['sks'],
            'semester' => (int)$_POST['semester'],
            'deskripsi' => sanitizeInput($_POST['deskripsi'])
        ];
        
        $errors = validateRequired(['kode_matakuliah', 'nama_matakuliah', 'sks', 'semester'], $data);
        
        if (empty($errors)) {
            $result = $krsSystem->createCourse($data);
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
            'kode_matakuliah' => sanitizeInput($_POST['kode_matakuliah']),
            'nama_matakuliah' => sanitizeInput($_POST['nama_matakuliah']),
            'sks' => (int)$_POST['sks'],
            'semester' => (int)$_POST['semester'],
            'deskripsi' => sanitizeInput($_POST['deskripsi'])
        ];
        
        $errors = validateRequired(['kode_matakuliah', 'nama_matakuliah', 'sks', 'semester'], $data);
        
        if (empty($errors)) {
            $result = $krsSystem->updateCourse($id, $data);
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
        $result = $krsSystem->deleteCourse($id);
        if ($result['success']) {
            $message = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}

$courses = $krsSystem->getCourses();
$editCourse = null;

// Get course for editing
if (isset($_GET['edit'])) {
    $editCourse = $krsSystem->getCourseById($_GET['edit']);
}

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title">Kelola Mata Kuliah</h1>
        <p class="sky-page-subtitle">Tambah, edit, dan hapus mata kuliah</p>
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
                    <?php echo $editCourse ? 'Edit Mata Kuliah' : 'Tambah Mata Kuliah'; ?>
                </h3>
                
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="<?php echo $editCourse ? 'update' : 'create'; ?>">
                    <?php if ($editCourse): ?>
                        <input type="hidden" name="id" value="<?php echo $editCourse['id_matakuliah']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label class="form-label">Kode Mata Kuliah *</label>
                        <input type="text" name="kode_matakuliah" class="form-input" 
                               value="<?php echo $editCourse ? htmlspecialchars($editCourse['kode_matakuliah']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nama Mata Kuliah *</label>
                        <input type="text" name="nama_matakuliah" class="form-input" 
                               value="<?php echo $editCourse ? htmlspecialchars($editCourse['nama_matakuliah']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">SKS *</label>
                        <select name="sks" class="form-select" required>
                            <option value="">Pilih SKS</option>
                            <?php for ($i = 1; $i <= 6; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($editCourse && $editCourse['sks'] == $i) ? 'selected' : ''; ?>>
                                    <?php echo $i; ?> SKS
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Semester *</label>
                        <select name="semester" class="form-select" required>
                            <option value="">Pilih Semester</option>
                            <?php for ($i = 1; $i <= 8; $i++): ?>
                                <option value="<?php echo $i; ?>" <?php echo ($editCourse && $editCourse['semester'] == $i) ? 'selected' : ''; ?>>
                                    Semester <?php echo $i; ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="deskripsi" class="form-input" rows="4"><?php echo $editCourse ? htmlspecialchars($editCourse['deskripsi']) : ''; ?></textarea>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-1">
                            <i class="fas fa-save mr-2"></i>
                            <?php echo $editCourse ? 'Update' : 'Simpan'; ?>
                        </button>
                        <?php if ($editCourse): ?>
                            <a href="manage_courses.php" class="btn-secondary">
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
                    <h3 class="text-lg font-semibold">Daftar Mata Kuliah</h3>
                    <span class="badge bg-gray-100 text-gray-800"><?php echo count($courses); ?> Total</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="text-left p-3">Kode</th>
                                <th class="text-left p-3">Nama Mata Kuliah</th>
                                <th class="text-center p-3">SKS</th>
                                <th class="text-center p-3">Semester</th>
                                <th class="text-center p-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-medium"><?php echo htmlspecialchars($course['kode_matakuliah']); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($course['nama_matakuliah']); ?></td>
                                <td class="p-3 text-center">
                                    <span class="badge bg-blue-100 text-blue-800"><?php echo $course['sks']; ?></span>
                                </td>
                                <td class="p-3 text-center">
                                    <span class="badge <?php echo getSemesterColor($course['semester']); ?>">
                                        <?php echo $course['semester']; ?>
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="?edit=<?php echo $course['id_matakuliah']; ?>" 
                                           class="btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($krsSystem->isDatabaseConnected()): ?>
                                        <form method="POST" class="inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus mata kuliah ini?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $course['id_matakuliah']; ?>">
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
