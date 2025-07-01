import { Card, CardContent, CardDescription, CardHeader, CardTitle } from "@/components/ui/card"
import { Badge } from "@/components/ui/badge"
import { getCourses } from "@/lib/db"
import { BookOpen, Hash, FileText } from "lucide-react"

export default async function CoursesPage() {
  const courses = await getCourses()

  const getSemesterColor = (semester: number) => {
    const colors = [
      "bg-blue-100 text-blue-800",
      "bg-green-100 text-green-800",
      "bg-yellow-100 text-yellow-800",
      "bg-purple-100 text-purple-800",
      "bg-pink-100 text-pink-800",
      "bg-indigo-100 text-indigo-800",
      "bg-red-100 text-red-800",
      "bg-orange-100 text-orange-800",
    ]
    return colors[(semester - 1) % colors.length]
  }

  return (
    <div className="flex-1 space-y-4 p-4 md:p-8 pt-6">
      <div className="flex items-center justify-between space-y-2">
        <h2 className="text-3xl font-bold tracking-tight">Courses</h2>
        <Badge variant="secondary">{courses.length} Total Courses</Badge>
      </div>
      <div className="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        {courses.map((course: any) => (
          <Card key={course.id_matakuliah} className="hover:shadow-md transition-shadow">
            <CardHeader>
              <div className="flex items-start justify-between">
                <div className="flex items-center space-x-2">
                  <BookOpen className="h-5 w-5 text-green-600" />
                  <div>
                    <CardTitle className="text-lg">{course.nama_matakuliah}</CardTitle>
                    <CardDescription>{course.kode_matakuliah}</CardDescription>
                  </div>
                </div>
                <Badge className={getSemesterColor(course.semester)}>Semester {course.semester}</Badge>
              </div>
            </CardHeader>
            <CardContent className="space-y-3">
              <div className="flex items-center justify-between">
                <div className="flex items-center space-x-2 text-sm">
                  <Hash className="h-4 w-4 text-muted-foreground" />
                  <span>SKS: {course.sks}</span>
                </div>
                <Badge variant="outline">{course.sks} Credits</Badge>
              </div>
              {course.deskripsi && (
                <div className="flex items-start space-x-2 text-sm">
                  <FileText className="h-4 w-4 text-muted-foreground mt-0.5" />
                  <span className="text-muted-foreground text-xs leading-relaxed">{course.deskripsi}</span>
                </div>
              )}
            </CardContent>
          </Card>
        ))}
      </div>
    </div>
  )
}
