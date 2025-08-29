"use client"

import { useRef, type ReactNode } from "react"
import { motion, useInView } from "framer-motion"

interface ScrollAnimationProps {
  children: ReactNode
  delay?: number
  direction?: "up" | "down" | "left" | "right"
  className?: string
  once?: boolean
  duration?: number
}

export default function ScrollAnimation({
  children,
  delay = 0,
  direction = "up",
  className = "",
  once = true,
  duration = 0.5,
}: ScrollAnimationProps) {
  const ref = useRef(null)
  const isInView = useInView(ref, { once, amount: 0.3 })

  const getDirectionValues = () => {
    switch (direction) {
      case "up":
        return { initial: { y: 50 }, animate: { y: 0 } }
      case "down":
        return { initial: { y: -50 }, animate: { y: 0 } }
      case "left":
        return { initial: { x: 50 }, animate: { x: 0 } }
      case "right":
        return { initial: { x: -50 }, animate: { x: 0 } }
      default:
        return { initial: { y: 50 }, animate: { y: 0 } }
    }
  }

  const { initial, animate } = getDirectionValues()

  return (
    <div ref={ref} className={className}>
      <motion.div
        initial={{ ...initial, opacity: 0 }}
        animate={isInView ? { ...animate, opacity: 1 } : { ...initial, opacity: 0 }}
        transition={{ duration, delay }}
      >
        {children}
      </motion.div>
    </div>
  )
}
