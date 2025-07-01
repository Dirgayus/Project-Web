<?php
session_start();

class Auth {
    private $db;
    private $conn;

    public function __construct() {
        require_once 'database.php';
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Register new user
    public function register($username, $email, $password, $full_name, $role = 'student') {
        if (!$this->conn) {
            return ['success' => false, 'message' => 'Koneksi basis data gagal - Pendaftaran hanya tersedia dengan basis data'];
        }

        try {
            // Check if username or email already exists
            $query = "SELECT id FROM users WHERE username = ? OR email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$username, $email]);
            
            if ($stmt->rowCount() > 0) {
                return ['success' => false, 'message' => 'Nama pengguna atau email sudah terdaftar'];
            }

            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user
            $query = "INSERT INTO users (username, email, password, full_name, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$username, $email, $hashed_password, $full_name, $role]);

            return ['success' => true, 'message' => 'Pendaftaran berhasil'];
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Kesalahan: ' . $e->getMessage()];
        }
    }

    // Login user
    public function login($username, $password) {
        if (!$this->conn) {
            // Demo login for when database is not available
            if ($username === 'admin' && $password === 'admin123') {
                $_SESSION['user_id'] = 1;
                $_SESSION['username'] = 'admin';
                $_SESSION['full_name'] = 'Administrator';
                $_SESSION['email'] = 'admin@skyuniversity.ac.id';
                $_SESSION['role'] = 'admin';
                $_SESSION['last_login'] = date('Y-m-d H:i:s');
                return ['success' => true, 'message' => 'Berhasil masuk (Mode Demo)'];
            } elseif ($username === 'student' && $password === 'student123') {
                $_SESSION['user_id'] = 2;
                $_SESSION['username'] = 'student';
                $_SESSION['full_name'] = 'Andi Pratama';
                $_SESSION['email'] = 'andi.pratama@student.ac.id';
                $_SESSION['role'] = 'student';
                $_SESSION['last_login'] = date('Y-m-d H:i:s');
                return ['success' => true, 'message' => 'Berhasil masuk (Mode Demo)'];
            } elseif ($username === 'lecturer' && $password === 'lecturer123') {
                $_SESSION['user_id'] = 3;
                $_SESSION['username'] = 'lecturer';
                $_SESSION['full_name'] = 'Dr. Ahmad Wijaya';
                $_SESSION['email'] = 'ahmad.wijaya@university.ac.id';
                $_SESSION['role'] = 'lecturer';
                $_SESSION['last_login'] = date('Y-m-d H:i:s');
                return ['success' => true, 'message' => 'Berhasil masuk (Mode Demo)'];
            } else {
                return ['success' => false, 'message' => 'Nama pengguna atau kata sandi salah'];
            }
        }

        try {
            $query = "SELECT id, username, email, password, full_name, role, last_login FROM users WHERE username = ? OR email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$username, $username]);
            
            if ($stmt->rowCount() === 1) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                if (password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    $_SESSION['full_name'] = $user['full_name'];
                    $_SESSION['email'] = $user['email'];
                    $_SESSION['role'] = $user['role'];
                    $_SESSION['last_login'] = $user['last_login'];

                    // Update last login
                    $update_query = "UPDATE users SET last_login = NOW() WHERE id = ?";
                    $update_stmt = $this->conn->prepare($update_query);
                    $update_stmt->execute([$user['id']]);

                    return ['success' => true, 'message' => 'Berhasil masuk'];
                } else {
                    return ['success' => false, 'message' => 'Kata sandi salah'];
                }
            } else {
                return ['success' => false, 'message' => 'Nama pengguna tidak ditemukan'];
            }
        } catch (PDOException $e) {
            return ['success' => false, 'message' => 'Kesalahan: ' . $e->getMessage()];
        }
    }

    // Logout user
    public function logout() {
        session_unset();
        session_destroy();
        return ['success' => true, 'message' => 'Berhasil keluar'];
    }

    // Check if user is logged in
    public function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }

    // Get current user info
    public function getCurrentUser() {
        if ($this->isLoggedIn()) {
            return [
                'id' => $_SESSION['user_id'],
                'username' => $_SESSION['username'],
                'full_name' => $_SESSION['full_name'],
                'email' => $_SESSION['email'],
                'role' => $_SESSION['role'],
                'last_login' => $_SESSION['last_login'] ?? null
            ];
        }
        return null;
    }

    // Check user role
    public function hasRole($role) {
        return $this->isLoggedIn() && $_SESSION['role'] === $role;
    }

    // Check if user has any of the specified roles
    public function hasAnyRole($roles) {
        if (!$this->isLoggedIn()) return false;
        return in_array($_SESSION['role'], $roles);
    }

    // Require login
    public function requireLogin() {
        if (!$this->isLoggedIn()) {
            header('Location: login.php');
            exit();
        }
    }

    // Require specific role
    public function requireRole($role) {
        $this->requireLogin();
        if (!$this->hasRole($role)) {
            header('Location: unauthorized.php');
            exit();
        }
    }

    // Require any of the specified roles
    public function requireAnyRole($roles) {
        $this->requireLogin();
        if (!$this->hasAnyRole($roles)) {
            header('Location: unauthorized.php');
            exit();
        }
    }
}

// Initialize auth
$auth = new Auth();
?>
