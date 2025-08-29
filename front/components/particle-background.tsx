"use client"

import { useEffect, useRef, useState } from "react"
import { motion, type MotionValue } from "framer-motion"

interface ParticleBackgroundProps {
  mouseX?: MotionValue<number>
  mouseY?: MotionValue<number>
}

export default function ParticleBackground({ mouseX, mouseY }: ParticleBackgroundProps) {
  const canvasRef = useRef<HTMLCanvasElement>(null)
  const [isClient, setIsClient] = useState(false)

  useEffect(() => {
    setIsClient(true)
  }, [])

  useEffect(() => {
    if (!isClient) return

    const canvas = canvasRef.current
    if (!canvas) return

    const ctx = canvas.getContext("2d")
    if (!ctx) return

    let animationFrameId: number

    const resizeCanvas = () => {
      canvas.width = Math.min(window.innerWidth, document.documentElement.clientWidth)
      canvas.height = window.innerHeight
    }

    resizeCanvas()
    window.addEventListener("resize", resizeCanvas)

    const particles: Array<{
      x: number
      y: number
      originX: number
      originY: number
      vx: number
      vy: number
      size: number
      opacity: number
      depth: number // For parallax
    }> = []

    // Create particles
    for (let i = 0; i < 50; i++) {
      // Increased particle count
      const x = Math.random() * canvas.width
      const y = Math.random() * canvas.height
      particles.push({
        x: x,
        y: y,
        originX: x,
        originY: y,
        vx: (Math.random() - 0.5) * 0.2, // Slower base velocity
        vy: (Math.random() - 0.5) * 0.2,
        size: Math.random() * 2 + 0.5, // Smaller base size
        opacity: Math.random() * 0.2 + 0.05, // More subtle opacity
        depth: Math.random() * 0.5 + 0.1, // Random depth for parallax
      })
    }

    const xRange = window.innerWidth / 2
    const yRange = window.innerHeight / 2

    const animate = () => {
      ctx.clearRect(0, 0, canvas.width, canvas.height)

      const currentMouseX = mouseX?.get() ?? xRange
      const currentMouseY = mouseY?.get() ?? yRange

      particles.forEach((particle) => {
        // Parallax effect
        const parallaxX = (currentMouseX - xRange) * particle.depth * 0.05
        const parallaxY = (currentMouseY - yRange) * particle.depth * 0.05

        particle.x = particle.originX + parallaxX + particle.vx * performance.now() * 0.01
        particle.y = particle.originY + parallaxY + particle.vy * performance.now() * 0.01

        // Wrap around edges (relative to origin + parallax)
        if (particle.x < -particle.size) particle.originX = canvas.width + particle.size
        if (particle.x > canvas.width + particle.size) particle.originX = -particle.size
        if (particle.y < -particle.size) particle.originY = canvas.height + particle.size
        if (particle.y > canvas.height + particle.size) particle.originY = -particle.size

        // Draw particle
        ctx.beginPath()
        ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2)
        ctx.fillStyle = `rgba(255, 255, 255, ${particle.opacity})` // Use theme variable if possible
        ctx.fill()
      })

      // Draw connections (optional, can be performance intensive)
      for (let i = 0; i < particles.length; i++) {
        for (let j = i + 1; j < particles.length; j++) {
          const p1 = particles[i]
          const p2 = particles[j]
          const dx = p1.x - p2.x
          const dy = p1.y - p2.y
          const distance = Math.sqrt(dx * dx + dy * dy)

          if (distance < 100) {
            // Increased connection distance
            ctx.beginPath()
            ctx.moveTo(p1.x, p1.y)
            ctx.lineTo(p2.x, p2.y)
            ctx.strokeStyle = `rgba(255, 255, 255, ${0.05 * (1 - distance / 100)})` // More subtle lines
            ctx.lineWidth = 0.5 // Thinner lines
            ctx.stroke()
          }
        }
      }

      animationFrameId = requestAnimationFrame(animate)
    }

    animate()

    return () => {
      window.removeEventListener("resize", resizeCanvas)
      cancelAnimationFrame(animationFrameId)
    }
  }, [isClient, mouseX, mouseY]) // Add mouseX, mouseY as dependencies

  if (!isClient) return null // Render nothing on the server

  return (
    <motion.canvas
      ref={canvasRef}
      className="absolute inset-0 pointer-events-none w-full h-full"
      style={{ maxWidth: "100vw" }}
      initial={{ opacity: 0 }}
      animate={{ opacity: 1 }}
      transition={{ duration: 2 }}
    />
  )
}
