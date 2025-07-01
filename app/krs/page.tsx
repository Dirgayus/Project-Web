import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { getKRSEntries } from "@/lib/db"
import { ClipboardList, User, UserCheck, Calendar, Hash, Award } from "lucide-react"

export default async function KRSPage() {
  const krsEntries = await getKRSEntries()

  const getStatusColor = (status: string) => {
    switch (status) {
      case "Aktif":
        return "bg-green-100 text-green-800"
      case "Selesai":
        return "bg-blue-100 text-blue-800"
      case "Batal":
        return "bg-red-100 text-red-800"
      default:
        return "bg-gray-100 text-gray-800"
    }
  }

  const getGradeColor = (grade: string) => {
    switch (grade) {
      case "A":
        return "bg-green-100 text-green-800"
      case "B":
      case "B+":
        return "bg-blue-100 text-blue-800"
      case "C":
      case "C+":
        return "bg-yellow-100 text-yellow-800"
      case "D":
        return "bg-orange-100 text-orange-800"
      case "E":
        return "bg-red-100 text-red-800"
      default:
        return "bg-gray-100 text-gray-800"
    }
  }

  return (
    <div className="flex-1 space-y-4 p-4 md:p-8 pt-6">
      <div className="flex items-center justify-between space-y-2">
        <h2 className="text-3xl font-bold tracking-tight">KRS Management</h2>
        <Badge variant="secondary">{krsEntries.length} Total Registrations</Badge>
      </div>
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {krsEntries.map((krs: any) => (
          <Card key={krs.id_krs} className="hover:shadow-md transition-shadow">
            <CardHeader>
              <div className="flex items-start justify-between">
                <div className="flex items-center space-x-2">
                  <ClipboardList className="h-5 w-5 text-indigo-600" />
                  <div>
                    <CardTitle className="text-lg">{krs.nama_matakuliah}</CardTitle>
                    <CardDescription>
                      {krs.kode_matakuliah} - Class {krs.nama_kelas}
                    </CardDescription>
                  </div>
                </div>
                <Badge className={getStatusColor(krs.status_krs)}>{krs.status_krs}</Badge>
              </div>
            </CardHeader>
            <CardContent className="space-y-3">
              <div className="flex items-center space-x-2 text-sm">
                <User className="h-4 w-4 text-muted-foreground" />
                <span>{krs.nama_mahasiswa}</span>
                <Badge variant="outline" className="ml-auto">
                  {krs.nim}
                </Badge>
              </div>
              <div className="flex items-center space-x-2 text-sm">
                <UserCheck className="h-4 w-4 text-muted-foreground" />
                <span>{krs.nama_dosen}</span>
              </div>
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-2 text-sm">
                  <Hash className="h-4 w-4 text-muted-foreground" />
                  <span>{krs.sks} SKS</span>
                </div>
                {krs.nilai_huruf && <Badge className={getGradeColor(krs.nilai_huruf)}>Grade: {krs.nilai_huruf}</Badge>}
              </div>
              <div className="flex items-center space-x-2 text-sm">
                <Calendar className="h-4 w-4 text-muted-foreground" />
                <span className="text-muted-foreground">
                  {krs.tahun_akademik} - {krs.semester_akademik}
                </span>
              </div>
              {krs.nilai_angka && (
                <div className="flex items-center space-x-2 text-sm">
                  <Award className="h-4 w-4 text-muted-foreground" />
                  <span className="text-muted-foreground">Score: {krs.nilai_angka}/4.00</span>
                </div>
              )}
              <div className="text-xs text-muted-foreground">
                Registered: {new Date(krs.tanggal_ambil).toLocaleDateString("id-ID")}
              </div>
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  )
}
