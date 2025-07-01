import { neon } from "@neondatabase/serverless"

// Check if DATABASE_URL is available
const isDatabaseConfigured = !!process.env.DATABASE_URL

export const sql = isDatabaseConfigured ? neon(process.env.DATABASE_URL) : null

// Mock data for when database is not configured
const mockStudents = [
  {
    id_mahasiswa: 1,
    nim: "2024001001",
    nama: "Andi Pratama",
    tanggal_lahir: "2005-03-15",
    jenis_kelamin: "L",
    alamat: "Jl. Merdeka No. 123, Jakarta",
    nomor_telepon: "081234567801",
    email: "andi.pratama@student.ac.id",
  },
  {
    id_mahasiswa: 2,
    nim: "2024001002",
    nama: "Sari Dewi",
    tanggal_lahir: "2005-07-22",
    jenis_kelamin: "P",
    alamat: "Jl. Sudirman No. 456, Bandung",
    nomor_telepon: "081234567802",
    email: "sari.dewi@student.ac.id",
  },
  {
    id_mahasiswa: 3,
    nim: "2024001003",
    nama: "Budi Setiawan",
    tanggal_lahir: "2005-01-10",
    jenis_kelamin: "L",
    alamat: "Jl. Thamrin No. 789, Surabaya",
    nomor_telepon: "081234567803",
    email: "budi.setiawan@student.ac.id",
  },
]

const mockCourses = [
  {
    id_matakuliah: 1,
    kode_matakuliah: "IF101",
    nama_matakuliah: "Pengantar Teknologi Informasi",
    sks: 3,
    semester: 1,
    deskripsi: "Mata kuliah pengenalan dasar teknologi informasi dan komputer",
  },
  {
    id_matakuliah: 2,
    kode_matakuliah: "IF102",
    nama_matakuliah: "Algoritma dan Pemrograman",
    sks: 4,
    semester: 1,
    deskripsi: "Mata kuliah dasar algoritma dan pemrograman menggunakan bahasa C++",
  },
  {
    id_matakuliah: 3,
    kode_matakuliah: "IF201",
    nama_matakuliah: "Struktur Data",
    sks: 3,
    semester: 2,
    deskripsi: "Mata kuliah tentang struktur data dan implementasinya",
  },
]

const mockLecturers = [
  {
    id_dosen: 1,
    nidn: "0123456789",
    nama_dosen: "Dr. Ahmad Wijaya",
    gelar: "S.Kom., M.T., Ph.D.",
    email: "ahmad.wijaya@university.ac.id",
    nomor_telepon: "081234567890",
  },
  {
    id_dosen: 2,
    nidn: "0123456790",
    nama_dosen: "Prof. Siti Nurhaliza",
    gelar: "S.Si., M.Sc., Ph.D.",
    email: "siti.nurhaliza@university.ac.id",
    nomor_telepon: "081234567891",
  },
]

const mockClasses = [
  {
    id_kelas: 1,
    nama_matakuliah: "Pengantar Teknologi Informasi",
    kode_matakuliah: "IF101",
    sks: 3,
    nama_dosen: "Dr. Ahmad Wijaya",
    nama_kelas: "A",
    kapasitas: 40,
    tahun_akademik: "2024/2025",
    semester_akademik: "Ganjil",
    tanggal_mulai: "2024-09-01",
    tanggal_selesai: "2024-12-15",
  },
  {
    id_kelas: 2,
    nama_matakuliah: "Algoritma dan Pemrograman",
    kode_matakuliah: "IF102",
    sks: 4,
    nama_dosen: "Prof. Siti Nurhaliza",
    nama_kelas: "A",
    kapasitas: 35,
    tahun_akademik: "2024/2025",
    semester_akademik: "Ganjil",
    tanggal_mulai: "2024-09-01",
    tanggal_selesai: "2024-12-15",
  },
]

const mockKRS = [
  {
    id_krs: 1,
    nim: "2024001001",
    nama_mahasiswa: "Andi Pratama",
    kode_matakuliah: "IF101",
    nama_matakuliah: "Pengantar Teknologi Informasi",
    sks: 3,
    nama_dosen: "Dr. Ahmad Wijaya",
    nama_kelas: "A",
    tahun_akademik: "2024/2025",
    semester_akademik: "Ganjil",
    status_krs: "Aktif",
    tanggal_ambil: "2024-08-15T10:00:00Z",
    nilai_huruf: null,
    nilai_angka: null,
  },
  {
    id_krs: 2,
    nim: "2024001002",
    nama_mahasiswa: "Sari Dewi",
    kode_matakuliah: "IF102",
    nama_matakuliah: "Algoritma dan Pemrograman",
    sks: 4,
    nama_dosen: "Prof. Siti Nurhaliza",
    nama_kelas: "A",
    tahun_akademik: "2024/2025",
    semester_akademik: "Ganjil",
    status_krs: "Aktif",
    tanggal_ambil: "2024-08-16T09:30:00Z",
    nilai_huruf: "A",
    nilai_angka: 4.0,
  },
]

// Database query functions with fallback to mock data
export async function getStudents() {
  if (!isDatabaseConfigured || !sql) {
    return mockStudents
  }

  try {
    return await sql`
      SELECT * FROM mahasiswa 
      ORDER BY nama ASC
    `
  } catch (error) {
    console.warn("Database query failed, using mock data:", error)
    return mockStudents
  }
}

export async function getCourses() {
  if (!isDatabaseConfigured || !sql) {
    return mockCourses
  }

  try {
    return await sql`
      SELECT * FROM mata_kuliah 
      ORDER BY semester ASC, nama_matakuliah ASC
    `
  } catch (error) {
    console.warn("Database query failed, using mock data:", error)
    return mockCourses
  }
}

export async function getLecturers() {
  if (!isDatabaseConfigured || !sql) {
    return mockLecturers
  }

  try {
    return await sql`
      SELECT * FROM dosen 
      ORDER BY nama_dosen ASC
    `
  } catch (error) {
    console.warn("Database query failed, using mock data:", error)
    return mockLecturers
  }
}

export async function getAcademicYears() {
  if (!isDatabaseConfigured || !sql) {
    return [
      {
        id_tahun_akademik: 1,
        tahun_akademik: "2024/2025",
        semester_akademik: "Ganjil",
        status: "Aktif",
      },
    ]
  }

  try {
    return await sql`
      SELECT * FROM tahun_akademik 
      ORDER BY tahun_akademik DESC, semester_akademik ASC
    `
  } catch (error) {
    console.warn("Database query failed, using mock data:", error)
    return [
      {
        id_tahun_akademik: 1,
        tahun_akademik: "2024/2025",
        semester_akademik: "Ganjil",
        status: "Aktif",
      },
    ]
  }
}

export async function getClasses() {
  if (!isDatabaseConfigured || !sql) {
    return mockClasses
  }

  try {
    return await sql`
      SELECT 
        k.*,
        mk.nama_matakuliah,
        mk.kode_matakuliah,
        mk.sks,
        d.nama_dosen,
        ta.tahun_akademik,
        ta.semester_akademik
      FROM kelas k
      JOIN mata_kuliah mk ON k.id_matakuliah = mk.id_matakuliah
      JOIN dosen d ON k.id_dosen = d.id_dosen
      JOIN tahun_akademik ta ON k.id_tahun_akademik = ta.id_tahun_akademik
      ORDER BY ta.tahun_akademik DESC, mk.nama_matakuliah ASC
    `
  } catch (error) {
    console.warn("Database query failed, using mock data:", error)
    return mockClasses
  }
}

export async function getKRSEntries() {
  if (!isDatabaseConfigured || !sql) {
    return mockKRS
  }

  try {
    return await sql`
      SELECT 
        krs.*,
        m.nim,
        m.nama as nama_mahasiswa,
        mk.kode_matakuliah,
        mk.nama_matakuliah,
        mk.sks,
        d.nama_dosen,
        k.nama_kelas,
        ta.tahun_akademik,
        ta.semester_akademik
      FROM krs
      JOIN mahasiswa m ON krs.id_mahasiswa = m.id_mahasiswa
      JOIN kelas k ON krs.id_kelas = k.id_kelas
      JOIN mata_kuliah mk ON k.id_matakuliah = mk.id_matakuliah
      JOIN dosen d ON k.id_dosen = d.id_dosen
      JOIN tahun_akademik ta ON k.id_tahun_akademik = ta.id_tahun_akademik
      ORDER BY ta.tahun_akademik DESC, m.nama ASC
    `
  } catch (error) {
    console.warn("Database query failed, using mock data:", error)
    return mockKRS
  }
}

export async function getDashboardStats() {
  if (!isDatabaseConfigured || !sql) {
    return {
      totalStudents: mockStudents.length,
      totalCourses: mockCourses.length,
      totalLecturers: mockLecturers.length,
      activeRegistrations: mockKRS.filter((krs) => krs.status_krs === "Aktif").length,
    }
  }

  try {
    const [students, courses, lecturers, activeKRS] = await Promise.all([
      sql`SELECT COUNT(*) as count FROM mahasiswa`,
      sql`SELECT COUNT(*) as count FROM mata_kuliah`,
      sql`SELECT COUNT(*) as count FROM dosen`,
      sql`SELECT COUNT(*) as count FROM krs WHERE status_krs = 'Aktif'`,
    ])

    return {
      totalStudents: students[0].count,
      totalCourses: courses[0].count,
      totalLecturers: lecturers[0].count,
      activeRegistrations: activeKRS[0].count,
    }
  } catch (error) {
    console.warn("Database query failed, using mock data:", error)
    return {
      totalStudents: mockStudents.length,
      totalCourses: mockCourses.length,
      totalLecturers: mockLecturers.length,
      activeRegistrations: mockKRS.filter((krs) => krs.status_krs === "Aktif").length,
    }
  }
}

// Helper function to check database status
export function getDatabaseStatus() {
  return {
    configured: isDatabaseConfigured,
    connected: isDatabaseConfigured && !!sql,
  }
}
