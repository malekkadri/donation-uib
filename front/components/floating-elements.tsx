"use client"

import { motion, useTransform, type MotionValue } from "framer-motion"
import { Heart, Star, TrendingUp, Users, Globe, Shield } from "lucide-react"
// Removed useMemo import as it's no longer needed for this purpose

interface FloatingElementsProps {
  mouseX: MotionValue<number>
  mouseY: MotionValue<number>
}

const elementsData = [
  {
    Icon: Heart,
    initialXPercent: 15,
    initialYPercent: 25,
    depth: 0.2,
    size: 32,
    baseDuration: 10,
    baseOpacity: [0, 0.4, 0.2, 0],
  },
  {
    Icon: Star,
    initialXPercent: 35,
    initialYPercent: 45,
    depth: 0.4,
    size: 28,
    baseDuration: 12,
    baseOpacity: [0, 0.5, 0.3, 0],
  },
  {
    Icon: TrendingUp,
    initialXPercent: 55,
    initialYPercent: 15,
    depth: 0.1,
    size: 30,
    baseDuration: 11,
    baseOpacity: [0, 0.3, 0.1, 0],
  },
  {
    Icon: Users,
    initialXPercent: 75,
    initialYPercent: 35,
    depth: 0.3,
    size: 36,
    baseDuration: 13,
    baseOpacity: [0, 0.6, 0.4, 0],
  },
  {
    Icon: Globe,
    initialXPercent: 25,
    initialYPercent: 65,
    depth: 0.5,
    size: 26,
    baseDuration: 9,
    baseOpacity: [0, 0.4, 0.2, 0],
  },
  {
    Icon: Shield,
    initialXPercent: 65,
    initialYPercent: 75,
    depth: 0.25,
    size: 34,
    baseDuration: 14,
    baseOpacity: [0, 0.5, 0.3, 0],
  },
]

export default function FloatingElements({ mouseX, mouseY }: FloatingElementsProps) {
  const xRange = typeof window !== "undefined" ? window.innerWidth / 2 : 0
  const yRange = typeof window !== "undefined" ? window.innerHeight / 2 : 0

  // CRITICAL FIX: Call useTransform directly for each element at the top level.
  // Since elementsData is a constant array, the number of calls is fixed and consistent.
  const parallaxTransforms = elementsData.map((element) => {
    const parallaxX = useTransform(mouseX, (latest) => (latest - xRange) * element.depth * 0.1)
    const parallaxY = useTransform(mouseY, (latest) => (latest - yRange) * element.depth * 0.1)
    return { parallaxX, parallaxY }
  })

  return (
    <div className="absolute inset-0 overflow-hidden pointer-events-none">
      {elementsData.map(({ Icon, initialXPercent, initialYPercent, size, baseDuration, baseOpacity }, index) => {
        // Access the pre-computed motion values for the current element
        const { parallaxX, parallaxY } = parallaxTransforms[index]

        return (
          <motion.div
            key={index}
            className="absolute"
            style={{
              left: `${initialXPercent}%`,
              top: `${initialYPercent}%`,
              x: parallaxX,
              y: parallaxY,
            }}
            initial={{ opacity: 0, scale: 0 }}
            animate={{
              opacity: baseOpacity,
              scale: [0.5, 1.0 + Math.random() * 0.4 - 0.2, 0.8 + Math.random() * 0.4 - 0.2, 0.5],
              rotate: [0, Math.random() * 360 - 180, 0],
            }}
            transition={{
              duration: baseDuration + Math.random() * 4 - 2,
              delay: index * 0.5,
              repeat: Number.POSITIVE_INFINITY,
              ease: "easeInOut",
            }}
          >
            <Icon style={{ width: size, height: size }} className="text-white/20" />
          </motion.div>
        )
      })}
    </div>
  )
}
