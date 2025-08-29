import type { Metadata } from "next"
import ContactPageClient from "./ContactPageClient"

export const metadata: Metadata = {
  title: "Contact Us | UIB Donation Platform",
  description: "Get in touch with our team",
}

export default function ContactPage() {
  return <ContactPageClient />
}
