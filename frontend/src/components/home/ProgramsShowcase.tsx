import { useState, useCallback } from "react";
import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { ArrowRight, ChevronLeft, ChevronRight, GraduationCap } from "lucide-react";

const programTeenage =
  "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=1400&q=80";
const programBusiness =
  "https://images.unsplash.com/photo-1556761175-b413da4baf72?auto=format&fit=crop&w=1400&q=80";
const programBac =
  "https://images.unsplash.com/photo-1506905925346-21bda4d32df4?auto=format&fit=crop&w=1400&q=80";
const programFrench =
  "https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1400&q=80";
const programGroup =
  "https://images.unsplash.com/photo-1511632765486-a01980e01a18?auto=format&fit=crop&w=1400&q=80";
const programImmigration =
  "https://images.unsplash.com/photo-1539635278303-d4002c07eae3?auto=format&fit=crop&w=1400&q=80";
const programItalian =
  "https://images.unsplash.com/photo-1523906834658-6e24ef2386f9?auto=format&fit=crop&w=1400&q=80";
const programGerman =
  "https://images.unsplash.com/photo-1467269204594-9661b134dd2b?auto=format&fit=crop&w=1400&q=80";

const programs = [
  { title: "Anglais Pour (10–15 Ans)", cta: "Rejoignez", image: programTeenage },
  { title: "Anglais Des Affaires", cta: "Commencez L'Anglais Des Affaires", image: programBusiness },
  { title: "Commencez La Préparation Au Bac", cta: "Commencez La Préparation Au Bac", image: programBac },
  { title: "Français — DELF / DALF", cta: "Commencez Le Français", image: programFrench },
  { title: "Cours En Groupe", cta: "Rejoignez Un Groupe", image: programGroup },
  { title: "Préparation Immigration", cta: "Commencez La Préparation", image: programImmigration },
  { title: "Cours D'Italien", cta: "Commencez L'Italien", image: programItalian },
  { title: "Cours D'Allemand", cta: "Commencez L'Allemand", image: programGerman },
];

const VISIBLE = 3;

export default function ProgramsShowcase() {
  const [current, setCurrent] = useState(0);
  const maxIndex = programs.length - VISIBLE;

  const prev = useCallback(() => setCurrent((c) => c === 0 ? maxIndex : c - 1), [maxIndex]);
  const next = useCallback(() => setCurrent((c) => c >= maxIndex ? 0 : c + 1), [maxIndex]);
  const goTo = useCallback((i: number) => setCurrent(i), []);

  const totalDots = maxIndex + 1;

  return (
    <section className="py-20 lg:py-28 bg-secondary/50">
      <div className="container mx-auto px-4 lg:px-8">
        {/* Header */}
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

        {/* Carousel */}
        <div className="relative">
          {/* Arrow Left */}
          <button
            onClick={prev}
            className="absolute -left-4 md:-left-6 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center shadow-lg hover:opacity-90 transition-opacity"
          >
            <ChevronLeft className="w-5 h-5" />
          </button>

          {/* Arrow Right */}
          <button
            onClick={next}
            className="absolute -right-4 md:-right-6 top-1/2 -translate-y-1/2 z-10 w-10 h-10 rounded-full bg-primary text-primary-foreground flex items-center justify-center shadow-lg hover:opacity-90 transition-opacity"
          >
            <ChevronRight className="w-5 h-5" />
          </button>

          {/* Cards Container */}
          <div className="overflow-hidden">
            <div
              className="flex transition-transform duration-500 ease-in-out"
              style={{ transform: `translateX(-${current * (100 / VISIBLE)}%)` }}
            >
              {programs.map((p, i) => (
                <div
                  key={i}
                  className="flex-shrink-0 px-3"
                  style={{ width: `${100 / VISIBLE}%` }}
                >
                  <div className="bg-card rounded-2xl overflow-hidden shadow-card hover:shadow-card-hover transition-all group flex flex-col h-full">
                    {/* Image */}
                    <div className="relative overflow-hidden aspect-[4/3]">
                      <img
                        src={p.image}
                        alt={p.title}
                        loading="lazy"
                        className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
                      />
                      {/* Blue diagonal overlay */}
                      <div className="absolute inset-0 bg-black/10 opacity-0 group-hover:opacity-20 transition-opacity duration-300" />
                    </div>

                    {/* Content */}
                    <div className="p-5 flex flex-col items-center text-center flex-1">
                      <h3 className="font-heading font-semibold text-foreground mb-4 text-base">
                        {p.title}
                      </h3>
                      <Link
                        to="/pre-registration"
                        className="w-full inline-flex items-center justify-center px-5 py-2.5 rounded-full bg-accent text-accent-foreground text-sm font-semibold hover:opacity-90 transition-opacity mt-auto"
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

        {/* Dots */}
        <div className="flex items-center justify-center gap-2 mt-8">
          {Array.from({ length: totalDots }).map((_, i) => (
            <button
              key={i}
              onClick={() => goTo(i)}
              className={`w-2.5 h-2.5 rounded-full transition-all ${
                i === current
                  ? "bg-primary w-6"
                  : "bg-muted-foreground/30 hover:bg-muted-foreground/50"
              }`}
            />
          ))}
        </div>

        {/* Mobile link */}
        <div className="text-center mt-6 md:hidden">
          <Link
            to="/programs"
            className="inline-flex items-center gap-2 text-sm font-semibold text-primary"
          >
            Voir Tout <ArrowRight className="w-4 h-4" />
          </Link>
        </div>
      </div>
    </section>
  );
}
