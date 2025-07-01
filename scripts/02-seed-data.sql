-- Seed data for KRS System
-- Insert demo users first
INSERT INTO users (username, email, password, full_name, role, created_at) VALUES
('admin', 'admin@skyuniversity.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin', NOW()),
('student', 'andi.pratama@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Andi Pratama', 'student', NOW()),
('lecturer', 'ahmad.wijaya@university.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dr. Ahmad Wijaya', 'lecturer', NOW()),
('maria', 'diana@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Maria Diana Heko', 'student', NOW()),
('dirga', 'dirga@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Dirgayusa', 'student', NOW()),
('ayu', 'Ayu@student.ac.id', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Ayu Citra', 'student', NOW());

-- Insert Academic Years
INSERT INTO tahun_akademik (tahun_akademik, semester_akademik, status) VALUES
('2024/2025', 'Ganjil', 'Aktif'),
('2024/2025', 'Genap', 'Tidak Aktif'),
('2023/2024', 'Genap', 'Tidak Aktif');

-- Insert Courses
INSERT INTO mata_kuliah (kode_matakuliah, nama_matakuliah, sks, semester, deskripsi) VALUES
('IF101', 'Pengantar Teknologi Informasi', 3, 1, 'Mata kuliah pengenalan dasar teknologi informasi dan komputer'),
('IF102', 'Algoritma dan Pemrograman', 4, 1, 'Mata kuliah dasar algoritma dan pemrograman menggunakan bahasa C++'),
('IF201', 'Struktur Data', 3, 2, 'Mata kuliah tentang struktur data dan implementasinya'),
('IF202', 'Basis Data', 3, 2, 'Mata kuliah tentang konsep dan implementasi basis data'),
('IF301', 'Rekayasa Perangkat Lunak', 3, 3, 'Mata kuliah tentang metodologi pengembangan perangkat lunak'),
('IF302', 'Jaringan Komputer', 3, 3, 'Mata kuliah tentang konsep dan implementasi jaringan komputer');

-- Insert Lecturers
INSERT INTO dosen (nidn, nama_dosen, gelar, email, nomor_telepon, user_id) VALUES
('0123456789', 'Dr. Ahmad Wijaya', 'S.Kom., M.T., Ph.D.', 'ahmad.wijaya@university.ac.id', '081234567890', 3),
('0123456790', 'Prof. Siti Nurhaliza', 'S.Si., M.Sc., Ph.D.', 'siti.nurhaliza@university.ac.id', '081234567891', NULL),
('0123456791', 'Dr. Budi Santoso', 'S.T., M.Kom., Ph.D.', 'budi.santoso@university.ac.id', '081234567892', NULL);

-- Insert Students (matching your existing data)
INSERT INTO mahasiswa (nim, nama, tanggal_lahir, jenis_kelamin, alamat, nomor_telepon, email, user_id) VALUES
('2024001001', 'Maria Diana Heko', '2005-03-15', 'P', 'Jl. Merdeka No. 123, Jimbaran', '081234567801', 'diana@student.ac.id', 4),
('2024001002', 'Dirgayusa', '2005-07-22', 'L', 'Jl. Sudirman No. 456, Badung', '081234567802', 'dirga@student.ac.id', 5),
('2024001003', 'Ayu Citra', '2005-01-10', 'P', 'Jl. Thamrin No. 789, Denpasar', '081234567803', 'Ayu@student.ac.id', 6),
('2024001004', 'Zidan Rifki Saputra', '2005-09-05', 'L', 'Jl. Gatot Subroto No. 321, Tabanan', '081234567804', 'zidan@student.ac.id', NULL),
('2024001005', 'Prasetyo', '2005-12-18', 'L', 'Jl. Ahmad Yani No. 654, Medan', '081234567805', 'tyo@student.ac.id', NULL),
('2024001006', 'Naro Gabriel', '2005-04-30', 'P', 'Jl. Diponegoro No. 987, Semarang', '081234567806', 'naro@student.ac.id', NULL);

-- Insert Classes
INSERT INTO kelas (id_matakuliah, id_dosen, id_tahun_akademik, nama_kelas, kapasitas, tanggal_mulai, tanggal_selesai) VALUES
(1, 1, 1, 'A', 40, '2024-09-01', '2024-12-15'),
(1, 1, 1, 'B', 40, '2024-09-01', '2024-12-15'),
(2, 2, 1, 'A', 35, '2024-09-01', '2024-12-15'),
(3, 3, 1, 'A', 30, '2024-09-01', '2024-12-15'),
(4, 1, 1, 'A', 35, '2024-09-01', '2024-12-15');

-- Insert KRS entries
INSERT INTO krs (id_mahasiswa, id_kelas, tanggal_ambil, status_krs) VALUES
(1, 1, '2024-08-15 10:00:00', 'Aktif'),
(1, 3, '2024-08-15 10:15:00', 'Aktif'),
(2, 2, '2024-08-16 09:30:00', 'Aktif'),
(2, 3, '2024-08-16 09:45:00', 'Aktif'),
(3, 1, '2024-08-17 11:00:00', 'Aktif'),
(4, 2, '2024-08-18 14:30:00', 'Aktif'),
(5, 4, '2024-08-19 08:15:00', 'Aktif'),
(6, 1, '2024-08-20 13:45:00', 'Aktif');
