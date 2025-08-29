"use client"

import DonationForm from "@/components/donation-form"
import { useLanguage } from "@/contexts/language-context"

export default function DonatePageClient() {
  const { t } = useLanguage()
  return (
    <div className="container py-12">
      <div className="max-w-3xl mx-auto">
        <div className="text-center mb-10">
          <h1 className="text-3xl font-bold mb-4">{t("donatePage.title")}</h1>
          <p className="text-muted-foreground">{t("donatePage.description")}</p>
        </div>

        <DonationForm />
      </div>
    </div>
  )
}
