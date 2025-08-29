"use client"

import AlbumGrid from "@/components/album-grid"
import { useLanguage } from "@/contexts/language-context"

export default function AlbumsPageClient() {
  const { t } = useLanguage()
  return (
    <div className="container py-12">
      <div className="max-w-4xl mx-auto">
        <div className="text-center mb-10">
          <h1 className="text-3xl font-bold mb-4">{t("gallery.title")}</h1>
          <p className="text-muted-foreground">{t("gallery.description")}</p>
        </div>

        <AlbumGrid />
      </div>
    </div>
  )
}
