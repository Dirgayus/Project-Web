-- Sky University KRS Management System Database
-- MySQL Database Schema

CREATE DATABASE IF NOT EXISTS krs_system;
USE krs_system;

-- Master Data Tables

-- Table: mahasiswa (Students)
CREATE TABLE IF NOT EXISTS mahasiswa (
    id_mahasiswa INT(11) AUTO_INCREMENT PRIMARY KEY,
    nim VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    alamat VARCHAR(255),
    nomor_telepon VARCHAR(15),
    email VARCHAR(100) UNIQUE,
    foto VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: mata_kuliah (Courses)
CREATE TABLE IF NOT EXISTS mata_kuliah (
    id_matakuliah INT(11) AUTO_INCREMENT PRIMARY KEY,
    kode_matakuliah VARCHAR(10) UNIQUE NOT NULL,
    nama_matakuliah VARCHAR(100) NOT NULL,
    sks INT(2) NOT NULL CHECK (sks > 0),
    semester INT(2) NOT NULL CHECK (semester > 0),
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: dosen (Lecturers)
CREATE TABLE IF NOT EXISTS dosen (
    id_dosen INT(11) AUTO_INCREMENT PRIMARY KEY,
    nidn VARCHAR(20) UNIQUE NOT NULL,
    nama_dosen VARCHAR(100) NOT NULL,
    gelar VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    nomor_telepon VARCHAR(15),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: tahun_akademik (Academic Year)
CREATE TABLE IF NOT EXISTS tahun_akademik (
    id_tahun_akademik INT(11) AUTO_INCREMENT PRIMARY KEY,
    tahun_akademik VARCHAR(9) NOT NULL,
    semester_akademik ENUM('Ganjil', 'Genap', 'Pendek') NOT NULL,
    status ENUM('Aktif', 'Tidak Aktif') DEFAULT 'Tidak Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Transaction Tables

-- Table: kelas (Classes)
CREATE TABLE IF NOT EXISTS kelas (
    id_kelas INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_matakuliah INT(11) NOT NULL,
    id_dosen INT(11) NOT NULL,
    id_tahun_akademik INT(11) NOT NULL,
    nama_kelas VARCHAR(50) NOT NULL,
    kapasitas INT(4) NOT NULL CHECK (kapasitas > 0),
    tanggal_mulai DATE,
    tanggal_selesai DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_matakuliah) REFERENCES mata_kuliah(id_matakuliah) ON DELETE CASCADE,
    FOREIGN KEY (id_dosen) REFERENCES dosen(id_dosen) ON DELETE CASCADE,
    FOREIGN KEY (id_tahun_akademik) REFERENCES tahun_akademik(id_tahun_akademik) ON DELETE CASCADE
);

-- Table: krs (Course Registration Cards)
CREATE TABLE IF NOT EXISTS krs (
    id_krs INT(11) AUTO_INCREMENT PRIMARY KEY,
    id_mahasiswa INT(11) NOT NULL,
    id_kelas INT(11) NOT NULL,
    tanggal_ambil DATETIME DEFAULT CURRENT_TIMESTAMP,
    nilai_angka DECIMAL(4,2) CHECK (nilai_angka >= 0 AND nilai_angka <= 4.0),
    nilai_huruf VARCHAR(2),
    status_krs ENUM('Aktif', 'Selesai', 'Batal') DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (id_mahasiswa) REFERENCES mahasiswa(id_mahasiswa) ON DELETE CASCADE,
    FOREIGN KEY (id_kelas) REFERENCES kelas(id_kelas) ON DELETE CASCADE,
    UNIQUE KEY unique_mahasiswa_kelas (id_mahasiswa, id_kelas)
);

-- Add users table for authentication
CREATE TABLE IF NOT EXISTS users (
    id INT(11) AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'lecturer', 'student') DEFAULT 'student',
    last_login DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample users
INSERT INTO users (username, email, password, full_name, role) VALUES
('admin', 'admin@skyuniversity.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin'),
('student', 'andi.pratama@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Andi Pratama', 'student'),
('lecturer', 'ahmad.wijaya@university.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Ahmad Wijaya', 'lecturer');

-- Create index for better performance
CREATE INDEX idx_users_username ON users(username);
CREATE INDEX idx_users_email ON users(email);

-- Create indexes for better performance
CREATE INDEX idx_mahasiswa_nim ON mahasiswa(nim);
CREATE INDEX idx_mata_kuliah_kode ON mata_kuliah(kode_matakuliah);
CREATE INDEX idx_dosen_nidn ON dosen(nidn);
CREATE INDEX idx_krs_mahasiswa ON krs(id_mahasiswa);
CREATE INDEX idx_krs_kelas ON krs(id_kelas);
CREATE INDEX idx_kelas_tahun_akademik ON kelas(id_tahun_akademik);

-- Insert sample data
INSERT INTO tahun_akademik (tahun_akademik, semester_akademik, status) VALUES
('2023/2024', 'Ganjil', 'Tidak Aktif'),
('2023/2024', 'Genap', 'Tidak Aktif'),
('2024/2025', 'Ganjil', 'Aktif'),
('2024/2025', 'Genap', 'Tidak Aktif');

INSERT INTO dosen (nidn, nama_dosen, gelar, email, nomor_telepon) VALUES
('0123456789', 'Dr. Ahmad Wijaya', 'S.Kom., M.T., Ph.D.', 'ahmad.wijaya@university.ac.id', '081234567890'),
('0123456790', 'Prof. Siti Nurhaliza', 'S.Si., M.Sc., Ph.D.', 'siti.nurhaliza@university.ac.id', '081234567891'),
('0123456791', 'Dr. Budi Santoso', 'S.T., M.T., Ph.D.', 'budi.santoso@university.ac.id', '081234567892'),
('0123456792', 'Dr. Maya Sari', 'S.Mat., M.Si., Ph.D.', 'maya.sari@university.ac.id', '081234567893'),
('0123456793', 'Prof. Andi Rahman', 'S.Kom., M.T., Ph.D.', 'andi.rahman@university.ac.id', '081234567894');

INSERT INTO mata_kuliah (kode_matakuliah, nama_matakuliah, sks, semester, deskripsi) VALUES
('IF101', 'Pengantar Teknologi Informasi', 3, 1, 'Mata kuliah pengenalan dasar teknologi informasi dan komputer'),
('IF102', 'Algoritma dan Pemrograman', 4, 1, 'Mata kuliah dasar algoritma dan pemrograman menggunakan bahasa C++'),
('IF201', 'Struktur Data', 3, 2, 'Mata kuliah tentang struktur data dan implementasinya'),
('IF202', 'Basis Data', 3, 2, 'Mata kuliah tentang konsep dan implementasi basis data'),
('IF301', 'Rekayasa Perangkat Lunak', 3, 3, 'Mata kuliah tentang metodologi pengembangan perangkat lunak'),
('IF302', 'Jaringan Komputer', 3, 3, 'Mata kuliah tentang konsep dan implementasi jaringan komputer'),
('MT101', 'Kalkulus I', 3, 1, 'Mata kuliah matematika dasar kalkulus diferensial'),
('MT102', 'Aljabar Linear', 3, 1, 'Mata kuliah tentang konsep aljabar linear dan matriks'),
('MT201', 'Kalkulus II', 3, 2, 'Mata kuliah matematika lanjutan kalkulus integral'),
('MT202', 'Statistika', 3, 2, 'Mata kuliah tentang konsep dasar statistika dan probabilitas');

INSERT INTO mahasiswa (nim, nama, tanggal_lahir, jenis_kelamin, alamat, nomor_telepon, email) VALUES
('2024001001', 'Andi Pratama', '2005-03-15', 'L', 'Jl. Merdeka No. 123, Jakarta', '081234567801', 'andi.pratama@student.ac.id'),
('2024001002', 'Sari Dewi', '2005-07-22', 'P', 'Jl. Sudirman No. 456, Bandung', '081234567802', 'sari.dewi@student.ac.id'),
('2024001003', 'Budi Setiawan', '2005-01-10', 'L', 'Jl. Thamrin No. 789, Surabaya', '081234567803', 'budi.setiawan@student.ac.id'),
('2024001004', 'Maya Putri', '2005-09-05', 'P', 'Jl. Gatot Subroto No. 321, Yogyakarta', '081234567804', 'maya.putri@student.ac.id'),
('2024001005', 'Rizki Ramadhan', '2005-12-18', 'L', 'Jl. Ahmad Yani No. 654, Medan', '081234567805', 'rizki.ramadhan@student.ac.id'),
('2024001006', 'Fitri Handayani', '2005-04-30', 'P', 'Jl. Diponegoro No. 987, Semarang', '081234567806', 'fitri.handayani@student.ac.id');

INSERT INTO kelas (id_matakuliah, id_dosen, id_tahun_akademik, nama_kelas, kapasitas, tanggal_mulai, tanggal_selesai) VALUES
(1, 1, 3, 'A', 40, '2024-09-01', '2024-12-15'),
(2, 2, 3, 'A', 35, '2024-09-01', '2024-12-15'),
(7, 5, 3, 'A', 45, '2024-09-01', '2024-12-15'),
(8, 4, 3, 'A', 40, '2024-09-01', '2024-12-15');

INSERT INTO krs (id_mahasiswa, id_kelas, tanggal_ambil, status_krs) VALUES
(1, 1, '2024-08-15 10:00:00', 'Aktif'),
(1, 2, '2024-08-15 10:05:00', 'Aktif'),
(2, 1, '2024-08-16 09:30:00', 'Aktif'),
(2, 3, '2024-08-16 09:35:00', 'Aktif');
