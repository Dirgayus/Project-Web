<?php
class Database {
    // Sesuaikan dengan konfigurasi database Anda
    private $host = 'localhost';
    private $db_name = 'krs_system';  // Nama database Anda
    private $username = 'root';       // Username MySQL Anda
    private $password = '';           // Password MySQL Anda (kosong jika default XAMPP)
    private $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, 
                                $this->username, $this->password);
            $this->conn->exec("set names utf8");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            // If database connection fails, we'll use demo data
            return null;
        }
        return $this->conn;
    }

    public function isConnected() {
        return $this->conn !== null;
    }
}

// Demo data for when database is not available
class DemoData {
    public static function getStudents() {
        return [
            [
                'id_mahasiswa' => 1,
                'nim' => '2024001001',
                'nama' => 'Maria Diana Heko',
                'tanggal_lahir' => '2005-03-15',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Merdeka No. 123, Jimbaran',
                'nomor_telepon' => '081234567801',
                'email' => 'diana@student.ac.id'
            ],
            [
                'id_mahasiswa' => 2,
                'nim' => '2024001002',
                'nama' => 'Dirgayusa',
                'tanggal_lahir' => '2005-07-22',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Sudirman No. 456, Badung',
                'nomor_telepon' => '081234567802',
                'email' => 'dirga@student.ac.id'
            ],
            [
                'id_mahasiswa' => 3,
                'nim' => '2024001003',
                'nama' => 'Ayu Citra',
                'tanggal_lahir' => '2005-01-10',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Thamrin No. 789, Denpasar',
                'nomor_telepon' => '081234567803',
                'email' => 'Ayu@student.ac.id'
            ],
            [
                'id_mahasiswa' => 4,
                'nim' => '2024001004',
                'nama' => 'Zidan Rifki Saputra',
                'tanggal_lahir' => '2005-09-05',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Gatot Subroto No. 321, Tabanan',
                'nomor_telepon' => '081234567804',
                'email' => 'zidan@student.ac.id'
            ],
            [
                'id_mahasiswa' => 5,
                'nim' => '2024001005',
                'nama' => 'Prasetyo',
                'tanggal_lahir' => '2005-12-18',
                'jenis_kelamin' => 'L',
                'alamat' => 'Jl. Ahmad Yani No. 654, Medan',
                'nomor_telepon' => '081234567805',
                'email' => 'tyo@student.ac.id'
            ],
            [
                'id_mahasiswa' => 6,
                'nim' => '2024001006',
                'nama' => 'Naro Gabriel',
                'tanggal_lahir' => '2005-04-30',
                'jenis_kelamin' => 'P',
                'alamat' => 'Jl. Diponegoro No. 987, Semarang',
                'nomor_telepon' => '081234567806',
                'email' => 'naro@student.ac.id'
            ]
        ];
    }

    public static function getCourses() {
        return [
            [
                'id_matakuliah' => 1,
                'kode_matakuliah' => 'IF101',
                'nama_matakuliah' => 'Pengantar Teknologi Informasi',
                'sks' => 3,
                'semester' => 1,
                'deskripsi' => 'Mata kuliah pengenalan dasar teknologi informasi dan komputer'
            ],
            [
                'id_matakuliah' => 2,
                'kode_matakuliah' => 'IF102',
                'nama_matakuliah' => 'Algoritma dan Pemrograman',
                'sks' => 4,
                'semester' => 1,
                'deskripsi' => 'Mata kuliah dasar algoritma dan pemrograman menggunakan bahasa C++'
            ],
            [
                'id_matakuliah' => 3,
                'kode_matakuliah' => 'IF201',
                'nama_matakuliah' => 'Struktur Data',
                'sks' => 3,
                'semester' => 2,
                'deskripsi' => 'Mata kuliah tentang struktur data dan implementasinya'
            ]
        ];
    }

    public static function getLecturers() {
        return [
            [
                'id_dosen' => 1,
                'nidn' => '0123456789',
                'nama_dosen' => 'Dr. Ahmad Wijaya',
                'gelar' => 'S.Kom., M.T., Ph.D.',
                'email' => 'ahmad.wijaya@university.ac.id',
                'nomor_telepon' => '081234567890'
            ],
            [
                'id_dosen' => 2,
                'nidn' => '0123456790',
                'nama_dosen' => 'Prof. Siti Nurhaliza',
                'gelar' => 'S.Si., M.Sc., Ph.D.',
                'email' => 'siti.nurhaliza@university.ac.id',
                'nomor_telepon' => '081234567891'
            ]
        ];
    }

    public static function getClasses() {
        return [
            [
                'id_kelas' => 1,
                'nama_matakuliah' => 'Pengantar Teknologi Informasi',
                'kode_matakuliah' => 'IF101',
                'sks' => 3,
                'nama_dosen' => 'Dr. Ahmad Wijaya',
                'nama_kelas' => 'A',
                'kapasitas' => 40,
                'tahun_akademik' => '2024/2025',
                'semester_akademik' => 'Ganjil',
                'tanggal_mulai' => '2024-09-01',
                'tanggal_selesai' => '2024-12-15'
            ],
            [
                'id_kelas' => 2,
                'nama_matakuliah' => 'Algoritma dan Pemrograman',
                'kode_matakuliah' => 'IF102',
                'sks' => 4,
                'nama_dosen' => 'Prof. Siti Nurhaliza',
                'nama_kelas' => 'A',
                'kapasitas' => 35,
                'tahun_akademik' => '2024/2025',
                'semester_akademik' => 'Ganjil',
                'tanggal_mulai' => '2024-09-01',
                'tanggal_selesai' => '2024-12-15'
            ]
        ];
    }

    public static function getKRS() {
        return [
            [
                'id_krs' => 1,
                'nim' => '2024001001',
                'nama_mahasiswa' => 'Maria Diana Heko',
                'kode_matakuliah' => 'IF101',
                'nama_matakuliah' => 'Pengantar Teknologi Informasi',
                'sks' => 3,
                'nama_dosen' => 'Dr. Ahmad Wijaya',
                'nama_kelas' => 'A',
                'tahun_akademik' => '2024/2025',
                'semester_akademik' => 'Ganjil',
                'status_krs' => 'Aktif',
                'tanggal_ambil' => '2024-08-15 10:00:00',
                'nilai_huruf' => null,
                'nilai_angka' => null
            ],
            [
                'id_krs' => 2,
                'nim' => '2024001002',
                'nama_mahasiswa' => 'Dirgayusa',
                'kode_matakuliah' => 'IF102',
                'nama_matakuliah' => 'Algoritma dan Pemrograman',
                'sks' => 4,
                'nama_dosen' => 'Prof. Siti Nurhaliza',
                'nama_kelas' => 'A',
                'tahun_akademik' => '2024/2025',
                'semester_akademik' => 'Ganjil',
                'status_krs' => 'Aktif',
                'tanggal_ambil' => '2024-08-16 09:30:00',
                'nilai_huruf' => 'A',
                'nilai_angka' => 4.0
            ]
        ];
    }

    public static function getDashboardStats() {
        return [
            'totalStudents' => 6,
            'totalCourses' => count(self::getCourses()),
            'totalLecturers' => count(self::getLecturers()),
            'activeRegistrations' => count(array_filter(self::getKRS(), function($krs) {
                return $krs['status_krs'] === 'Aktif';
            }))
        ];
    }
}
?>
