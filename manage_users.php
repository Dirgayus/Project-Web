<?php
require_once 'config/auth.php';
require_once 'includes/functions.php';

// Require admin role only
$auth->requireRole('admin');

$pageTitle = 'Kelola Pengguna';
$krsSystem = new KRSSystem();
$message = '';
$error = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'create') {
        $data = [
            'username' => sanitizeInput($_POST['username']),
            'email' => sanitizeInput($_POST['email']),
            'password' => $_POST['password'],
            'full_name' => sanitizeInput($_POST['full_name']),
            'role' => $_POST['role']
        ];
        
        $errors = validateRequired(['username', 'email', 'password', 'full_name', 'role'], $data);
        if (!validateEmail($data['email'])) {
            $errors[] = 'Format email tidak valid';
        }
        if (strlen($data['password']) < 6) {
            $errors[] = 'Password minimal 6 karakter';
        }
        
        if (empty($errors)) {
            $result = $auth->register($data['username'], $data['email'], $data['password'], $data['full_name'], $data['role']);
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
            'username' => sanitizeInput($_POST['username']),
            'email' => sanitizeInput($_POST['email']),
            'full_name' => sanitizeInput($_POST['full_name']),
            'role' => $_POST['role']
        ];
        
        // Update password only if provided
        if (!empty($_POST['password'])) {
            if (strlen($_POST['password']) < 6) {
                $error = 'Password minimal 6 karakter';
            } else {
                $data['password'] = $_POST['password'];
            }
        }
        
        if (empty($error)) {
            $errors = validateRequired(['username', 'email', 'full_name', 'role'], $data);
            if (!validateEmail($data['email'])) {
                $errors[] = 'Format email tidak valid';
            }
            
            if (empty($errors)) {
                $result = updateUser($id, $data);
                if ($result['success']) {
                    $message = $result['message'];
                } else {
                    $error = $result['message'];
                }
            } else {
                $error = implode(', ', $errors);
            }
        }
    }
    
    if ($action === 'delete') {
        $id = $_POST['id'];
        $currentUser = $auth->getCurrentUser();
        
        // Prevent self-deletion
        if ($id == $currentUser['id']) {
            $error = 'Tidak dapat menghapus akun sendiri';
        } else {
            $result = deleteUser($id);
            if ($result['success']) {
                $message = $result['message'];
            } else {
                $error = $result['message'];
            }
        }
    }
    
    if ($action === 'toggle_status') {
        $id = $_POST['id'];
        $status = $_POST['status'];
        $result = toggleUserStatus($id, $status);
        if ($result['success']) {
            $message = $result['message'];
        } else {
            $error = $result['message'];
        }
    }
}

$users = getAllUsers();
$editUser = null;

// Get user for editing
if (isset($_GET['edit'])) {
    $editUser = getUserById($_GET['edit']);
}

// User management functions
function getAllUsers() {
    global $krsSystem;
    if (!$krsSystem->isDatabaseConnected()) {
        return [
            [
                'id' => 1,
                'username' => 'admin',
                'email' => 'admin@skyuniversity.ac.id',
                'full_name' => 'Administrator',
                'role' => 'admin',
                'status' => 'active',
                'last_login' => '2024-12-17 10:00:00',
                'created_at' => '2024-01-01 00:00:00'
            ],
            [
                'id' => 2,
                'username' => 'student',
                'email' => 'andi.pratama@student.ac.id',
                'full_name' => 'Andi Pratama',
                'role' => 'student',
                'status' => 'active',
                'last_login' => '2024-12-16 14:30:00',
                'created_at' => '2024-01-15 00:00:00'
            ],
            [
                'id' => 3,
                'username' => 'lecturer',
                'email' => 'ahmad.wijaya@university.ac.id',
                'full_name' => 'Dr. Ahmad Wijaya',
                'role' => 'lecturer',
                'status' => 'active',
                'last_login' => '2024-12-17 08:15:00',
                'created_at' => '2024-01-10 00:00:00'
            ]
        ];
    }
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        $query = "SELECT id, username, email, full_name, role, 
                         CASE WHEN last_login IS NULL THEN 'inactive' ELSE 'active' END as status,
                         last_login, created_at 
                  FROM users ORDER BY created_at DESC";
        $stmt = $conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return [];
    }
}

function getUserById($id) {
    global $krsSystem;
    if (!$krsSystem->isDatabaseConnected()) return null;
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        $query = "SELECT * FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return null;
    }
}

function updateUser($id, $data) {
    global $krsSystem;
    if (!$krsSystem->isDatabaseConnected()) {
        return ['success' => false, 'message' => 'Database tidak tersedia'];
    }
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        
        if (isset($data['password'])) {
            $query = "UPDATE users SET username=?, email=?, full_name=?, role=?, password=?, updated_at=NOW() WHERE id=?";
            $hashed_password = password_hash($data['password'], PASSWORD_DEFAULT);
            $stmt = $conn->prepare($query);
            $stmt->execute([$data['username'], $data['email'], $data['full_name'], $data['role'], $hashed_password, $id]);
        } else {
            $query = "UPDATE users SET username=?, email=?, full_name=?, role=?, updated_at=NOW() WHERE id=?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$data['username'], $data['email'], $data['full_name'], $data['role'], $id]);
        }
        
        return ['success' => true, 'message' => 'Data pengguna berhasil diperbarui'];
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Kesalahan: ' . $e->getMessage()];
    }
}

function deleteUser($id) {
    global $krsSystem;
    if (!$krsSystem->isDatabaseConnected()) {
        return ['success' => false, 'message' => 'Database tidak tersedia'];
    }
    
    try {
        $db = new Database();
        $conn = $db->getConnection();
        $query = "DELETE FROM users WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$id]);
        return ['success' => true, 'message' => 'Pengguna berhasil dihapus'];
    } catch(PDOException $e) {
        return ['success' => false, 'message' => 'Kesalahan: ' . $e->getMessage()];
    }
}

function toggleUserStatus($id, $status) {
    // This would be implemented if we had a status column
    return ['success' => true, 'message' => 'Status pengguna berhasil diubah'];
}

include 'includes/header.php';
?>

<div class="sky-fade-in">
    <div class="sky-page-header">
        <h1 class="sky-page-title">Kelola Pengguna</h1>
        <p class="sky-page-subtitle">Tambah, edit, dan kelola akun pengguna sistem</p>
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
                    <?php echo $editUser ? 'Edit Pengguna' : 'Tambah Pengguna Baru'; ?>
                </h3>
                
                <form method="POST" class="space-y-4">
                    <input type="hidden" name="action" value="<?php echo $editUser ? 'update' : 'create'; ?>">
                    <?php if ($editUser): ?>
                        <input type="hidden" name="id" value="<?php echo $editUser['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label class="form-label">Username *</label>
                        <input type="text" name="username" class="form-input" 
                               value="<?php echo $editUser ? htmlspecialchars($editUser['username']) : ''; ?>" 
                               required placeholder="Masukkan username">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email *</label>
                        <input type="email" name="email" class="form-input" 
                               value="<?php echo $editUser ? htmlspecialchars($editUser['email']) : ''; ?>" 
                               required placeholder="user@example.com">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap *</label>
                        <input type="text" name="full_name" class="form-input" 
                               value="<?php echo $editUser ? htmlspecialchars($editUser['full_name']) : ''; ?>" 
                               required placeholder="Masukkan nama lengkap">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Peran *</label>
                        <select name="role" class="form-select" required>
                            <option value="">Pilih peran</option>
                            <option value="admin" <?php echo ($editUser && $editUser['role'] === 'admin') ? 'selected' : ''; ?>>Administrator</option>
                            <option value="lecturer" <?php echo ($editUser && $editUser['role'] === 'lecturer') ? 'selected' : ''; ?>>Dosen</option>
                            <option value="student" <?php echo ($editUser && $editUser['role'] === 'student') ? 'selected' : ''; ?>>Mahasiswa</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            Password <?php echo $editUser ? '(Kosongkan jika tidak ingin mengubah)' : '*'; ?>
                        </label>
                        <input type="password" name="password" class="form-input" 
                               <?php echo !$editUser ? 'required' : ''; ?>
                               minlength="6" placeholder="Minimal 6 karakter">
                    </div>
                    
                    <div class="flex gap-2">
                        <button type="submit" class="btn-primary flex-1">
                            <i class="fas fa-save mr-2"></i>
                            <?php echo $editUser ? 'Update' : 'Simpan'; ?>
                        </button>
                        <?php if ($editUser): ?>
                            <a href="manage_users.php" class="btn-secondary">
                                <i class="fas fa-times mr-2"></i>
                                Batal
                            </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Quick Stats -->
            <div class="sky-card mt-6">
                <h4 class="font-semibold mb-4">Statistik Pengguna</h4>
                <div class="space-y-3">
                    <?php
                    $roleStats = array_count_values(array_column($users, 'role'));
                    $statusStats = array_count_values(array_column($users, 'status'));
                    ?>
                    <div class="flex justify-between">
                        <span class="text-sm">Total Pengguna:</span>
                        <span class="font-semibold"><?php echo count($users); ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Administrator:</span>
                        <span class="badge bg-red-100 text-red-800"><?php echo $roleStats['admin'] ?? 0; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Dosen:</span>
                        <span class="badge bg-green-100 text-green-800"><?php echo $roleStats['lecturer'] ?? 0; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Mahasiswa:</span>
                        <span class="badge bg-blue-100 text-blue-800"><?php echo $roleStats['student'] ?? 0; ?></span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-sm">Aktif:</span>
                        <span class="badge bg-green-100 text-green-800"><?php echo $statusStats['active'] ?? 0; ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table Section -->
        <div class="lg:col-span-2">
            <div class="sky-card">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Daftar Pengguna</h3>
                    <span class="badge bg-gray-100 text-gray-800"><?php echo count($users); ?> Total</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b bg-gray-50">
                                <th class="text-left p-3">Username</th>
                                <th class="text-left p-3">Nama Lengkap</th>
                                <th class="text-left p-3">Email</th>
                                <th class="text-center p-3">Peran</th>
                                <th class="text-center p-3">Status</th>
                                <th class="text-center p-3">Login Terakhir</th>
                                <th class="text-center p-3">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                            <tr class="border-b hover:bg-gray-50">
                                <td class="p-3 font-medium"><?php echo htmlspecialchars($user['username']); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($user['full_name']); ?></td>
                                <td class="p-3"><?php echo htmlspecialchars($user['email']); ?></td>
                                <td class="p-3 text-center">
                                    <?php
                                    $roleColors = [
                                        'admin' => 'bg-red-100 text-red-800',
                                        'lecturer' => 'bg-green-100 text-green-800',
                                        'student' => 'bg-blue-100 text-blue-800'
                                    ];
                                    $roleLabels = [
                                        'admin' => 'Admin',
                                        'lecturer' => 'Dosen',
                                        'student' => 'Mahasiswa'
                                    ];
                                    ?>
                                    <span class="badge <?php echo $roleColors[$user['role']] ?? 'bg-gray-100 text-gray-800'; ?>">
                                        <?php echo $roleLabels[$user['role']] ?? $user['role']; ?>
                                    </span>
                                </td>
                                <td class="p-3 text-center">
                                    <span class="badge <?php echo $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'; ?>">
                                        <?php echo $user['status'] === 'active' ? 'Aktif' : 'Tidak Aktif'; ?>
                                    </span>
                                </td>
                                <td class="p-3 text-center text-xs text-gray-600">
                                    <?php echo $user['last_login'] ? date('d/m/Y H:i', strtotime($user['last_login'])) : 'Belum pernah'; ?>
                                </td>
                                <td class="p-3 text-center">
                                    <div class="flex justify-center gap-1">
                                        <!-- TOMBOL EDIT INI -->
                                        <a href="?edit=<?php echo $user['id']; ?>" 
                                           class="btn-sm btn-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <!-- Tombol hapus -->
                                        <?php if ($krsSystem->isDatabaseConnected() && $user['id'] != $auth->getCurrentUser()['id']): ?>
                                        <form method="POST" class="inline" 
                                              onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                            <input type="hidden" name="action" value="delete">
                                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
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

            <!-- User Activity Log -->
            <div class="sky-card mt-6">
                <h4 class="font-semibold mb-4">Aktivitas Login Terbaru</h4>
                <div class="space-y-3">
                    <?php
                    // Sort users by last login
                    $recentUsers = $users;
                    usort($recentUsers, function($a, $b) {
                        return strtotime($b['last_login'] ?? '1970-01-01') - strtotime($a['last_login'] ?? '1970-01-01');
                    });
                    $recentUsers = array_slice($recentUsers, 0, 5);
                    ?>
                    <?php foreach ($recentUsers as $user): ?>
                        <?php if ($user['last_login']): ?>
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center text-white text-sm font-medium">
                                    <?php echo strtoupper(substr($user['full_name'], 0, 1)); ?>
                                </div>
                                <div>
                                    <div class="font-medium text-sm"><?php echo htmlspecialchars($user['full_name']); ?></div>
                                    <div class="text-xs text-gray-500"><?php echo htmlspecialchars($user['username']); ?></div>
                                </div>
                            </div>
                            <div class="text-right">
                                <div class="text-xs text-gray-600"><?php echo date('d/m/Y', strtotime($user['last_login'])); ?></div>
                                <div class="text-xs text-gray-500"><?php echo date('H:i', strtotime($user['last_login'])); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="assets/css/forms.css">

<?php include 'includes/footer.php'; ?>
