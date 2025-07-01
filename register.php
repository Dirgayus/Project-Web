<?php
require_once 'config/auth.php';

$error_message = '';
$success_message = '';

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    header('Location: index.php');
    exit();
}

// Handle registration form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $full_name = trim($_POST['full_name'] ?? '');
    $role = $_POST['role'] ?? 'student';

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error_message = 'Semua kolom wajib harus diisi';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = 'Format email tidak valid';
    } elseif (strlen($password) < 6) {
        $error_message = 'Kata sandi minimal 6 karakter';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Konfirmasi kata sandi tidak cocok';
    } else {
        $result = $auth->register($username, $email, $password, $full_name, $role);
        if ($result['success']) {
            $success_message = $result['message'] . ' Silakan masuk menggunakan akun Anda.';
            // Clear form data
            $_POST = [];
        } else {
            $error_message = $result['message'];
        }
    }
}

$pageTitle = 'Daftar';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Wano Saga University</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <link href="assets/css/forms.css" rel="stylesheet">
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg,rgb(77, 76, 91) 0%,rgb(137, 41, 137) 100%);
            padding: 1rem;
        }
        
        .auth-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2rem;
            width: 100%;
            max-width: 450px;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .auth-logo i {
            font-size: 2rem;
            color: #4f46e5;
        }
        
        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 0.5rem;
        }
        
        .auth-subtitle {
            color: #6b7280;
            font-size: 0.875rem;
        }
        
        .auth-footer {
            text-align: center;
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 1px solid #e5e7eb;
        }
        
        .auth-link {
            color: #4f46e5;
            text-decoration: none;
            font-size: 0.875rem;
        }
        
        .auth-link:hover {
            text-decoration: underline;
        }
        
        .demo-info {
            background-color: #fffbeb;
            border: 1px solid #fed7aa;
            color: #ea580c;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-card sky-fade-in">
            <div class="auth-header">
                <div class="auth-logo">
                    <i class="fas fa-graduation-cap"></i>
                    <div>
                        <div class="font-bold text-lg">WANO SAGA</div>
                        <div class="text-sm font-medium">UNIVERSITY</div>
                    </div>
                </div>
                <h1 class="auth-title">Daftar Akun Baru dulu yaa!</h1>
                <p class="auth-subtitle">Buat akun untuk mengakses Sistem KRS <br> Wano Saga University</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success_message)): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle mr-2"></i>
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <div class="demo-info">
                <strong><i class="fas fa-exclamation-triangle mr-1"></i> Akses Resmi Wano Saga University</strong>
                <div class="mt-1">Pendaftaran hanya berfungsi jika sudah registrasi yaa!.</div>
            </div>

            <form method="POST" action="" id="registerForm">
                <div class="form-group">
                    <label for="full_name" class="form-label">Nama Lengkap *</label>
                    <input 
                        type="text" 
                        id="full_name" 
                        name="full_name" 
                        class="form-input" 
                        value="<?php echo htmlspecialchars($_POST['full_name'] ?? ''); ?>"
                        required
                        placeholder="Masukkan nama lengkap"
                    >
                </div>

                <div class="form-group">
                    <label for="NIM" class="form-label">NIM (Jika Anda Mahasiswa)*</label>
                    <input 
                        type="text" 
                        id="NIM" 
                        name="NIM" 
                        class="form-input" 
                        value="<?php echo htmlspecialchars($_POST['NIM'] ?? ''); ?>"
                        autocomplete="NIM"
                        placeholder="Masukkan NIM Anda"
                    >
                </div>

                <div class="form-group">
                    <label for="NIDN" class="form-label">NIDN (Jika Anda Dosen)*</label>
                    <input 
                        type="text" 
                        id="NIDN" 
                        name="NIDN" 
                        class="form-input" 
                        value="<?php echo htmlspecialchars($_POST['NIDN'] ?? ''); ?>"
                        autocomplete="NIDN"
                        placeholder="Masukkan NIDN Anda"
                    >
                </div>

                <div class="form-group">
                    <label for="username" class="form-label">Nama Pengguna *</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-input" 
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                        required
                        autocomplete="username"
                        placeholder="Masukkan nama pengguna"
                    >
                </div>

                <div class="form-group">
                    <label for="email" class="form-label">Email *</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                        required
                        autocomplete="email"
                        placeholder="Masukkan alamat email"
                    >
                </div>

                <div class="form-group">
                    <label for="role" class="form-label">Peran *</label>
                    <select id="role" name="role" class="form-select" required>
                        <option value="student" <?php echo ($_POST['role'] ?? 'student') === 'student' ? 'selected' : ''; ?>>Mahasiswa</option>
                        <option value="lecturer" <?php echo ($_POST['role'] ?? '') === 'lecturer' ? 'selected' : ''; ?>>Dosen</option>
                        <option value="admin" <?php echo ($_POST['role'] ?? '') === 'admin' ? 'selected' : ''; ?>>Administrator</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi *</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        required
                        autocomplete="new-password"
                        minlength="6"
                        placeholder="Minimal 6 karakter"
                    >
                </div>

                <div class="form-group">
                    <label for="confirm_password" class="form-label">Konfirmasi Kata Sandi *</label>
                    <input 
                        type="password" 
                        id="confirm_password" 
                        name="confirm_password" 
                        class="form-input" 
                        required
                        autocomplete="new-password"
                        placeholder="Ulangi kata sandi"
                    >
                </div>

                <button type="submit" class="btn-primary w-full" id="registerBtn">
                    <i class="fas fa-user-plus mr-2"></i>
                    Daftar
                </button>
            </form>

            <div class="auth-footer">
                <p class="text-sm text-gray-600">
                    Sudah memiliki akun? 
                    <a href="login.php" class="auth-link">Masuk di sini</a>
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    © 2025/2026 Wano Saga University. Hak cipta dilindungi.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('registerForm');
            const registerBtn = document.getElementById('registerBtn');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');
            
            form.addEventListener('submit', function() {
                registerBtn.disabled = true;
                registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sedang memproses...';
            });

            // Password confirmation validation
            confirmPassword.addEventListener('input', function() {
                if (password.value !== confirmPassword.value) {
                    confirmPassword.setCustomValidity('Kata sandi tidak cocok');
                } else {
                    confirmPassword.setCustomValidity('');
                }
            });
        });
    </script>
</body>
</html>
