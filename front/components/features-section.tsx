"use client"

import { Heart, Users, Eye } from "lucide-react"
import { Card } from "@/components/ui/card"
import { useLanguage } from "@/contexts/language-context"
import { motion } from "framer-motion"

export default function FeaturesSection() {
  const { t } = useLanguage()

  const features = [
    {
      icon: Heart,
      title: t("features.card1.title"),
      description: t("features.card1.description"),
    },
    {
      icon: Users,
      title: t("features.card2.title"),
      description: t("features.card2.description"),
    },
    {
      icon: Eye,
      title: t("features.card3.title"),
      description: t("features.card3.description"),
    },
  ]

  const cardContainerVariants = {
    hidden: { opacity: 1 }, // Container itself is visible
    visible: {
      opacity: 1,
      transition: {
        staggerChildren: 0.2, // Stagger animation of child cards
      },
    },
  }

  const cardVariants = {
    hidden: { opacity: 0, y: 60, rotateY: -180, scale: 0.8 },
    visible: {
      opacity: 1,
      y: 0,
      rotateY: 0,
      scale: 1,
      transition: {
        type: "spring",
        stiffness: 80,
        damping: 15,
        duration: 0.8,
      },
    },
  }

  const cardHoverVariants = {
    hover: {
      scale: 1.05,
      boxShadow: "0px 10px 30px -5px hsl(var(--primary) / 0.3)",
      transition: { duration: 0.3, ease: "easeInOut" },
    },
    tap: {
      scale: 0.98,
    },
  }

  return (
    <section className="py-16 md:py-24 bg-dark-light text-dark-text">
      <div className="container text-center">
        <motion.h2
          className="text-3xl md:text-4xl font-bold mb-4"
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true, amount: 0.5 }}
          transition={{ duration: 0.6 }}
        >
          {t("features.title")}
        </motion.h2>
        <motion.p
          className="text-muted-foreground text-lg max-w-2xl mx-auto mb-12"
          initial={{ opacity: 0, y: 20 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true, amount: 0.5 }}
          transition={{ duration: 0.6, delay: 0.2 }}
        >
          {t("features.subtitle")}
        </motion.p>
        <motion.div
          className="grid grid-cols-1 md:grid-cols-3 gap-8"
          variants={cardContainerVariants}
          initial="hidden"
          whileInView="visible"
          viewport={{ once: true, amount: 0.2 }} // Trigger when 20% of the container is visible
        >
          {features.map((feature, index) => (
            <motion.div
              key={index}
              className="h-full"
              variants={cardVariants} // Apply individual card animation here
              // No need for initial/whileInView here as it's handled by parent stagger
            >
              <motion.div className="h-full" variants={cardHoverVariants} whileHover="hover" whileTap="tap">
                <Card className="h-full flex flex-col items-center p-6 text-center shadow-lg transition-shadow duration-300 bg-card border-border perspective">
                  {" "}
                  {/* Added perspective for 3D */}
                  <div className="bg-primary/10 text-primary w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6 transform transition-transform duration-300 group-hover:scale-110">
                    <feature.icon className="w-8 h-8" />
                  </div>
                  <h3 className="text-xl font-semibold mb-3 text-foreground">{feature.title}</h3>
                  <p className="text-muted-foreground">{feature.description}</p>
                </Card>
              </motion.div>
            </motion.div>
          ))}
        </motion.div>
      </div>
    </section>
  )
}
