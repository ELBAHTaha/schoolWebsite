import PublicLayout from "@/components/PublicLayout";
import HeroSection from "@/components/home/HeroSection";
import FeaturesBar from "@/components/home/FeaturesBar";
import PainPoints from "@/components/home/PainPoints";
import ProgramsShowcase from "@/components/home/ProgramsShowcase";
import PackageConfigurator from "@/components/home/PackageConfigurator";
import WhyChooseUs from "@/components/home/WhyChooseUs";
import TestimonialsSection from "@/components/home/TestimonialsSection";
import FAQSection from "@/components/home/FAQSection";
import CTASection from "@/components/home/CTASection";

export default function Index() {
  return (
    <PublicLayout>
      <HeroSection />
      <FeaturesBar />
      <PainPoints />
      <ProgramsShowcase />
      <PackageConfigurator />
      <WhyChooseUs />
      <TestimonialsSection />
      <FAQSection />
      <CTASection />
    </PublicLayout>
  );
}
