<?php
require_once 'config/auth.php';
require_once 'includes/functions.php';
require_once 'includes/photo-upload.php';

// Require admin or lecturer role
$auth->requireAnyRole(['admin', 'lecturer']);

$pageTitle = 'Kelola Data Mahasiswa';
$krsSystem = new KRSSystem();
$photoUpload = new PhotoUpload();
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $data = [
            'nim' => sanitizeInput($_POST['nim']),
            'nama' => sanitizeInput($_POST['nama']),
            'tanggal_lahir' => $_POST['tanggal_lahir'],
            'jenis_kelamin' => $_POST['jenis_kelamin'],
            'alamat' => sanitizeInput($_POST['alamat']),
            'nomor_telepon' => sanitizeInput($_POST['nomor_telepon']),
            'email' => sanitizeInput($_POST['email']),
            'foto' => ''
        ];
        
        $errors = validateRequired(['nim', 'nama', 'tanggal_lahir', 'jenis_kelamin'], $data);
        if (!empty($data['email']) && !validateEmail($data['email'])) {
            $errors[] = 'Format email tidak valid';
        }
        
        // Handle photo upload
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $photoUpload->uploadPhoto($_FILES['foto']);
            if ($uploadResult['success']) {
                $data['foto'] = $uploadResult['filename'];
            } else {
                $errors[] = $uploadResult['message'];
            }
        }
        
        if (empty($errors)) {
            $result = $krsSystem->createStudent($data);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
                // Delete uploaded photo if student creation failed
                if (!empty($data['foto'])) {
                    $photoUpload->deletePhoto($data['foto']);
                }
            }
        } else {
            $error = implode(', ', $errors);
            // Delete uploaded photo if validation failed
            if (!empty($data['foto'])) {
                $photoUpload->deletePhoto($data['foto']);
            }
        }
    }
    
    if ($action === 'update') {
        $id = $_POST['id'];
        $data = [
            'nim' => sanitizeInput($_POST['nim']),
            'nama' => sanitizeInput($_POST['nama']),
            'tanggal_lahir' => $_POST['tanggal_lahir'],
            'jenis_kelamin' => $_POST['jenis_kelamin'],
            'alamat' => sanitizeInput($_POST['alamat']),
            'nomor_telepon' => sanitizeInput($_POST['nomor_telepon']),
            'email' => sanitizeInput($_POST['email'])
        ];
        
        $errors = validateRequired(['nim', 'nama', 'tanggal_lahir', 'jenis_kelamin'], $data);
        if (!empty($data['email']) && !validateEmail($data['email'])) {
            $errors[] = 'Format email tidak valid';
        }
        
        // Get current student data
        $currentStudent = $krsSystem->getStudentById($id);
        $oldPhoto = $currentStudent['foto'] ?? '';
        
        // Handle photo upload
        if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $photoUpload->uploadPhoto($_FILES['foto'], $id);
            if ($uploadResult['success']) {
                $data['foto'] = $uploadResult['filename'];
                // Delete old photo
                if (!empty($oldPhoto)) {
                    $photoUpload->deletePhoto($oldPhoto);
                }
            } else {
                $errors[] = $uploadResult['message'];
            }
        }
        
        if (empty($errors)) {
            $result = $krsSystem->updateStudent($id, $data);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
                // Restore old photo if update failed and new photo was uploaded
                if (isset($data['foto']) && !empty($oldPhoto)) {
                    $photoUpload->deletePhoto($data['foto']);
                }
            }
        } else {
            $error = implode(', ', $errors);
            // Delete new photo if validation failed
            if (isset($data['foto'])) {
                $photoUpload->deletePhoto($data['foto']);
            }
        }
    }
    
    if ($action === 'delete') {
        $id = $_POST['id'];
        $student = $krsSystem->getStudentById($id);
        $result = $krsSystem->deleteStudent($id);
        if ($result['success']) {
            $message = $result['message'];
            // Delete associated photo
            if (!empty($student['foto'])) {
                $photoUpload->deletePhoto($student['foto']);
            }
        } else {
            $error = $result['message'];
        }
    }
}

$students = $krsSystem->getStudents();
$editStudent = null;

// Get student for editing
if (isset($_GET['edit'])) {
    $editStudent = $krsSystem->getStudentById($_GET['edit']);
}

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title">Kelola Data Mahasiswa</h1>
        <p class="sky-page-subtitle">Tambah, ubah, dan hapus data mahasiswa dengan foto</p>
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
                    <?php echo $editStudent ? 'Ubah Data Mahasiswa' : 'Tambah Mahasiswa Baru'; ?>
                </h3>
                
                <form method="POST" enctype="multipart/form-data" class="space-y-4" id="studentForm">
                    <input type="hidden" name="action" value="<?php echo $editStudent ? 'update' : 'create'; ?>">
                    <?php if ($editStudent): ?>
                        <input type="hidden" name="id" value="<?php echo $editStudent['id_mahasiswa']; ?>">
                    <?php endif; ?>
                    
                    <!-- Photo Upload Section -->
                    <div class="form-group">
                        <label class="form-label">Foto Mahasiswa</label>
                        <div class="photo-upload-container">
                            <div class="photo-preview" id="photoPreview">
                                <?php if ($editStudent && !empty($editStudent['foto'])): ?>
                                    <?php $photoInfo = $photoUpload->getPhotoInfo($editStudent['foto']); ?>
                                    <?php if ($photoInfo && $photoInfo['exists']): ?>
                                        <img src="<?php echo $photoInfo['url']; ?>" alt="Foto Mahasiswa" class="preview-image">
                                        <div class="photo-info">
                                            <small class="text-gray-500">
                                                Ukuran: <?php echo number_format($photoInfo['size'] / 1024, 1); ?> KB
                                            </small>
                                        </div>
                                    <?php else: ?>
                                        <div class="no-photo">
                                            <i class="fas fa-user-circle text-4xl text-gray-300"></i>
                                            <span class="text-gray-500">Tidak ada foto</span>
                                        </div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <div class="no-photo">
                                        <i class="fas fa-user-circle text-4xl text-gray-300"></i>
                                        <span class="text-gray-500">Belum ada foto</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            
                            <div class="photo-upload-controls">
                                <input type="file" name="foto" id="fotoInput" accept="image/*" class="hidden">
                                <label for="fotoInput" class="btn-secondary cursor-pointer">
                                    <i class="fas fa-camera mr-2"></i>
                                    Pilih Foto
                                </label>
                                <button type="button" id="removePhoto" class="btn-danger btn-sm" style="display: none;">
                                    <i class="fas fa-trash mr-1"></i>
                                    Hapus
                                </button>
                            </div>
                            
                            <div class="upload-progress" id="uploadProgress" style="display: none;">
                                <div class="progress-bar">
                                    <div class="progress-fill"></div>
                                </div>
                                <span class="progress-text">Mengupload...</span>
                            </div>
                            
                            <div class="upload-info">
                                <small class="text-gray-500">
                                    Format: JPG, PNG, GIF | Maksimal: 5MB | Otomatis dioptimasi
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nomor Induk Mahasiswa (NIM) *</label>
                        <input type="text" name="nim" class="form-input" 
                               value="<?php echo $editStudent ? htmlspecialchars($editStudent['nim']) : ''; ?>" 
                               required placeholder="Contoh: 2024001001">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="nama" class="form-input" 
                               value="<?php echo $editStudent ? htmlspecialchars($editStudent['nama']) : ''; ?>" 
                               required placeholder="Masukkan nama lengkap">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Tanggal Lahir *</label>
                        <input type="date" name="tanggal_lahir" class="form-input" 
                               value="<?php echo $editStudent ? $editStudent['tanggal_lahir'] : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Jenis Kelamin *</label>
                        <select name="jenis_kelamin" class="form-select" required>
                            <option value="">Pilih jenis kelamin</option>
                            <option value="L" <?php echo ($editStudent && $editStudent['jenis_kelamin'] === 'L') ? 'selected' : ''; ?>>Laki-laki</option>
                            <option value="P" <?php echo ($editStudent && $editStudent['jenis_kelamin'] === 'P') ? 'selected' : ''; ?>>Perempuan</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Alamat Email</label>
                        <input type="email" name="email" class="form-input" 
                               value="<?php echo $editStudent ? htmlspecialchars($editStudent['email']) : ''; ?>"
                               placeholder="contoh@email.com">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="nomor_telepon" class="form-input" 
                               value="<?php echo $editStudent ? htmlspecialchars($editStudent['nomor_telepon']) : ''; ?>"
                               placeholder="08xxxxxxxxxx">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" class="form-input" rows="3" 
                                  placeholder="Masukkan alamat lengkap"><?php echo $editStudent ? htmlspecialchars($editStudent['alamat']) : ''; ?></textarea>
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-1" id="submitBtn">
                            <i class="fas fa-save mr-2"></i>
                            <?php echo $editStudent ? 'Perbarui Data' : 'Simpan Data'; ?>
                        </button>
                        <?php if ($editStudent): ?>
                            <a href="manage_students.php" class="btn-secondary">
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
                    <h3 class="text-lg font-semibold">Daftar Mahasiswa</h3>
                    <span class="badge bg-gray-100 text-gray-800"><?php echo count($students); ?> Total Mahasiswa</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="text-left p-3">Foto</th>
                                <th class="text-left p-3">NIM</th>
                                <th class="text-left p-3">Nama Lengkap</th>
                                <th class="text-left p-3">Jenis Kelamin</th>
                                <th class="text-left p-3">Email</th>
                                <th class="text-center p-3">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3">
                                    <div class="student-photo-thumb">
                                        <?php if (!empty($student['foto'])): ?>
                                            <?php $photoInfo = $photoUpload->getPhotoInfo($student['foto']); ?>
                                            <?php if ($photoInfo && $photoInfo['exists']): ?>
                                                <img src="<?php echo $photoInfo['url']; ?>" alt="Foto <?php echo htmlspecialchars($student['nama']); ?>" class="photo-thumbnail">
                                            <?php else: ?>
                                                <div class="photo-placeholder">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                        <?php else: ?>
                                            <div class="photo-placeholder">
                                                <i class="fas fa-user"></i>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td class="p-3 font-medium"><?php echo htmlspecialchars($student['nim']); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($student['nama']); ?></td>
                                <td class="p-3">
                                    <span class="badge <?php echo $student['jenis_kelamin'] === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'; ?>">
                                        <?php echo $student['jenis_kelamin'] === 'L' ? 'Laki-laki' : 'Perempuan'; ?>
                                    </span>
                                </td>
                                <td class="p-3"><?php echo htmlspecialchars($student['email'] ?? '-'); ?></td>
                                <td class="p-3 text-center">
                                    <div class="flex justify-center gap-2">
                                        <a href="?edit=<?php echo $student['id_mahasiswa']; ?>" 
                                           class="btn-sm btn-primary" title="Ubah Data">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($krsSystem->isDatabaseConnected()): ?>
                                        <form method="POST" class="inline" 
                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus data mahasiswa ini? Foto juga akan dihapus.')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $student['id_mahasiswa']; ?>">
                                            <button type="submit" class="btn-sm btn-danger" title="Hapus Data">
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

<style>
/* Photo Upload Styles */
.photo-upload-container {
    border: 2px dashed #d1d5db;
    border-radius: 0.5rem;
    padding: 1rem;
    text-align: center;
    transition: border-color 0.3s ease;
}

.photo-upload-container:hover {
    border-color: #4f46e5;
}

.photo-preview {
    margin-bottom: 1rem;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

.preview-image {
    max-width: 150px;
    max-height: 150px;
    border-radius: 0.5rem;
    object-fit: cover;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.no-photo {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
}

.photo-upload-controls {
    display: flex;
    gap: 0.5rem;
    justify-content: center;
    margin-bottom: 1rem;
}

.upload-progress {
    margin: 1rem 0;
}

.progress-bar {
    width: 100%;
    height: 8px;
    background: #e5e7eb;
    border-radius: 4px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    background: #4f46e5;
    width: 0%;
    transition: width 0.3s ease;
}

.progress-text {
    font-size: 0.875rem;
    color: #6b7280;
    margin-top: 0.5rem;
}

.upload-info {
    margin-top: 0.5rem;
}

/* Photo Thumbnail in Table */
.student-photo-thumb {
    width: 40px;
    height: 40px;
}

.photo-thumbnail {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #e5e7eb;
}

.photo-placeholder {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #f3f4f6;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #9ca3af;
    border: 2px solid #e5e7eb;
}

.hidden {
    display: none;
}

/* Drag and Drop Styles */
.photo-upload-container.dragover {
    border-color: #4f46e5;
    background-color: #f8fafc;
}

/* Loading State */
.loading .btn-primary {
    opacity: 0.6;
    pointer-events: none;
}

.loading .btn-primary::after {
    content: '';
    display: inline-block;
    width: 16px;
    height: 16px;
    border: 2px solid #ffffff;
    border-radius: 50%;
    border-top-color: transparent;
    animation: spin 1s linear infinite;
    margin-left: 8px;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fotoInput = document.getElementById('fotoInput');
    const photoPreview = document.getElementById('photoPreview');
    const removePhotoBtn = document.getElementById('removePhoto');
    const uploadProgress = document.getElementById('uploadProgress');
    const studentForm = document.getElementById('studentForm');
    const submitBtn = document.getElementById('submitBtn');
    const uploadContainer = document.querySelector('.photo-upload-container');
    
    // File input change handler
    fotoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFileSelect(file);
        }
    });
    
    // Remove photo handler
    removePhotoBtn.addEventListener('click', function() {
        clearPhotoPreview();
        fotoInput.value = '';
    });
    
    // Drag and drop handlers
    uploadContainer.addEventListener('dragover', function(e) {
        e.preventDefault();
        uploadContainer.classList.add('dragover');
    });
    
    uploadContainer.addEventListener('dragleave', function(e) {
        e.preventDefault();
        uploadContainer.classList.remove('dragover');
    });
    
    uploadContainer.addEventListener('drop', function(e) {
        e.preventDefault();
        uploadContainer.classList.remove('dragover');
        
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (file.type.startsWith('image/')) {
                fotoInput.files = files;
                handleFileSelect(file);
            } else {
                alert('Hanya file gambar yang diperbolehkan');
            }
        }
    });
    
    // Form submit handler
    studentForm.addEventListener('submit', function(e) {
        const file = fotoInput.files[0];
        if (file) {
            // Validate file size
            if (file.size > 5 * 1024 * 1024) {
                e.preventDefault();
                alert('Ukuran file terlalu besar. Maksimal 5MB.');
                return;
            }
            
            // Show loading state
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        }
    });
    
    function handleFileSelect(file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
            alert('Hanya file gambar yang diperbolehkan');
            return;
        }
        
        // Validate file size
        if (file.size > 5 * 1024 * 1024) {
            alert('Ukuran file terlalu besar. Maksimal 5MB.');
            return;
        }
        
        // Show preview
        const reader = new FileReader();
        reader.onload = function(e) {
            showPhotoPreview(e.target.result, file);
        };
        reader.readAsDataURL(file);
    }
    
    function showPhotoPreview(src, file) {
        const fileSize = (file.size / 1024).toFixed(1);
        
        photoPreview.innerHTML = `
            <img src="${src}" alt="Preview" class="preview-image">
            <div class="photo-info">
                <small class="text-gray-500">
                    ${file.name} (${fileSize} KB)
                </small>
            </div>
        `;
        
        removePhotoBtn.style.display = 'inline-block';
    }
    
    function clearPhotoPreview() {
        photoPreview.innerHTML = `
            <div class="no-photo">
                <i class="fas fa-user-circle text-4xl text-gray-300"></i>
                <span class="text-gray-500">Belum ada foto</span>
            </div>
        `;
        removePhotoBtn.style.display = 'none';
    }
    
    // Image compression function (optional enhancement)
    function compressImage(file, maxWidth = 800, quality = 0.8) {
        return new Promise((resolve) => {
            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');
            const img = new Image();
            
            img.onload = function() {
                const ratio = Math.min(maxWidth / img.width, maxWidth / img.height);
                canvas.width = img.width * ratio;
                canvas.height = img.height * ratio;
                
                ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                
                canvas.toBlob(resolve, 'image/jpeg', quality);
            };
            
            img.src = URL.createObjectURL(file);
        });
    }
});
</script>

<link rel="stylesheet" href="assets/css/forms.css">

<?php include 'includes/footer.php'; ?>
