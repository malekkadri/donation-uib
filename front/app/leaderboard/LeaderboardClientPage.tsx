"use client"

import LeaderboardTable from "@/components/leaderboard-table"
import { useLanguage } from "@/contexts/language-context"

export default function LeaderboardClientPage() {
  const { t } = useLanguage()
  return (
    <div className="container py-12">
      <div className="max-w-4xl mx-auto">
        <div className="text-center mb-10">
          <h1 className="text-3xl font-bold mb-4">{t("leaderboardPage.title")}</h1>
          <p className="text-muted-foreground">{t("leaderboardPage.description")}</p>
        </div>

        <LeaderboardTable />
      </div>
    </div>
  )
}
