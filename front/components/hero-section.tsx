"use client"

import Link from "next/link"
import { motion, useMotionValue, useSpring } from "framer-motion"
import ParticleBackground from "./particle-background"
import FloatingElements from "./floating-elements"
import { Button } from "@/components/ui/button"
import { useLanguage } from "@/contexts/language-context"
import { useEffect, useRef } from "react"

export default function HeroSection() {
  const { t } = useLanguage()
  const ref = useRef<HTMLElement>(null)

  // Mouse position tracking
  const mouseX = useMotionValue(typeof window !== "undefined" ? window.innerWidth / 2 : 0)
  const mouseY = useMotionValue(typeof window !== "undefined" ? window.innerHeight / 2 : 0)

  // Springify mouse values for smoother parallax
  const springConfig = { damping: 100, stiffness: 500, mass: 1 }
  const smoothMouseX = useSpring(mouseX, springConfig)
  const smoothMouseY = useSpring(mouseY, springConfig)

  useEffect(() => {
    const handleMouseMove = (event: MouseEvent) => {
      if (ref.current) {
        const rect = ref.current.getBoundingClientRect()
        mouseX.set(event.clientX - rect.left)
        mouseY.set(event.clientY - rect.top)
      } else {
        mouseX.set(event.clientX)
        mouseY.set(event.clientY)
      }
    }
    window.addEventListener("mousemove", handleMouseMove)
    return () => window.removeEventListener("mousemove", handleMouseMove)
  }, [mouseX, mouseY])

  const titleText = t("hero.title")

  // Variants for the container of the letters
  const sentenceContainerVariants = {
    hidden: { opacity: 1 },
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.03, // Stagger each letter's animation
        delayChildren: 0.2, // Wait a bit before starting
      },
    },
  }

  // Variants for each individual letter
  const letterVariants = {
    hidden: { opacity: 0, y: 25, filter: "blur(8px)" },
    visible: {
      opacity: 1,
      y: 0,
      filter: "blur(0px)",
      transition: {
        type: "spring",
        damping: 12,
        stiffness: 150,
      },
    },
  }

  // Variants for the subtitle and buttons, with adjusted delays
  const subtitleVariants = {
    hidden: { opacity: 0, y: 20 },
    visible: {
      opacity: 1,
      y: 0,
      transition: { delay: 1.2, duration: 0.8, ease: "easeOut" },
    },
  }

  const buttonContainerVariants = {
    hidden: { opacity: 0 },
    visible: {
      opacity: 1,
      transition: {
        delay: 1.5,
        staggerChildren: 0.2,
      },
    },
  }

  const buttonVariants = {
    hidden: { opacity: 0, scale: 0.8, y: 10 },
    visible: { opacity: 1, scale: 1, y: 0, transition: { duration: 0.5, ease: "backOut" } },
    hover: { scale: 1.05, boxShadow: "0px 0px 15px hsl(var(--primary) / 0.5)" },
    tap: { scale: 0.95 },
  }

  return (
    <section
      ref={ref}
      className="relative h-[calc(100vh-4rem)] flex items-center justify-center text-center overflow-hidden bg-gradient-to-br from-dark to-dark-light text-dark-text"
    >
      <ParticleBackground mouseX={smoothMouseX} mouseY={smoothMouseY} />
      <FloatingElements mouseX={smoothMouseX} mouseY={smoothMouseY} />
      <div className="relative z-10 container px-4 md:px-6">
        <motion.h1
          className="text-4xl md:text-6xl font-bold tracking-tight mb-4 drop-shadow-lg flex flex-wrap justify-center"
          variants={sentenceContainerVariants}
          initial="hidden"
          animate="visible"
        >
          {titleText.split("").map((char, index) => (
            <motion.span
              key={index}
              variants={letterVariants}
              whileHover={{
                y: -10,
                scale: 1.1,
                color: "hsl(var(--primary))",
                textShadow: "0px 0px 10px hsl(var(--primary))",
                transition: { type: "spring", damping: 8, stiffness: 300 },
              }}
              className="inline-block cursor-pointer"
            >
              {char === " " ? "\u00A0" : char}
            </motion.span>
          ))}
        </motion.h1>
        <motion.p
          className="text-lg md:text-xl max-w-3xl mx-auto mb-8 opacity-90 drop-shadow-md"
          variants={subtitleVariants}
          initial="hidden"
          animate="visible"
        >
          {t("hero.subtitle")}
        </motion.p>
        <motion.div
          className="flex flex-col sm:flex-row justify-center gap-4"
          variants={buttonContainerVariants}
          initial="hidden"
          animate="visible"
        >
          <motion.div variants={buttonVariants} whileHover="hover" whileTap="tap">
            <Button asChild size="lg" className="bg-primary text-primary-foreground hover:bg-primary/90">
              <Link href="/donate">{t("hero.donateButton")}</Link>
            </Button>
          </motion.div>
          <motion.div variants={buttonVariants} whileHover="hover" whileTap="tap">
            <Button
              asChild
              size="lg"
              variant="outline"
              className="border-dark-text text-dark-text hover:bg-dark-light/50 hover:border-primary hover:text-primary"
            >
              <Link href="/about">{t("hero.learnButton")}</Link>
            </Button>
          </motion.div>
        </motion.div>
      </div>
    </section>
  )
}
