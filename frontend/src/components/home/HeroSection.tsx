import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { ArrowRight, CheckCircle2, Globe2, Languages, MessageCircle } from "lucide-react";

const heroBg = "/src/assets/herobg.jpg";

export default function HeroSection() {
  return (
    <section
      className="relative overflow-hidden flex justify-start items-start mt-0 min-h-[600px]"
      style={{
        backgroundImage: `url(${heroBg})`,
        backgroundSize: "cover",
        backgroundPosition: "top",
        backgroundRepeat: "no-repeat",
      }}
    >
      <div className="absolute inset-0 pointer-events-none">
        <div
          className="absolute -top-8 -left-6 h-32 w-32 opacity-70"
          style={{
            backgroundImage: "radial-gradient(hsl(var(--navy)) 1.6px, transparent 1.6px)",
            backgroundSize: "10px 10px",
          }}
        />
      </div>

      <div className="relative container mx-auto px-4 lg:px-8 pt-24 lg:pt-32 pl-0 lg:pl-0">
        <div className="grid lg:grid-cols-[1.05fr_1fr] gap-12 lg:gap-16 items-center">
          
          <motion.div
            initial={{ opacity: 0, x: -40 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.7, delay: 0.1 }}
            className="pl-8 md:pl-16"
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

          {/* Carte droite supprimée */}

        </div>
      </div>
    </section>
  );
}
