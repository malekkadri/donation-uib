"use client"

import type React from "react"
import { useState, useEffect } from "react"
import Link from "next/link"
import { Card, CardContent } from "@/components/ui/card"
import { Button } from "@/components/ui/button"
import { Skeleton } from "@/components/ui/skeleton"
import { formatCurrency } from "@/lib/utils"
import { useLanguage } from "@/contexts/language-context"
import { motion } from "framer-motion"

interface Donor {
  id: number
  name: string
  amount: number
  city_name?: string
  country_name?: string
}

const TopDonors: React.FC = () => {
  const [donors, setDonors] = useState<Donor[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)
  const { t } = useLanguage()

  useEffect(() => {
    const fetchDonors = async () => {
      try {
        setLoading(true)
        setError(null)

        console.log("Fetching donors data...")
        const response = await fetch("/api/page/donate")

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`)
        }

        const contentType = response.headers.get("content-type")
        if (!contentType || !contentType.includes("application/json")) {
          console.error("Response is not JSON:", contentType)
          const text = await response.text()
          console.error("Response text:", text.substring(0, 500))
          throw new Error("Response is not JSON")
        }

        const data = await response.json()
        console.log("Donors data:", data)

        if (data.donors && Array.isArray(data.donors)) {
          setDonors(data.donors)
        } else if (data.success && data.donors && Array.isArray(data.donors)) {
          setDonors(data.donors)
        } else {
          console.warn("Unexpected data format:", data)
          setDonors([])
        }
      } catch (err) {
        console.error("Error fetching donors:", err)
        setDonors([])
      } finally {
        setLoading(false)
      }
    }

    fetchDonors()
  }, [])

  const itemVariants = {
    hidden: { opacity: 0, x: -50 },
    visible: {
      opacity: 1,
      x: 0,
      transition: {
        type: "spring",
        stiffness: 120,
        damping: 14,
        duration: 0.6,
        ease: "easeOut",
      },
    },
  }

  if (error) {
    return (
      <section className="py-16 md:py-24 bg-dark text-dark-text">
        <div className="container">
          <Card className="bg-card border-border">
            <CardContent className="p-6 text-center text-red-500">{error}</CardContent>
          </Card>
        </div>
      </section>
    )
  }

  return (
    <section className="py-16 md:py-24 bg-dark text-dark-text">
      <div className="container text-center">
        <motion.h2
          className="text-3xl md:text-4xl font-bold mb-4"
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true, amount: 0.5 }}
          transition={{ duration: 0.6 }}
        >
          {t("donors.title")}
        </motion.h2>
        <motion.p
          className="text-muted-foreground text-lg max-w-2xl mx-auto mb-12"
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true, amount: 0.5 }}
          transition={{ duration: 0.6, delay: 0.2 }}
        >
          {t("donors.subtitle")}
        </motion.p>

        <Card className="max-w-2xl mx-auto shadow-lg bg-card border-border">
          <CardContent className="p-6">
            {loading ? (
              <div className="space-y-4">
                {[...Array(5)].map((_, i) => (
                  <div key={i} className="flex items-center justify-between py-2">
                    <div className="flex items-center gap-3">
                      <Skeleton className="h-10 w-10 rounded-full bg-secondary" />
                      <div className="space-y-1">
                        <Skeleton className="h-4 w-24 bg-secondary" />
                        <Skeleton className="h-3 w-16 bg-secondary" />
                      </div>
                    </div>
                    <Skeleton className="h-5 w-20 bg-secondary" />
                  </div>
                ))}
              </div>
            ) : donors.length > 0 ? (
              <motion.ul
                className="space-y-4"
                initial="hidden"
                whileInView="visible"
                viewport={{ once: true, amount: 0.3 }}
                variants={{
                  visible: {
                    transition: {
                      staggerChildren: 0.1,
                    },
                  },
                }}
              >
                {donors.map((donor, index) => (
                  <motion.li
                    key={donor.id}
                    className="flex items-center justify-between border-b border-border pb-4 last:border-b-0 last:pb-0"
                    variants={itemVariants}
                  >
                    <div className="flex items-center gap-4">
                      <span className="text-xl font-bold text-primary">{index + 1}.</span>
                      <div>
                        <p className="font-semibold text-lg text-foreground">{donor.name}</p>
                        <p className="text-sm text-muted-foreground">
                          {donor.city_name && donor.country_name
                            ? `${donor.city_name}, ${donor.country_name}`
                            : "Anonymous Location"}
                        </p>
                      </div>
                    </div>
                    <span className="text-xl font-bold text-primary">{formatCurrency(donor.amount)}</span>
                  </motion.li>
                ))}
              </motion.ul>
            ) : (
              <p className="text-muted-foreground py-8">{t("donors.empty")}</p>
            )}
          </CardContent>
        </Card>

        <motion.div
          className="mt-10"
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true, amount: 0.5 }}
          transition={{ duration: 0.6, delay: 0.4 }}
        >
          <Button asChild size="lg" variant="outline" className="border-border text-foreground hover:bg-secondary">
            <Link href="/leaderboard">{t("donors.viewAll")}</Link>
          </Button>
        </motion.div>
      </div>
    </section>
  )
}

export default TopDonors
