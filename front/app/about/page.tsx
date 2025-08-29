import type { Metadata } from "next"
import AboutPageClient from "./AboutPageClient"

export const metadata: Metadata = {
  title: "About Us | UIB Donation Platform",
  description: "Learn about our mission and team",
}

export default function AboutPage() {
  return <AboutPageClient />
}
