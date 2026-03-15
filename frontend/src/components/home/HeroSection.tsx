import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { ArrowRight, CheckCircle2, Globe2, Languages, MessageCircle } from "lucide-react";

const heroImg = "/images/landing/hero.png";

export default function HeroSection() {
  return (
    <section className="relative overflow-hidden bg-creative-sky min-h-[90vh] flex items-center">
      <div className="absolute inset-0 pointer-events-none">
        <div
          className="absolute -top-8 -left-6 h-32 w-32 opacity-70"
          style={{
            backgroundImage: "radial-gradient(hsl(var(--navy)) 1.6px, transparent 1.6px)",
            backgroundSize: "10px 10px",
          }}
        />
        <div className="absolute -top-10 right-0 h-48 w-48 bg-[radial-gradient(circle,rgba(255,255,255,0.8)_0%,transparent_60%)]" />
        <div className="absolute -bottom-24 right-[-10%] w-[70%] h-[45%] opacity-90">
          <svg viewBox="0 0 900 300" className="w-full h-full">
            <path
              d="M0 180c90-40 180-60 270-40 90 20 150 60 240 50 90-10 150-60 240-70 90-10 150 20 150 20v110H0z"
              fill="hsl(var(--navy))"
              opacity="0.9"
            />
            <path
              d="M0 210c90-40 180-60 270-40 90 20 150 60 240 50 90-10 150-60 240-70 90-10 150 20 150 20v80H0z"
              fill="hsl(var(--gold))"
              opacity="0.9"
            />
          </svg>
        </div>
      </div>

      <div className="relative container mx-auto px-4 lg:px-8 py-16 lg:py-24">
        <div className="grid lg:grid-cols-[1.05fr_1fr] gap-12 lg:gap-16 items-center">
          <motion.div
            initial={{ opacity: 0, x: -40 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.7, delay: 0.1 }}
          >
            <h1 className="text-4xl md:text-5xl lg:text-[3.6rem] font-heading font-bold text-foreground leading-[1.1] mb-6">
              Apprenez les langues à <span className="text-primary">JEFAL Privé</span>
              <br />
              <span className="text-accent">votre réussite commence ici</span>
            </h1>

            <ul className="space-y-3 mb-8">
              {[
                "Cours de langues avec des experts certifiés",
                "Sessions interactives avec les meilleurs enseignants",
                "Préparations DELF, TOEFL, IELTS et plus encore",
              ].map((item) => (
                <li key={item} className="flex items-start gap-3 text-muted-foreground">
                  <span className="mt-0.5 h-5 w-5 rounded-full bg-white shadow-sm flex items-center justify-center">
                    <CheckCircle2 className="w-4 h-4 text-primary" />
                  </span>
                  <span className="text-[15px]">{item}</span>
                </li>
              ))}
            </ul>

            <div className="flex flex-wrap gap-4">
              <Link
                to="/pre-registration"
                className="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-accent text-accent-foreground font-semibold text-sm hover:opacity-90 transition-opacity shadow-lg"
              >
                Commencez Maintenant <ArrowRight className="w-4 h-4" />
              </Link>
              <Link
                to="/programs"
                className="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-white text-foreground font-semibold text-sm border border-border hover:border-primary hover:text-primary transition-colors shadow-sm"
              >
                Nos Programmes
              </Link>
            </div>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, x: 40 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.7, delay: 0.3 }}
            className="relative"
          >
            <div className="relative rounded-[28px] overflow-hidden shadow-[0_25px_60px_rgba(17,24,39,0.18)]">
              <div
                className="w-full h-[420px] lg:h-[520px] bg-cover bg-no-repeat"
                style={{
                  backgroundImage: `url(${heroImg})`,
                  backgroundPosition: "60% 20%",
                }}
                aria-label="Étudiants en cours"
              />
              <div className="absolute inset-0 bg-gradient-to-tr from-white/20 via-transparent to-transparent" />
            </div>

            <div className="absolute -top-6 right-6 h-14 w-14 rounded-full bg-white shadow-lg flex items-center justify-center">
              <Globe2 className="w-6 h-6 text-primary" />
            </div>
            <div className="absolute top-16 right-0 h-12 w-12 rounded-full bg-white shadow-lg flex items-center justify-center">
              <Languages className="w-5 h-5 text-primary" />
            </div>
            <div className="absolute top-28 right-10 h-12 w-12 rounded-full bg-white shadow-lg flex items-center justify-center">
              <MessageCircle className="w-5 h-5 text-[hsl(var(--gold))]" />
            </div>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
