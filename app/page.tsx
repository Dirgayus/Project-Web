import { Card, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { getDashboardStats, getDatabaseStatus } from "@/lib/db"
import { Database, AlertTriangle } from "lucide-react"

export default async function Dashboard() {
  const stats = await getDashboardStats()
  const dbStatus = getDatabaseStatus()

  const tabs = [
    { name: "Beranda", active: true },
    { name: "Mata Kuliah", active: false },
    { name: "Dosen", active: false },
    { name: "KRS", active: false },
  ]

  return (
    <div className="sky-fade-in">
      <div className="sky-page-header">
        <h1 className="sky-page-title">Dashboard</h1>
        <div className="sky-tabs">
          {tabs.map((tab) => (
            <div key={tab.name} className={`sky-tab ${tab.active ? "active" : ""}`}>
              {tab.name}
            </div>
          ))}
        </div>
      </div>

      <div className="sky-page-subtitle mb-6">Selamat datang di dashboard Sky University.</div>

      {!dbStatus.configured && (
        <Card className="border-yellow-200 bg-yellow-50 mb-6">
          <CardHeader>
            <CardTitle className="text-yellow-800 flex items-center">
              <AlertTriangle className="h-5 w-5 mr-2" />
              Mode Demo
            </CardTitle>
            <CardDescription className="text-yellow-700">
              Sistem sedang berjalan dalam mode demo dengan data sampel. Untuk menggunakan data real, silakan
              konfigurasi koneksi database Neon.
            </CardDescription>
          </CardHeader>
        </Card>
      )}

      <div className="sky-stats-grid">
        <div className="sky-stat-card">
          <div className="sky-stat-number">{stats.totalStudents}</div>
          <div className="sky-stat-label">Total Mahasiswa</div>
        </div>
        <div className="sky-stat-card">
          <div className="sky-stat-number">{stats.totalCourses}</div>
          <div className="sky-stat-label">Mata Kuliah</div>
        </div>
        <div className="sky-stat-card">
          <div className="sky-stat-number">{stats.totalLecturers}</div>
          <div className="sky-stat-label">Dosen</div>
        </div>
        <div className="sky-stat-card">
          <div className="sky-stat-number">{stats.activeRegistrations}</div>
          <div className="sky-stat-label">KRS Aktif</div>
        </div>
      </div>

      <div className="grid gap-6 md:grid-cols-2">
        <div className="sky-card">
          <h3 className="text-lg font-semibold mb-4">Fitur Sistem</h3>
          <ul className="space-y-2 text-sm text-muted-foreground">
            <li>• Manajemen data mahasiswa</li>
            <li>• Pengelolaan mata kuliah dan kurikulum</li>
            <li>• Tracking penugasan dosen</li>
            <li>• Manajemen jadwal dan kapasitas kelas</li>
            <li>• Pemrosesan Kartu Rencana Studi</li>
            <li>• Manajemen tahun dan semester akademik</li>
          </ul>
        </div>

        <div className="sky-card">
          <h3 className="text-lg font-semibold mb-4">Status Sistem</h3>
          <div className="space-y-3">
            <div className="flex items-center justify-between">
              <span className="text-sm font-medium">Status Database</span>
              <div className="flex items-center space-x-2">
                <Database className={`h-4 w-4 ${dbStatus.configured ? "text-green-600" : "text-yellow-600"}`} />
                <span className={`text-sm ${dbStatus.configured ? "text-green-600" : "text-yellow-600"}`}>
                  {dbStatus.configured ? "Terhubung" : "Mode Demo"}
                </span>
              </div>
            </div>
            <div className="flex items-center justify-between">
              <span className="text-sm font-medium">Sumber Data</span>
              <span className="text-sm text-muted-foreground">
                {dbStatus.configured ? "Neon Database" : "Data Sampel"}
              </span>
            </div>
            <div className="flex items-center justify-between">
              <span className="text-sm font-medium">Versi Sistem</span>
              <span className="text-sm text-muted-foreground">v1.0.0</span>
            </div>
          </div>
        </div>
      </div>

      <div className="sky-footer mt-12">Copyright © Sky University 2025/2026</div>
    </div>
  )
}
