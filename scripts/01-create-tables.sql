-- Create database tables for KRS System
-- Master Data Tables

-- Table: users (Authentication)
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'lecturer', 'student') NOT NULL DEFAULT 'student',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Table: mahasiswa (Students)
CREATE TABLE IF NOT EXISTS mahasiswa (
    id_mahasiswa SERIAL PRIMARY KEY,
    nim VARCHAR(20) UNIQUE NOT NULL,
    nama VARCHAR(100) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    jenis_kelamin VARCHAR(1) CHECK (jenis_kelamin IN ('L', 'P')) NOT NULL,
    alamat VARCHAR(255),
    nomor_telepon VARCHAR(15),
    email VARCHAR(100) UNIQUE,
    foto VARCHAR(255),
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: mata_kuliah (Courses)
CREATE TABLE IF NOT EXISTS mata_kuliah (
    id_matakuliah SERIAL PRIMARY KEY,
    kode_matakuliah VARCHAR(10) UNIQUE NOT NULL,
    nama_matakuliah VARCHAR(100) NOT NULL,
    sks INTEGER NOT NULL CHECK (sks > 0),
    semester INTEGER NOT NULL CHECK (semester > 0),
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: dosen (Lecturers)
CREATE TABLE IF NOT EXISTS dosen (
    id_dosen SERIAL PRIMARY KEY,
    nidn VARCHAR(20) UNIQUE NOT NULL,
    nama_dosen VARCHAR(100) NOT NULL,
    gelar VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    nomor_telepon VARCHAR(15),
    user_id INTEGER REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: tahun_akademik (Academic Year)
CREATE TABLE IF NOT EXISTS tahun_akademik (
    id_tahun_akademik SERIAL PRIMARY KEY,
    tahun_akademik VARCHAR(9) NOT NULL,
    semester_akademik VARCHAR(10) CHECK (semester_akademik IN ('Ganjil', 'Genap', 'Pendek')) NOT NULL,
    status VARCHAR(15) CHECK (status IN ('Aktif', 'Tidak Aktif')) DEFAULT 'Tidak Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Transaction Tables

-- Table: kelas (Classes)
CREATE TABLE IF NOT EXISTS kelas (
    id_kelas SERIAL PRIMARY KEY,
    id_matakuliah INTEGER REFERENCES mata_kuliah(id_matakuliah) ON DELETE CASCADE,
    id_dosen INTEGER REFERENCES dosen(id_dosen) ON DELETE CASCADE,
    id_tahun_akademik INTEGER REFERENCES tahun_akademik(id_tahun_akademik) ON DELETE CASCADE,
    nama_kelas VARCHAR(50) NOT NULL,
    kapasitas INTEGER NOT NULL CHECK (kapasitas > 0),
    tanggal_mulai DATE,
    tanggal_selesai DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table: krs (Course Registration Cards)
CREATE TABLE IF NOT EXISTS krs (
    id_krs SERIAL PRIMARY KEY,
    id_mahasiswa INTEGER REFERENCES mahasiswa(id_mahasiswa) ON DELETE CASCADE,
    id_kelas INTEGER REFERENCES kelas(id_kelas) ON DELETE CASCADE,
    tanggal_ambil TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    nilai_angka DECIMAL(4,2) CHECK (nilai_angka >= 0 AND nilai_angka <= 4.0),
    nilai_huruf VARCHAR(2),
    status_krs VARCHAR(10) CHECK (status_krs IN ('Aktif', 'Selesai', 'Batal')) DEFAULT 'Aktif',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE(id_mahasiswa, id_kelas)
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_users_username ON users(username);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);
CREATE INDEX IF NOT EXISTS idx_mahasiswa_nim ON mahasiswa(nim);
CREATE INDEX IF NOT EXISTS idx_mahasiswa_user_id ON mahasiswa(user_id);
CREATE INDEX IF NOT EXISTS idx_mata_kuliah_kode ON mata_kuliah(kode_matakuliah);
CREATE INDEX IF NOT EXISTS idx_dosen_nidn ON dosen(nidn);
CREATE INDEX IF NOT EXISTS idx_dosen_user_id ON dosen(user_id);
CREATE INDEX IF NOT EXISTS idx_krs_mahasiswa ON krs(id_mahasiswa);
CREATE INDEX IF NOT EXISTS idx_krs_kelas ON krs(id_kelas);
CREATE INDEX IF NOT EXISTS idx_kelas_tahun_akademik ON kelas(id_tahun_akademik);
