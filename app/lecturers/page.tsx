import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { getLecturers } from "@/lib/db"
import { UserCheck, Mail, Phone, Award, Hash } from "lucide-react"

export default async function LecturersPage() {
  const lecturers = await getLecturers()

  return (
    <div className="flex-1 space-y-4 p-4 md:p-8 pt-6">
      <div className="flex items-center justify-between space-y-2">
        <h2 className="text-3xl font-bold tracking-tight">Lecturers</h2>
        <Badge variant="secondary">{lecturers.length} Total Lecturers</Badge>
      </div>
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {lecturers.map((lecturer: any) => (
          <Card key={lecturer.id_dosen} className="hover:shadow-md transition-shadow">
            <CardHeader>
              <div className="flex items-center space-x-2">
                <UserCheck className="h-5 w-5 text-purple-600" />
                <div>
                  <CardTitle className="text-lg">{lecturer.nama_dosen}</CardTitle>
                  <CardDescription>{lecturer.nidn}</CardDescription>
                </div>
              </div>
            </CardHeader>
            <CardContent className="space-y-3">
              {lecturer.gelar && (
                <div className="flex items-center space-x-2 text-sm">
                  <Award className="h-4 w-4 text-muted-foreground" />
                  <span>{lecturer.gelar}</span>
                </div>
              )}
              {lecturer.email && (
                <div className="flex items-center space-x-2 text-sm">
                  <Mail className="h-4 w-4 text-muted-foreground" />
                  <span className="truncate">{lecturer.email}</span>
                </div>
              )}
              {lecturer.nomor_telepon && (
                <div className="flex items-center space-x-2 text-sm">
                  <Phone className="h-4 w-4 text-muted-foreground" />
                  <span>{lecturer.nomor_telepon}</span>
                </div>
              )}
              <div className="flex items-center space-x-2 text-sm">
                <Hash className="h-4 w-4 text-muted-foreground" />
                <span className="text-muted-foreground">NIDN: {lecturer.nidn}</span>
              </div>
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  )
}
