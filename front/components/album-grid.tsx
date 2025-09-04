"use client"

import { useEffect, useState } from "react"
import Link from "next/link"
import Image from "next/image"
import { Card, CardContent } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Skeleton } from "@/components/ui/skeleton"
import { ChevronLeft, ChevronRight } from "lucide-react"
import { motion } from "framer-motion"
import { useLanguage } from "@/contexts/language-context"
import { getBackendUrl } from "@/lib/utils"

interface Album {
  id: number
  name: string
  description: string
  media: {
    id: number
    name: string
  }[]
}

interface PaginationData {
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export default function AlbumGrid() {
  const [albums, setAlbums] = useState<Album[]>([])
  const [pagination, setPagination] = useState<PaginationData>({
    current_page: 1,
    last_page: 1,
    per_page: 12,
    total: 0,
  })
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)
  const { t } = useLanguage()

  const fetchAlbums = async (page = 1) => {
    setLoading(true)
    setError(null)

    try {
      const response = await fetch(`/api/page/albums?page=${page}`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      console.log("Albums data:", data)

      // Handle response format from Laravel backend
      if (data.albums && data.albums.data) {
        setAlbums(data.albums.data)
        setPagination({
          current_page: data.albums.current_page || 1,
          last_page: data.albums.last_page || 1,
          per_page: data.albums.per_page || 12,
          total: data.albums.total || 0,
        })
      } else if (data.success && data.albums && data.albums.data) {
        setAlbums(data.albums.data)
        setPagination({
          current_page: data.albums.current_page || 1,
          last_page: data.albums.last_page || 1,
          per_page: data.albums.per_page || 12,
          total: data.albums.total || 0,
        })
      } else {
        console.warn("Unexpected data format:", data)
        setAlbums([])
      }
    } catch (err) {
      console.error("Error fetching albums:", err)
      // Don't show error to user, just use empty state
      setAlbums([])
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchAlbums()
  }, [])

  const handlePageChange = (page: number) => {
    fetchAlbums(page)
  }

  const getImageUrl = (name: string) => {
    // Check if the image name is a placeholder or doesn't exist
    if (!name || name.includes("placeholder")) {
      return `/placeholder.svg?height=192&width=384&query=album`
    }
    return `${getBackendUrl()}/images/albums/${name}`
  }

  if (error) {
    return (
      <Card>
        <CardContent className="p-6 text-center text-red-500">{error}</CardContent>
      </Card>
    )
  }

  return (
    <div>
      {loading ? (
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          {[...Array(6)].map((_, i) => (
            <Card key={i}>
              <CardContent className="p-0">
                <Skeleton className="h-48 w-full rounded-t-lg" />
                <div className="p-4">
                  <Skeleton className="h-6 w-3/4 mb-2" />
                  <Skeleton className="h-4 w-full mb-1" />
                  <Skeleton className="h-4 w-2/3" />
                </div>
              </CardContent>
            </Card>
          ))}
        </div>
      ) : albums.length > 0 ? (
        <>
          <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            {albums.map((album, index) => (
              <motion.div
                key={album.id}
                initial={{ opacity: 0, y: 20 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.3, delay: index * 0.1 }}
              >
                <Link href={`/albums/${album.id}`}>
                  <Card className="overflow-hidden h-full transition-transform hover:scale-[1.02]">
                    <CardContent className="p-0">
                      <div className="h-48 relative">
                        {album.media && album.media.length > 0 ? (
                          <Image
                            src={getImageUrl(album.media[0].name) || "/placeholder.svg"}
                            alt={album.name}
                            fill
                            className="object-cover"
                          />
                        ) : (
                          <div className="h-full w-full bg-gray-200 flex items-center justify-center text-muted-foreground">
                            {t("albums.noImages")}
                          </div>
                        )}
                      </div>
                      <div className="p-4">
                        <h3 className="font-semibold text-lg mb-2 line-clamp-1">{album.name}</h3>
                        <p className="text-muted-foreground text-sm line-clamp-2">{album.description}</p>
                        <div className="mt-3 text-sm text-red-600">
                          {t(`albums.photos.${album.media.length}`)}
                        </div>
                      </div>
                    </CardContent>
                  </Card>
                </Link>
              </motion.div>
            ))}
          </div>

          {/* Pagination */}
          {pagination.last_page > 1 && (
            <div className="flex justify-center items-center gap-2 mt-10">
              <Button
                variant="outline"
                size="sm"
                onClick={() => handlePageChange(pagination.current_page - 1)}
                disabled={pagination.current_page === 1}
              >
                <ChevronLeft className="h-4 w-4" />
              </Button>

              <div className="text-sm">
                {t(
                  `Page ${pagination.current_page} of ${pagination.last_page}`
                )}
              </div>

              <Button
                variant="outline"
                size="sm"
                onClick={() => handlePageChange(pagination.current_page + 1)}
                disabled={pagination.current_page === pagination.last_page}
              >
                <ChevronRight className="h-4 w-4" />
              </Button>
            </div>
          )}
        </>
      ) : (
        <div className="text-center py-12 text-muted-foreground">{t("albums.empty")}</div>
      )}
    </div>
  )
}
