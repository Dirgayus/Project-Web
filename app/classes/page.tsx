import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { getClasses } from "@/lib/db"
import { Calendar, Users, UserCheck, Hash, Clock } from "lucide-react"

export default async function ClassesPage() {
  const classes = await getClasses()

  const getSemesterColor = (semester: string) => {
    switch (semester) {
      case "Ganjil":
        return "bg-blue-100 text-blue-800"
      case "Genap":
        return "bg-green-100 text-green-800"
      case "Pendek":
        return "bg-yellow-100 text-yellow-800"
      default:
        return "bg-gray-100 text-gray-800"
    }
  }

  return (
    <div className="flex-1 space-y-4 p-4 md:p-8 pt-6">
      <div className="flex items-center justify-between space-y-2">
        <h2 className="text-3xl font-bold tracking-tight">Classes</h2>
        <Badge variant="secondary">{classes.length} Total Classes</Badge>
      </div>
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {classes.map((classItem: any) => (
          <Card key={classItem.id_kelas} className="hover:shadow-md transition-shadow">
            <CardHeader>
              <div className="flex items-start justify-between">
                <div className="flex items-center space-x-2">
                  <Calendar className="h-5 w-5 text-orange-600" />
                  <div>
                    <CardTitle className="text-lg">
                      {classItem.nama_matakuliah} - {classItem.nama_kelas}
                    </CardTitle>
                    <CardDescription>{classItem.kode_matakuliah}</CardDescription>
                  </div>
                </div>
                <Badge className={getSemesterColor(classItem.semester_akademik)}>{classItem.semester_akademik}</Badge>
              </div>
            </CardHeader>
            <CardContent className="space-y-3">
              <div className="flex items-center space-x-2 text-sm">
                <UserCheck className="h-4 w-4 text-muted-foreground" />
                <span>{classItem.nama_dosen}</span>
              </div>
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-2 text-sm">
                  <Users className="h-4 w-4 text-muted-foreground" />
                  <span>Capacity: {classItem.kapasitas}</span>
                </div>
                <Badge variant="outline">{classItem.sks} SKS</Badge>
              </div>
              <div className="flex items-center space-x-2 text-sm">
                <Hash className="h-4 w-4 text-muted-foreground" />
                <span className="text-muted-foreground">{classItem.tahun_akademik}</span>
              </div>
              {classItem.tanggal_mulai && classItem.tanggal_selesai && (
                <div className="flex items-center space-x-2 text-sm">
                  <Clock className="h-4 w-4 text-muted-foreground" />
                  <span className="text-muted-foreground text-xs">
                    {new Date(classItem.tanggal_mulai).toLocaleDateString("id-ID")} -{" "}
                    {new Date(classItem.tanggal_selesai).toLocaleDateString("id-ID")}
                  </span>
                </div>
              )}
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  )
}
