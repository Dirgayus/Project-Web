<?php
require_once 'config/auth.php';

$error_message = '';
$success_message = '';

// Redirect if already logged in
if ($auth->isLoggedIn()) {
    header('Location: index.php');
    exit();
}

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $error_message = 'Nama pengguna dan kata sandi harus diisi';
    } else {
        $result = $auth->login($username, $password);
        if ($result['success']) {
            $success_message = $result['message'];
            // Redirect after successful login
            header('refresh:2;url=index.php');
        } else {
            $error_message = $result['message'];
        }
    }
}

$pageTitle = 'Masuk';
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
            background: linear-gradient(135deg,rgb(106, 104, 137) 0%,rgb(3, 4, 4) 100%);
            padding: 1rem;
        }
        
        .auth-card {
            background: white;
            border-radius: 1rem;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
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
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            color: #1e40af;
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
        }
        
        .demo-credentials {
            margin-top: 0.5rem;
            font-family: monospace;
            font-size: 0.8rem;
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
                <h1 class="auth-title">Selamat Datang</h1>
                <p class="auth-subtitle">Masuk ke Sistem KRS Wano Saga University</p>
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
                    <div class="mt-2">Sedang mengalihkan ke beranda...</div>
                </div>
            <?php endif; ?>

            <div class="information">
                <strong><i class="fas fa-info-circle mr-1"></i> information</strong>
                <div class="demo-info">
                        <strong class="fas fa-info-circle mr-1"></strong> Kami senang Anda bergabung. Silakan masuk untuk menjelajahi berbagai fitur dan sumber daya yang tersedia untuk membantu perjalanan akademis Anda. Bersama-sama, kita tingkatkan pengalaman belajar di kampus ini!
                    </div>
                </div>

            <form method="POST" action="" id="loginForm">
                <div class="form-group">
                    <label for="username" class="form-label">Nama Pengguna atau Email</label>
                    <input 
                        type="text" 
                        id="username" 
                        name="username" 
                        class="form-input" 
                        value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                        required
                        autocomplete="username"
                        placeholder="Masukkan nama pengguna atau email"
                    >
                </div>

                <div class="form-group">
                    <label for="password" class="form-label">Kata Sandi</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan kata sandi"
                    >
                </div>

                <button type="submit" class="btn-primary w-full" id="loginBtn">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Masuk
                </button>
            </form>

            <div class="auth-footer">
                <p class="text-sm text-gray-600">
                    Belum memiliki akun? 
                    <a href="register.php" class="auth-link">Daftar di sini</a>
                </p>
                <p class="text-xs text-gray-500 mt-2">
                    © 2025/2026 Wano Saga University. Hak cipta dilindungi.
                </p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('loginForm');
            const loginBtn = document.getElementById('loginBtn');
            
            form.addEventListener('submit', function() {
                loginBtn.disabled = true;
                loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Sedang memproses...';
            });

            // Auto-fill demo credentials
            document.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === '1') {
                    document.getElementById('username').value = 'admin';
                    document.getElementById('password').value = 'admin123';
                    e.preventDefault();
                }
                if (e.ctrlKey && e.key === '2') {
                    document.getElementById('username').value = 'lecturer';
                    document.getElementById('password').value = 'lecturer123';
                    e.preventDefault();
                }
                if (e.ctrlKey && e.key === '3') {
                    document.getElementById('username').value = 'student';
                    document.getElementById('password').value = 'student123';
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>
