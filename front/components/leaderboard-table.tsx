"use client"

import { useState, useEffect } from "react"
import { Card, CardContent } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Skeleton } from "@/components/ui/skeleton"
import { formatCurrency } from "@/lib/utils"
import { ChevronLeft, ChevronRight } from "lucide-react"
import { motion } from "framer-motion"
import { useLanguage } from "@/contexts/language-context"

interface Donor {
  id: number
  name: string
  amount: number
  city_name?: string
  country_name?: string
  created_at: string
}

interface PaginationData {
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export default function LeaderboardTable() {
  const [donors, setDonors] = useState<Donor[]>([])
  const [pagination, setPagination] = useState<PaginationData>({
    current_page: 1,
    last_page: 1,
    per_page: 10,
    total: 0,
  })
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)
  const { t } = useLanguage()

  const fetchDonors = async (page = 1) => {
    setLoading(true)
    setError(null)

    try {
      const response = await fetch(`/api/page/leaderboard?page=${page}`)
      if (!response.ok) {
        throw new Error(`HTTP error! status: ${response.status}`)
      }

      const data = await response.json()
      console.log("Leaderboard data:", data)

      // Handle the response format from Laravel backend
      if (data.donors) {
        if (data.donors.data) {
          // Paginated response
          setDonors(data.donors.data)
          setPagination({
            current_page: data.donors.current_page || 1,
            last_page: data.donors.last_page || 1,
            per_page: data.donors.per_page || 10,
            total: data.donors.total || 0,
          })
        } else if (Array.isArray(data.donors)) {
          // Non-paginated response
          setDonors(data.donors)
        }
      } else {
        console.warn("Unexpected data format:", data)
        setDonors([])
      }
    } catch (err) {
      console.error("Error fetching leaderboard data:", err)
      // Don't show error to user, just use empty state
      setDonors([])
    } finally {
      setLoading(false)
    }
  }

  useEffect(() => {
    fetchDonors()
  }, [])

  const handlePageChange = (page: number) => {
    fetchDonors(page)
  }

  const rowVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: { opacity: 1, y: 0, transition: { duration: 0.4, ease: "easeOut" } },
  }

  if (error) {
    return (
      <Card className="bg-card border-border">
        <CardContent className="p-6 text-center text-red-500">{error}</CardContent>
      </Card>
    )
  }

  return (
    <Card className="bg-card border-border">
      <CardContent className="p-6">
        {loading ? (
          <div className="space-y-4">
            {[...Array(5)].map((_, i) => (
              <div key={i} className="flex justify-between items-center py-3 border-b border-border">
                <div className="flex items-center gap-4">
                  <Skeleton className="h-8 w-8 rounded-full bg-secondary" />
                  <div className="space-y-2">
                    <Skeleton className="h-4 w-32 bg-secondary" />
                    <Skeleton className="h-3 w-24 bg-secondary" />
                  </div>
                </div>
                <Skeleton className="h-6 w-20 bg-secondary" />
              </div>
            ))}
          </div>
        ) : donors.length > 0 ? (
          <>
            <div className="overflow-x-auto">
              <table className="w-full">
                <thead>
                  <tr className="border-b border-border">
                    <th className="text-left py-3 px-2 text-muted-foreground">{t("leaderboard.table.rank")}</th>
                    <th className="text-left py-3 px-2 text-muted-foreground">{t("leaderboard.table.donor")}</th>
                    <th className="text-left py-3 px-2 text-muted-foreground">{t("leaderboard.table.location")}</th>
                    <th className="text-right py-3 px-2 text-muted-foreground">{t("leaderboard.table.amount")}</th>
                    <th className="text-right py-3 px-2 text-muted-foreground">{t("leaderboard.table.date")}</th>
                  </tr>
                </thead>
                <motion.tbody
                  initial="hidden"
                  animate="visible"
                  variants={{
                    visible: {
                      transition: {
                        staggerChildren: 0.05,
                      },
                    },
                  }}
                >
                  {donors.map((donor, index) => {
                    const rank = (pagination.current_page - 1) * pagination.per_page + index + 1
                    const donationDate = new Date(donor.created_at).toLocaleDateString()

                    return (
                      <motion.tr
                        key={donor.id}
                        className="border-b border-border last:border-b-0"
                        variants={rowVariants}
                      >
                        <td className="py-3 px-2">
                          <div className="font-semibold text-foreground">
                            {rank <= 3 ? (
                              <span
                                className={`
                                inline-flex items-center justify-center w-6 h-6 rounded-full
                                ${
                                  rank === 1
                                    ? "bg-yellow-100 text-yellow-700"
                                    : rank === 2
                                      ? "bg-gray-100 text-gray-700"
                                      : "bg-amber-100 text-amber-700"
                                }
                              `}
                              >
                                {rank}
                              </span>
                            ) : (
                              rank
                            )}
                          </div>
                        </td>
                        <td className="py-3 px-2 font-medium text-foreground">{donor.name}</td>
                        <td className="py-3 px-2 text-muted-foreground">
                          {donor.city_name && donor.country_name
                            ? `${donor.city_name}, ${donor.country_name}`
                            : t("leaderboard.location.anonymous")}
                        </td>
                        <td className="py-3 px-2 text-right font-semibold text-primary">
                          {formatCurrency(donor.amount)}
                        </td>
                        <td className="py-3 px-2 text-right text-muted-foreground">{donationDate}</td>
                      </motion.tr>
                    )
                  })}
                </motion.tbody>
              </table>
            </div>

            {/* Pagination */}
            {pagination.last_page > 1 && (
              <div className="flex justify-center items-center gap-2 mt-6">
                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => handlePageChange(pagination.current_page - 1)}
                  disabled={pagination.current_page === 1}
                  className="border-border text-foreground hover:bg-secondary"
                >
                  <ChevronLeft className="h-4 w-4" />
                </Button>

                <div className="text-sm text-muted-foreground">
                  {t(
                    `leaderboard.pagination.pageOf.${pagination.current_page}_of_${pagination.last_page}`
                  )}
                </div>

                <Button
                  variant="outline"
                  size="sm"
                  onClick={() => handlePageChange(pagination.current_page + 1)}
                  disabled={pagination.current_page === pagination.last_page}
                  className="border-border text-foreground hover:bg-secondary"
                >
                  <ChevronRight className="h-4 w-4" />
                </Button>
              </div>
            )}
          </>
        ) : (
          <div className="text-center py-8 text-muted-foreground">{t("leaderboard.empty")}</div>
        )}
      </CardContent>
    </Card>
  )
}
