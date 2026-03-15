import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { ArrowRight, CheckCircle2, Star } from "lucide-react";

const reasons = [
  "Professeurs qualifiÃ©s et expÃ©rimentÃ©s du monde entier",
  "Support multilingue disponible 7j/7",
  "Programmes personnalisÃ©s adaptÃ©s Ã  vos besoins",
  "Sessions interactives avec des enseignants spÃ©cialisÃ©s",
  "Apprenez Ã  votre rythme dans un environnement moderne",
];

const compositeImg = "/images/landing/landing-composite.jpg";

export default function WhyChooseUs() {
  return (
    <section className="py-20 lg:py-28 bg-creative-spotlight">
      <div className="container mx-auto px-4 lg:px-8">
        <div className="grid lg:grid-cols-2 gap-16 items-center">
          <motion.div
            initial={{ opacity: 0, x: -30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
            className="relative"
          >
            <div className="rounded-3xl overflow-hidden shadow-float border border-white/60 dark:border-white/10">
              <div
                className="w-full h-[400px] lg:h-[500px] bg-cover bg-no-repeat"
                style={{ backgroundImage: `url(${compositeImg})`, backgroundPosition: "55% 35%" }}
                aria-label="Pourquoi JEFAL PrivÃ©"
              />
            </div>
            <div className="absolute -bottom-6 -right-6 bg-card rounded-2xl p-5 shadow-float">
              <div className="flex items-center gap-2 mb-1">
                {[1, 2, 3, 4, 5].map((s) => (
                  <Star key={s} className="w-4 h-4 fill-accent text-accent" />
                ))}
              </div>
              <div className="text-sm font-heading font-bold text-foreground">+1200 Ã©tudiants satisfaits</div>
            </div>
          </motion.div>

          <motion.div
            initial={{ opacity: 0, x: 30 }}
            whileInView={{ opacity: 1, x: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6, delay: 0.2 }}
          >
            <span className="text-xs font-semibold uppercase tracking-widest text-primary mb-3 block">
              Pourquoi JEFAL PrivÃ© ?
            </span>
            <h2 className="text-3xl md:text-4xl font-heading font-bold text-foreground mb-6">
              Pourquoi choisir <span className="text-primary">JEFAL PrivÃ©</span> pour apprendre les langues ?
            </h2>
            <ul className="space-y-4 mb-8">
              {reasons.map((r) => (
                <li key={r} className="flex items-start gap-3">
                  <CheckCircle2 className="w-5 h-5 text-success flex-shrink-0 mt-0.5" />
                  <span className="text-muted-foreground">{r}</span>
                </li>
              ))}
            </ul>
            <Link
              to="/pre-registration"
              className="inline-flex items-center gap-2 px-7 py-3.5 rounded-full bg-accent text-accent-foreground font-semibold text-sm hover:opacity-90 transition-opacity shadow-md"
            >
              <ArrowRight className="w-4 h-4" /> Commencez Maintenant
            </Link>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
