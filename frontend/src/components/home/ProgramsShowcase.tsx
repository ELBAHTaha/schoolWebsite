import { useState, useCallback } from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { ArrowRight, ChevronLeft, ChevronRight, GraduationCap } from "lucide-react";

const programs = [
  { title: "Anglais Pour (10–15 Ans)", cta: "Rejoignez", image: "/images/programs/anglais-10-15.png", position: "50% 35%" },
  { title: "Anglais Des Affaires", cta: "Commencez L'Anglais Des Affaires", image: "/images/programs/anglais-affaires.png", position: "50% 35%" },
  { title: "Commencez La Préparation Au Bac", cta: "Commencez La Préparation Au Bac", image: "/images/programs/bac.png", position: "50% 55%" },
  { title: "Français — DELF / DALF", cta: "Commencez Le Français", image: "/images/programs/delf-dalf.png", position: "50% 40%" },
  { title: "Cours En Groupe", cta: "Rejoignez Un Groupe", image: "/images/programs/groupe.png", position: "50% 40%" },
  { title: "Préparation Immigration", cta: "Commencez La Préparation", image: "/images/programs/bac.png", position: "50% 55%" },
  { title: "Cours D'Italien", cta: "Commencez L'Italien", image: "/images/programs/italien.png", position: "50% 40%" },
  { title: "Cours D'Allemand", cta: "Commencez L'Allemand", image: "/images/programs/allemand.png", position: "50% 40%" },
];

const VISIBLE = 3;

export default function ProgramsShowcase() {
  const [current, setCurrent] = useState(0);
  const maxIndex = programs.length - VISIBLE;

  const prev = useCallback(() => setCurrent((c) => (c === 0 ? maxIndex : c - 1)), [maxIndex]);
  const next = useCallback(() => setCurrent((c) => (c >= maxIndex ? 0 : c + 1)), [maxIndex]);
  const goTo = useCallback((i: number) => setCurrent(i), []);

  const totalDots = maxIndex + 1;

  return (
    <section className="py-20 lg:py-28 bg-creative-waves">
      <div className="container mx-auto px-4 lg:px-8">
        <div className="flex items-start justify-between mb-14">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
          >
            <span className="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-primary mb-3 border border-primary/20 rounded-full px-4 py-1.5 bg-primary/5">
              <GraduationCap className="w-4 h-4" /> Choisissez Votre Cursus
            </span>
            <h2 className="text-3xl md:text-4xl font-heading font-bold text-foreground">
              Définissez Votre Parcours Éducatif
              <br />
              Parmi Nos <span className="text-primary">Programmes Variés</span>
            </h2>
          </motion.div>

          <Link
            to="/programs"
            className="hidden md:inline-flex items-center gap-2 text-sm font-semibold border border-foreground/20 rounded-full px-6 py-2.5 text-foreground hover:bg-primary hover:text-primary-foreground hover:border-primary transition-colors mt-4"
          >
            <ArrowRight className="w-4 h-4" /> Voir Tout
          </Link>
        </div>

        <div className="relative">
          <button
            onClick={prev}
            className="absolute -left-4 md:-left-6 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center shadow-lg hover:opacity-90 transition-opacity"
          >
            <ChevronLeft className="w-5 h-5" />
          </button>

          <button
            onClick={next}
            className="absolute -right-4 md:-right-6 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center shadow-lg hover:opacity-90 transition-opacity"
          >
            <ChevronRight className="w-5 h-5" />
          </button>

          <div className="overflow-hidden">
            <div
              className="flex transition-transform duration-500 ease-in-out"
              style={{ transform: `translateX(-${current * (100 / VISIBLE)}%)` }}
            >
              {programs.map((p, i) => (
                <div key={i} className="flex-shrink-0 px-3" style={{ width: `${100 / VISIBLE}%` }}>
                  <div className="bg-card rounded-2xl overflow-hidden shadow-card hover:shadow-card-hover transition-all group flex flex-col h-full border border-white/60 dark:border-white/10">
                    <div className="relative overflow-hidden aspect-[16/9]">
                      <img
                        src={p.image}
                        alt={p.title}
                        loading="lazy"
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                        style={{ objectPosition: p.position }}
                      />
                      <div className="absolute inset-0 bg-gradient-to-tr from-white/10 via-transparent to-black/10" />
                    </div>

                    <div className="p-5 flex flex-col items-center text-center flex-1">
                      <h3 className="font-heading font-semibold text-foreground mb-4 text-base">{p.title}</h3>
                      <Link
                        to="/pre-registration"
                        className="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-full bg-accent text-accent-foreground text-sm font-semibold hover:opacity-90 transition-opacity mt-auto shadow-sm"
                      >
                        {p.cta}
                      </Link>
                    </div>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </div>

        <div className="flex items-center justify-center gap-2 mt-8">
          {Array.from({ length: totalDots }).map((_, i) => (
            <button
              key={i}
              onClick={() => goTo(i)}
              className={`w-2.5 h-2.5 rounded-full transition-all ${
                i === current ? "bg-primary w-6" : "bg-muted-foreground/30 hover:bg-muted-foreground/50"
              }`}
            />
          ))}
        </div>

        <div className="text-center mt-6 md:hidden">
          <Link to="/programs" className="inline-flex items-center gap-2 text-sm font-semibold text-primary">
            Voir Tout <ArrowRight className="w-4 h-4" />
          </Link>
        </div>
      </div>
    </section>
  );
}
