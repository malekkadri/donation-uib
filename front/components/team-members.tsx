"use client"

import { useEffect, useState } from "react"
import Image from "next/image"
import { Card, CardContent } from "@/components/ui/card"
import { Skeleton } from "@/components/ui/skeleton"
import { motion } from "framer-motion"
import { useLanguage } from "@/contexts/language-context"
import { getBackendUrl } from "@/lib/utils"

interface Member {
  id: number
  name: string
  designation: string
  quote: string
  image: string
}

export default function TeamMembers() {
  const [members, setMembers] = useState<Member[]>([])
  const [loading, setLoading] = useState(true)
  const [error, setError] = useState<string | null>(null)
  const { t } = useLanguage()

  useEffect(() => {
    const fetchMembers = async () => {
      try {
        setLoading(true)
        setError(null)

        console.log("Fetching team members...")
        const response = await fetch("/api/page/about")

        if (!response.ok) {
          throw new Error(`HTTP error! status: ${response.status}`)
        }

        const data = await response.json()
        console.log("Members data:", data)

        // Handle response format from Laravel backend
        if (data.member && Array.isArray(data.member)) {
          setMembers(data.member)
        } else if (data.success && data.member && Array.isArray(data.member)) {
          setMembers(data.member)
        } else {
          console.warn("Unexpected data format:", data)
          setMembers([])
        }
      } catch (err) {
        console.error("Error fetching team members:", err)
        // Don't show error to user, just use empty state
        setMembers([])
      } finally {
        setLoading(false)
      }
    }

    fetchMembers()
  }, [])

  const getImageUrl = (image: string) => {
    // Check if the image name is a placeholder or doesn't exist
    if (!image || image.includes("placeholder")) {
      return `/placeholder.svg?height=128&width=128&query=person`
    }
    return `${getBackendUrl()}/images/members/${image}`
  }

  if (error) {
    return (
      <Card>
        <CardContent className="p-6 text-center text-red-500">{error}</CardContent>
      </Card>
    )
  }

  if (loading) {
    return (
      <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        {[...Array(3)].map((_, i) => (
          <Card key={i}>
            <CardContent className="p-6 text-center">
              <Skeleton className="h-32 w-32 rounded-full mx-auto mb-4" />
              <Skeleton className="h-6 w-32 mx-auto mb-2" />
              <Skeleton className="h-4 w-24 mx-auto mb-4" />
              <Skeleton className="h-4 w-full mb-2" />
              <Skeleton className="h-4 w-full mb-2" />
              <Skeleton className="h-4 w-2/3 mx-auto" />
            </CardContent>
          </Card>
        ))}
      </div>
    )
  }

  return (
    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      {members.length > 0 ? (
        members.map((member, index) => (
          <motion.div
            key={member.id}
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.3, delay: index * 0.1 }}
          >
            <Card>
              <CardContent className="p-6 text-center">
                <div className="mb-4">
                  <Image
                    src={getImageUrl(member.image) || "/placeholder.svg"}
                    alt={member.name}
                    width={128}
                    height={128}
                    className="rounded-full mx-auto object-cover h-32 w-32"
                  />
                </div>
                <h3 className="text-xl font-semibold mb-1">{member.name}</h3>
                <p className="text-red-600 mb-4">{member.designation}</p>
                <p className="text-muted-foreground italic">"{member.quote}"</p>
              </CardContent>
            </Card>
          </motion.div>
        ))
      ) : (
        <div className="col-span-full text-center py-8 text-muted-foreground">{t("about.team.empty")}</div>
      )}
    </div>
  )
}
