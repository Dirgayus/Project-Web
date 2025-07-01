import type React from "react"
import type { Metadata } from "next"
import { Inter } from "next/font/google"
import "./globals.css"
import { SkyNavigation } from "@/components/sky-navigation"

const inter = Inter({ subsets: ["latin"] })

export const metadata: Metadata = {
  title: "Sky University - KRS Management System",
  description: "Sistem Kartu Rencana Studi Sky University",
    generator: 'v0.dev'
}

export default function RootLayout({
  children,
}: {
  children: React.ReactNode
}) {
  return (
    <html lang="id">
      <body className={inter.className}>
        <div className="relative min-h-screen">
          <SkyNavigation />
          <main className="sky-main-content lg:sky-main-content">
            <div className="sky-content-area pt-16 lg:pt-0">{children}</div>
          </main>
        </div>
      </body>
    </html>
  )
}
