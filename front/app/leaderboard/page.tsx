import type { Metadata } from "next"
import LeaderboardClientPage from "./LeaderboardClientPage"

export const metadata: Metadata = {
  title: "Leaderboard | UIB Donation Platform",
  description: "View our top donors and their contributions",
}

export default function LeaderboardPage() {
  return <LeaderboardClientPage />
}
