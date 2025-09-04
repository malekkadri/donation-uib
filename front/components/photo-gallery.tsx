"use client"

import type React from "react"

import { useState } from "react"
import Image from "next/image"
import { Dialog, DialogContent } from "@/components/ui/dialog"
import { X, ChevronLeft, ChevronRight } from "lucide-react"
import { Button } from "@/components/ui/button"
import { useLanguage } from "@/contexts/language-context"
import { getBackendUrl } from "@/lib/utils"

interface Media {
  id: number
  name: string
}

interface PhotoGalleryProps {
  media: Media[]
}

export default function PhotoGallery({ media }: PhotoGalleryProps) {
  const [selectedPhoto, setSelectedPhoto] = useState<number | null>(null)
  const { t } = useLanguage()

  const openLightbox = (index: number) => {
    setSelectedPhoto(index)
  }

  const closeLightbox = () => {
    setSelectedPhoto(null)
  }

  const goToPrevious = () => {
    if (selectedPhoto === null) return
    setSelectedPhoto((prev) => {
      if (prev === null) return 0
      return prev === 0 ? media.length - 1 : prev - 1
    })
  }

  const goToNext = () => {
    if (selectedPhoto === null) return
    setSelectedPhoto((prev) => {
      if (prev === null) return 0
      return prev === media.length - 1 ? 0 : prev + 1
    })
  }

  const handleKeyDown = (e: React.KeyboardEvent) => {
    if (e.key === "ArrowLeft") {
      goToPrevious()
    } else if (e.key === "ArrowRight") {
      goToNext()
    } else if (e.key === "Escape") {
      closeLightbox()
    }
  }

  const getImageUrl = (name: string) => {
    // Check if the image name is a placeholder or doesn't exist
    if (!name || name.includes("placeholder")) {
      return `/placeholder.svg?height=400&width=400&query=photo`
    }
    return `${getBackendUrl()}/images/albums/${name}`
  }

  return (
    <div>
      <div className="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
        {media.map((item, index) => (
          <div
            key={item.id}
            className="aspect-square relative rounded-md overflow-hidden cursor-pointer"
            onClick={() => openLightbox(index)}
          >
            <Image
              src={getImageUrl(item.name) || "/placeholder.svg"}
              alt={`Photo ${index + 1}`}
              fill
              className="object-cover hover:scale-105 transition-transform duration-300"
            />
          </div>
        ))}
      </div>

      <Dialog open={selectedPhoto !== null} onOpenChange={closeLightbox}>
        <DialogContent className="max-w-5xl w-[95vw] h-[90vh] p-0 bg-black/90 border-none" onKeyDown={handleKeyDown}>
          <div className="relative w-full h-full flex items-center justify-center">
            <Button
              variant="ghost"
              size="icon"
              className="absolute top-2 right-2 text-white z-10 hover:bg-white/10"
              onClick={closeLightbox}
            >
              <X className="h-6 w-6" />
              <span className="sr-only">{t("photoGallery.close")}</span>
            </Button>

            <Button
              variant="ghost"
              size="icon"
              className="absolute left-2 top-1/2 -translate-y-1/2 text-white z-10 hover:bg-white/10"
              onClick={goToPrevious}
            >
              <ChevronLeft className="h-8 w-8" />
              <span className="sr-only">{t("photoGallery.previous")}</span>
            </Button>

            {selectedPhoto !== null && (
              <div className="w-full h-full flex items-center justify-center p-8">
                <div className="relative max-h-full max-w-full">
                  <Image
                    src={getImageUrl(media[selectedPhoto].name) || "/placeholder.svg"}
                    alt={`Photo ${selectedPhoto + 1}`}
                    width={1200}
                    height={800}
                    className="max-h-[80vh] w-auto object-contain"
                  />
                </div>
              </div>
            )}

            <Button
              variant="ghost"
              size="icon"
              className="absolute right-2 top-1/2 -translate-y-1/2 text-white z-10 hover:bg-white/10"
              onClick={goToNext}
            >
              <ChevronRight className="h-8 w-8" />
              <span className="sr-only">{t("photoGallery.next")}</span>
            </Button>

            <div className="absolute bottom-4 left-0 right-0 text-center text-white">
              {selectedPhoto !== null && `${selectedPhoto + 1} / ${media.length}`}
            </div>
          </div>
        </DialogContent>
      </Dialog>
    </div>
  )
}
