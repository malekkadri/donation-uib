"use client"

import { useEffect, useState } from "react"
import { motion, useMotionValue, useTransform, animate } from "framer-motion"

interface CountUpProps {
  end: number
  duration?: number
  delay?: number
  prefix?: string
  suffix?: string
}

export default function CountUp({ end, duration = 2, delay = 0, prefix = "", suffix = "" }: CountUpProps) {
  const count = useMotionValue(0)
  const rounded = useTransform(count, (latest) => Math.round(latest))
  const [displayValue, setDisplayValue] = useState(0)

  useEffect(() => {
    const timer = setTimeout(() => {
      const controls = animate(count, end, {
        duration,
        ease: "easeOut",
        onUpdate: (latest) => {
          setDisplayValue(Math.round(latest))
        },
      })
      return controls.stop
    }, delay * 1000)

    return () => clearTimeout(timer)
  }, [count, end, duration, delay])

  return (
    <motion.span
      initial={{ opacity: 0, scale: 0.5 }}
      animate={{ opacity: 1, scale: 1 }}
      transition={{ duration: 0.5, delay }}
    >
      {prefix}
      {displayValue.toLocaleString()}
      {suffix}
    </motion.span>
  )
}
