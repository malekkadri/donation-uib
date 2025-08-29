import type { Metadata } from "next"
import DonatePageClient from "./DonatePageClient"

export const metadata: Metadata = {
  title: "Donate | UIB Donation Platform",
  description: "Make a donation to support UIB initiatives",
}

export default function DonatePage() {
  return <DonatePageClient />
}
