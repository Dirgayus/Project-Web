import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { getStudents } from "@/lib/db"
import { User, Mail, Phone, MapPin, Calendar } from "lucide-react"

export default async function StudentsPage() {
  const students = await getStudents()

  return (
    <div className="flex-1 space-y-4 p-4 md:p-8 pt-6">
      <div className="flex items-center justify-between space-y-2">
        <h2 className="text-3xl font-bold tracking-tight">Students</h2>
        <Badge variant="secondary">{students.length} Total Students</Badge>
      </div>
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {students.map((student: any) => (
          <Card key={student.id_mahasiswa} className="hover:shadow-md transition-shadow">
            <CardHeader>
              <div className="flex items-center space-x-2">
                <User className="h-5 w-5 text-blue-600" />
                <div>
                  <CardTitle className="text-lg">{student.nama}</CardTitle>
                  <CardDescription>{student.nim}</CardDescription>
                </div>
              </div>
            </CardHeader>
            <CardContent className="space-y-3">
              <div className="flex items-center space-x-2 text-sm">
                <Calendar className="h-4 w-4 text-muted-foreground" />
                <span>{new Date(student.tanggal_lahir).toLocaleDateString("id-ID")}</span>
                <Badge variant={student.jenis_kelamin === "L" ? "default" : "secondary"} className="ml-auto">
                  {student.jenis_kelamin === "L" ? "Laki-laki" : "Perempuan"}
                </Badge>
              </div>
              {student.email && (
                <div className="flex items-center space-x-2 text-sm">
                  <Mail className="h-4 w-4 text-muted-foreground" />
                  <span className="truncate">{student.email}</span>
                </div>
              )}
              {student.nomor_telepon && (
                <div className="flex items-center space-x-2 text-sm">
                  <Phone className="h-4 w-4 text-muted-foreground" />
                  <span>{student.nomor_telepon}</span>
                </div>
              )}
              {student.alamat && (
                <div className="flex items-start space-x-2 text-sm">
                  <MapPin className="h-4 w-4 text-muted-foreground mt-0.5" />
                  <span className="text-muted-foreground">{student.alamat}</span>
                </div>
              )}
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  )
}
