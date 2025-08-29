"use client"

import { useEffect, useState } from "react"
import { useParams, useRouter } from "next/navigation"
import { Button } from "@/components/ui/button"
import { Card, CardContent } from "@/components/ui/card"
import { Skeleton } from "@/components/ui/skeleton"
import { ArrowLeft } from "lucide-react"
import PhotoGallery from "@/components/photo-gallery"
import { useLanguage } from "@/contexts/language-context"

interface Album {
  id: number
  name: string
  description: string
  media: {
    id: number
    name: string
  }[]
}

export default function AlbumDetailPage() {
  const params = useParams()
  const router = useRouter()
  const [album, setAlbum] = useState<Album | null>(null)
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)
  const { t } = useLanguage()

  useEffect(() => {
    const fetchAlbum = async () => {
      if (!params.id) return

      try {
        const response = await fetch(`/api/page/album/${params.id}`)
        if (!response.ok) {
          if (response.status === 404) {
            throw new Error(t("albumDetail.notFound"))
          }
          throw new Error("Failed to fetch album")
        }

        const data = await response.json()
        console.log("Album data:", data)

        // Handle response format from Laravel backend
        if (data.album) {
          setAlbum(data.album)
        } else if (data.success && data.album) {
          setAlbum(data.album)
        } else {
          console.warn("Unexpected data format:", data)
          setAlbum(null)
        }
      } catch (err) {
        setError(err instanceof Error ? err.message : t("form.error.unexpected"))
        console.error(err)
      } finally {
        setLoading(false)
      }
    }

    fetchAlbum()
  }, [params.id, t])

  return (
    <div className="container py-12">
      <Button variant="ghost" className="mb-6 flex items-center gap-2" onClick={() => router.back()}>
        <ArrowLeft className="h-4 w-4" /> {t("albumDetail.back")}
      </Button>

      {loading ? (
        <div className="space-y-6">
          <Skeleton className="h-10 w-1/3" />
          <Skeleton className="h-6 w-full" />
          <Skeleton className="h-6 w-full" />
          <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-8">
            {[...Array(8)].map((_, i) => (
              <Skeleton key={i} className="aspect-square rounded-md" />
            ))}
          </div>
        </div>
      ) : error ? (
        <Card>
          <CardContent className="p-6 text-center text-red-500">{error}</CardContent>
        </Card>
      ) : album ? (
        <div>
          <h1 className="text-3xl font-bold mb-4">{album.name}</h1>
          <div className="prose max-w-none mb-8" dangerouslySetInnerHTML={{ __html: album.description }} />

          {album.media && album.media.length > 0 ? (
            <PhotoGallery media={album.media} />
          ) : (
            <div className="text-center py-12 text-muted-foreground">{t("albumDetail.noPhotos")}</div>
          )}
        </div>
      ) : (
        <div className="text-center py-12 text-muted-foreground">{t("albumDetail.notFound")}</div>
      )}
    </div>
  )
}
