import HeroSection from "@/components/hero-section"
import FeaturesSection from "@/components/features-section"
import TopDonors from "@/components/top-donors"
import CtaSection from "@/components/cta-section"
import ApiDebug from "@/components/api-debug"

export default function Home() {
  return (
    <div>
      <HeroSection />
      <FeaturesSection />
      <TopDonors />
      <CtaSection />
      <div className="container py-12">
      </div>
    </div>
  )
}
