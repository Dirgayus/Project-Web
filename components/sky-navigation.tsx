"use client"

import Link from "next/link"
import { usePathname } from "next/navigation"
import { cn } from "@/lib/utils"
import { Button } from "@/components/ui/button"
import { Sheet, SheetContent, SheetTrigger } from "@/components/ui/sheet"
import { Input } from "@/components/ui/input"
import { Menu, GraduationCap, BookOpen, UserCheck, Calendar, ClipboardList, Home, Search, User } from "lucide-react"

const navigation = [
  { name: "Beranda", href: "/", icon: Home, section: "main" },
  { name: "Mata Kuliah", href: "/courses", icon: BookOpen, section: "akademik" },
  { name: "Dosen", href: "/lecturers", icon: UserCheck, section: "akademik" },
  { name: "Kartu Rencana Studi", href: "/krs", icon: ClipboardList, section: "akademik" },
  { name: "Tahun Akademik", href: "/classes", icon: Calendar, section: "informasi" },
]

const sections = {
  main: "",
  akademik: "AKADEMIK",
  informasi: "INFORMASI",
}

export function SkyNavigation() {
  const pathname = usePathname()

  const SidebarContent = () => (
    <div className="sky-sidebar">
      <div className="sky-sidebar-header">
        <Link href="/" className="sky-sidebar-brand">
          <GraduationCap className="h-8 w-8" />
          <div>
            <div className="font-bold text-lg">SKY</div>
            <div className="text-sm font-medium">UNIVERSITY</div>
          </div>
        </Link>
      </div>

      <nav className="sky-sidebar-nav">
        {Object.entries(sections).map(([sectionKey, sectionTitle]) => (
          <div key={sectionKey} className="sky-sidebar-section">
            {sectionTitle && <div className="sky-sidebar-section-title">{sectionTitle}</div>}
            {navigation
              .filter((item) => item.section === sectionKey)
              .map((item) => (
                <Link
                  key={item.href}
                  href={item.href}
                  className={cn("sky-sidebar-item", pathname === item.href && "active")}
                >
                  <item.icon className="sky-sidebar-icon" />
                  <span>{item.name}</span>
                </Link>
              ))}
          </div>
        ))}
      </nav>
    </div>
  )

  return (
    <>
      {/* Desktop Sidebar */}
      <div className="hidden lg:block">
        <SidebarContent />
      </div>

      {/* Mobile Navigation */}
      <Sheet>
        <div className="lg:hidden fixed top-0 left-0 right-0 z-50 sky-header">
          <div className="flex items-center">
            <SheetTrigger asChild>
              <Button variant="ghost" size="icon" className="mr-2">
                <Menu className="h-5 w-5" />
              </Button>
            </SheetTrigger>
            <Link href="/" className="flex items-center space-x-2">
              <GraduationCap className="h-6 w-6 text-primary" />
              <span className="font-bold">SKY UNIVERSITY</span>
            </Link>
          </div>
        </div>
        <SheetContent side="left" className="p-0 w-64">
          <SidebarContent />
        </SheetContent>
      </Sheet>

      {/* Main Header for Desktop */}
      <div className="hidden lg:block sky-main-content">
        <header className="sky-header">
          <div className="sky-search-container">
            <div className="relative">
              <Search className="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-muted-foreground" />
              <Input placeholder="Cari..." className="pl-10 sky-search-input" />
            </div>
          </div>
          <div className="sky-user-profile">
            <span>Pengguna</span>
            <User className="h-5 w-5" />
          </div>
        </header>
      </div>
    </>
  )
}
