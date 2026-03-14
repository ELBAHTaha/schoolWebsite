import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { ArrowRight, CheckCircle2, Star, Users } from "lucide-react";

const levels = ["A1", "A2", "B1", "B2", "C1", "C2"];
const heroImg =
  "https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1400&q=80";

export default function HeroSection() {
  return (
    <section className="relative overflow-hidden bg-background min-h-[90vh] flex items-center">
      <div className="relative container mx-auto px-4 lg:px-8 py-16 lg:py-24">
        <div className="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
          {/* Left Content */}
          <motion.div
            initial={{ opacity: 0, x: -40 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.7, delay: 0.1 }}
          >
            <h1 className="text-4xl md:text-5xl lg:text-[3.5rem] font-heading font-bold text-foreground leading-[1.15] mb-6">
              Apprenez les langues{" "}
              <span className="text-primary">à JEFAL Privé</span>
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
                  <CheckCircle2 className="w-5 h-5 text-primary flex-shrink-0 mt-0.5" />
                  <span className="text-[15px]">{item}</span>
                </li>
              ))}
            </ul>

            <div className="flex flex-wrap gap-4 mb-8">
              <Link
                to="/pre-registration"
                className="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-accent text-accent-foreground font-semibold text-sm hover:opacity-90 transition-opacity shadow-md"
              >
                <ArrowRight className="w-4 h-4" /> Commencez Maintenant
              </Link>
              <Link
                to="/programs"
                className="inline-flex items-center gap-2 px-7 py-3.5 rounded-full border-2 border-border text-foreground font-semibold text-sm hover:border-primary hover:text-primary transition-colors"
              >
                Nos Programmes
              </Link>
            </div>

            {/* Level badges */}
            <div className="flex items-center gap-2">
              {levels.map((lvl) => (
                <span
                  key={lvl}
                  className="px-3 py-1 rounded-full text-xs font-bold bg-accent/10 text-accent border border-accent/20"
                >
                  {lvl}
                </span>
              ))}
            </div>
          </motion.div>

          {/* Right - Image + Floating Cards */}
          <motion.div
            initial={{ opacity: 0, x: 40 }}
            animate={{ opacity: 1, x: 0 }}
            transition={{ duration: 0.7, delay: 0.3 }}
            className="relative"
          >
            <div className="relative rounded-3xl overflow-hidden shadow-float">
              <img
                src={heroImg}
                alt="Étudiants en cours"
                loading="lazy"
                className="w-full h-[420px] lg:h-[480px] object-cover"
              />
              <div className="absolute inset-0 bg-black/10" />
            </div>

            {/* Floating Card - Places */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: 0.8 }}
              className="absolute -top-4 -right-4 lg:right-4 bg-card rounded-2xl p-4 shadow-float animate-float"
            >
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                  <Users className="w-5 h-5 text-primary" />
                </div>
                <div>
                  <div className="text-xs text-muted-foreground font-medium">Places Réservées</div>
                  <div className="text-lg font-heading font-bold text-primary">85%</div>
                </div>
              </div>
            </motion.div>

            {/* Floating Card - Rating */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.5, delay: 1 }}
              className="absolute -bottom-4 left-4 lg:-left-4 bg-card rounded-2xl p-4 shadow-float"
            >
              <div className="flex items-center gap-3">
                <div className="w-10 h-10 rounded-xl bg-accent/10 flex items-center justify-center">
                  <Star className="w-5 h-5 text-accent fill-accent" />
                </div>
                <div>
                  <div className="text-xs text-muted-foreground font-medium">Évaluation</div>
                  <div className="text-lg font-heading font-bold text-foreground">4.9/5</div>
                </div>
              </div>
            </motion.div>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
