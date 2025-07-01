<?php
require_once 'config/database.php';

class KRSSystem {
    private $db;
    private $conn;

    public function __construct() {
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Enhanced CRUD Operations for Students with Photo Support
    public function getStudents() {
        if (!$this->conn) {
            return DemoData::getStudents();
        }

        try {
            $query = "SELECT * FROM mahasiswa ORDER BY nama ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: DemoData::getStudents();
        } catch(PDOException $e) {
            error_log("Database error in getStudents: " . $e->getMessage());
            return DemoData::getStudents();
        }
    }

    public function getStudentById($id) {
        if (!$this->conn) return null;
        
        try {
            $query = "SELECT * FROM mahasiswa WHERE id_mahasiswa = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch(PDOException $e) {
            return null;
        }
    }

    public function createStudent($data) {
        if (!$this->conn) {
            return ['success' => false, 'message' => 'Basis data tidak tersedia'];
        }

        try {
            $query = "INSERT INTO mahasiswa (nim, nama, tanggal_lahir, jenis_kelamin, alamat, nomor_telepon, email, foto) 
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $data['nim'], $data['nama'], $data['tanggal_lahir'], 
                $data['jenis_kelamin'], $data['alamat'], $data['nomor_telepon'], 
                $data['email'], $data['foto']
            ]);
            return ['success' => true, 'message' => 'Data mahasiswa berhasil ditambahkan'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Kesalahan: ' . $e->getMessage()];
        }
    }

    public function updateStudent($id, $data) {
        if (!$this->conn) {
            return ['success' => false, 'message' => 'Basis data tidak tersedia'];
        }

        try {
            // Build query dynamically based on whether photo is being updated
            if (isset($data['foto'])) {
                $query = "UPDATE mahasiswa SET nim=?, nama=?, tanggal_lahir=?, jenis_kelamin=?, 
                         alamat=?, nomor_telepon=?, email=?, foto=?, updated_at=NOW() WHERE id_mahasiswa=?";
                $params = [
                    $data['nim'], $data['nama'], $data['tanggal_lahir'], 
                    $data['jenis_kelamin'], $data['alamat'], $data['nomor_telepon'], 
                    $data['email'], $data['foto'], $id
                ];
            } else {
                $query = "UPDATE mahasiswa SET nim=?, nama=?, tanggal_lahir=?, jenis_kelamin=?, 
                         alamat=?, nomor_telepon=?, email=?, updated_at=NOW() WHERE id_mahasiswa=?";
                $params = [
                    $data['nim'], $data['nama'], $data['tanggal_lahir'], 
                    $data['jenis_kelamin'], $data['alamat'], $data['nomor_telepon'], 
                    $data['email'], $id
                ];
            }
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            return ['success' => true, 'message' => 'Data mahasiswa berhasil diperbarui'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Kesalahan: ' . $e->getMessage()];
        }
    }

    public function deleteStudent($id) {
        if (!$this->conn) {
            return ['success' => false, 'message' => 'Basis data tidak tersedia'];
        }

        try {
            $query = "DELETE FROM mahasiswa WHERE id_mahasiswa = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([$id]);
            return ['success' => true, 'message' => 'Data mahasiswa berhasil dihapus'];
        } catch(PDOException $e) {
            return ['success' => false, 'message' => 'Kesalahan: ' . $e->getMessage()];
        }
    }

    // Other existing methods remain the same...
    public function getCourses() {
        if (!$this->conn) {
            return DemoData::getCourses();
        }

        try {
            $query = "SELECT * FROM mata_kuliah ORDER BY semester ASC, nama_matakuliah ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: DemoData::getCourses();
        } catch(PDOException $e) {
            error_log("Database error in getCourses: " . $e->getMessage());
            return DemoData::getCourses();
        }
    }

    public function getLecturers() {
        if (!$this->conn) {
            return DemoData::getLecturers();
        }

        try {
            $query = "SELECT * FROM dosen ORDER BY nama_dosen ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: DemoData::getLecturers();
        } catch(PDOException $e) {
            error_log("Database error in getLecturers: " . $e->getMessage());
            return DemoData::getLecturers();
        }
    }

    public function getClasses() {
        if (!$this->conn) {
            return DemoData::getClasses();
        }

        try {
            $query = "SELECT k.*, mk.nama_matakuliah, mk.kode_matakuliah, mk.sks, 
                            d.nama_dosen, ta.tahun_akademik, ta.semester_akademik
                     FROM kelas k
                     JOIN mata_kuliah mk ON k.id_matakuliah = mk.id_matakuliah
                     JOIN dosen d ON k.id_dosen = d.id_dosen
                     JOIN tahun_akademik ta ON k.id_tahun_akademik = ta.id_tahun_akademik
                     ORDER BY ta.tahun_akademik DESC, mk.nama_matakuliah ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: DemoData::getClasses();
        } catch(PDOException $e) {
            error_log("Database error in getClasses: " . $e->getMessage());
            return DemoData::getClasses();
        }
    }

    public function getKRSEntries() {
        if (!$this->conn) {
            return DemoData::getKRS();
        }

        try {
            $query = "SELECT krs.*, m.nim, m.nama as nama_mahasiswa,
                            mk.kode_matakuliah, mk.nama_matakuliah, mk.sks,
                            d.nama_dosen, k.nama_kelas,
                            ta.tahun_akademik, ta.semester_akademik
                     FROM krs
                     JOIN mahasiswa m ON krs.id_mahasiswa = m.id_mahasiswa
                     JOIN kelas k ON krs.id_kelas = k.id_kelas
                     JOIN mata_kuliah mk ON k.id_matakuliah = mk.id_matakuliah
                     JOIN dosen d ON k.id_dosen = d.id_dosen
                     JOIN tahun_akademik ta ON k.id_tahun_akademik = ta.id_tahun_akademik
                     ORDER BY ta.tahun_akademik DESC, m.nama ASC";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: DemoData::getKRS();
        } catch(PDOException $e) {
            error_log("Database error in getKRSEntries: " . $e->getMessage());
            return DemoData::getKRS();
        }
    }

    public function getDashboardStats() {
        if (!$this->conn) {
            return DemoData::getDashboardStats();
        }

        try {
            $stats = [];
            
            $query = "SELECT COUNT(*) as count FROM mahasiswa";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['totalStudents'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            $query = "SELECT COUNT(*) as count FROM mata_kuliah";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['totalCourses'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            $query = "SELECT COUNT(*) as count FROM dosen";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['totalLecturers'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            $query = "SELECT COUNT(*) as count FROM krs WHERE status_krs = 'Aktif'";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $stats['activeRegistrations'] = $stmt->fetch(PDO::FETCH_ASSOC)['count'];

            return $stats;
        } catch(PDOException $e) {
            error_log("Database error in getDashboardStats: " . $e->getMessage());
            return DemoData::getDashboardStats();
        }
    }

    public function isDatabaseConnected() {
        return $this->conn !== null;
    }
}

// Utility functions remain the same...
function formatDate($date) {
    return date('d/m/Y', strtotime($date));
}

function getSemesterColor($semester) {
    $colors = [
        1 => 'bg-blue-100 text-blue-800',
        2 => 'bg-green-100 text-green-800',
        3 => 'bg-yellow-100 text-yellow-800',
        4 => 'bg-purple-100 text-purple-800',
        5 => 'bg-pink-100 text-pink-800',
        6 => 'bg-indigo-100 text-indigo-800',
        7 => 'bg-red-100 text-red-800',
        8 => 'bg-orange-100 text-orange-800'
    ];
    return $colors[$semester] ?? 'bg-gray-100 text-gray-800';
}

function getStatusColor($status) {
    switch ($status) {
        case 'Aktif':
            return 'bg-green-100 text-green-800';
        case 'Selesai':
            return 'bg-blue-100 text-blue-800';
        case 'Batal':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

function getGradeColor($grade) {
    switch ($grade) {
        case 'A':
            return 'bg-green-100 text-green-800';
        case 'B':
        case 'B+':
            return 'bg-blue-100 text-blue-800';
        case 'C':
        case 'C+':
            return 'bg-yellow-100 text-yellow-800';
        case 'D':
            return 'bg-orange-100 text-orange-800';
        case 'E':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

function validateRequired($fields, $data) {
    $errors = [];
    foreach ($fields as $field) {
        if (empty($data[$field])) {
            $errors[] = ucfirst(str_replace('_', ' ', $field)) . ' harus diisi';
        }
    }
    return $errors;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function sanitizeInput($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}
?>
