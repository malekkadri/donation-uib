import type { Metadata } from "next"
import AlbumsPageClient from "./AlbumsPageClient"

export const metadata: Metadata = {
  title: "Gallery | UIB Donation Platform",
  description: "View our photo albums and see the impact of your donations",
}

export default function AlbumsPage() {
  return <AlbumsPageClient />
}
