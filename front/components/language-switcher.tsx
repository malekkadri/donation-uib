"use client"

import { useLanguage } from "@/contexts/language-context"
import { Button } from "@/components/ui/button"

export default function LanguageSwitcher() {
  const { language, setLanguage } = useLanguage()

  return (
    <div className="flex items-center space-x-2">
      <Button
        variant={language === "en" ? "default" : "outline"}
        size="sm"
        onClick={() => setLanguage("en")}
        className="w-10"
      >
        EN
      </Button>
      <Button
        variant={language === "fr" ? "default" : "outline"}
        size="sm"
        onClick={() => setLanguage("fr")}
        className="w-10"
      >
        FR
      </Button>
    </div>
  )
}
