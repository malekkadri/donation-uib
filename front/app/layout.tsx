import type React from "react"
import type { Metadata } from "next/dist/lib/metadata/types/metadata-interface"
import { Inter } from "next/font/google"
import "./globals.css"
import { ThemeProvider } from "@/components/theme-provider"
import { LanguageProvider } from "@/contexts/language-context"
import Navbar from "@/components/navbar"
import Footer from "@/components/footer"
import { UIBChat } from "@/components/uib-chat"

const inter = Inter({ subsets: ["latin"] })

export const metadata: Metadata = {
  title: "UIB - Union Internationale de Banques",
  description: "Your trusted banking partner in Tunisia",
    generator: 'v0.dev'
}

export default function RootLayout({
  children,
}: Readonly<{
  children: React.ReactNode
}>) {
  return (
    <html lang="en" suppressHydrationWarning className="overflow-x-hidden">
      <body className={`${inter.className} overflow-x-hidden`}>
        <ThemeProvider attribute="class" defaultTheme="light" enableSystem disableTransitionOnChange>
          <LanguageProvider>
            <div className="flex min-h-screen flex-col overflow-x-hidden w-full">
              <Navbar />
              <main className="flex-1 overflow-x-hidden w-full">{children}</main>
              <Footer />
            </div>
          </LanguageProvider>
        </ThemeProvider>
         <UIBChat />
      </body>
    </html>
  )
}
