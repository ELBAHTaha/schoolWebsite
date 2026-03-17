import { useState } from "react";
import { motion } from "framer-motion";
import { Calendar, Monitor, Clock, Crown, ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";
import PreRegistrationForm from "@/components/PreRegistrationForm";

const durations = [
  { months: 3, label: "(X3) Mois", sub: "x3 Mois", bonus: "", price: 900 },
  { months: 6, label: "(X6) Mois", sub: "+ 1 Mois Gratuit", bonus: "Le plus demandé", price: 1500 },
  { months: 12, label: "(X12) Mois", sub: "+ 2 Mois Gratuits", bonus: "", price: 2400 },
];

const sessions = [
  { count: 2, label: "(X2) Sessions", sub: "Petit groupe", bonus: "Le plus demandé", multiplier: 1 },
  { count: 0, label: "Cours particuliers", sub: "Prof particulier", bonus: "", multiplier: 2.2, isVip: true },
];

interface PackageConfiguratorProps {
  initialProgram?: string | null;
}

export default function PackageConfigurator({ initialProgram }: PackageConfiguratorProps) {
  const [currentStep, setCurrentStep] = useState(1);
  const [selectedDuration, setSelectedDuration] = useState(0);
  const [selectedSession, setSelectedSession] = useState(0);
  const [selectedLevel, setSelectedLevel] = useState("B1");

  const basePrice = durations[selectedDuration].price;
  const multiplier = sessions[selectedSession].multiplier;
  const totalPrice = Math.round(basePrice * multiplier);
  const originalPrice = Math.round(totalPrice / 0.6);
  const months = durations[selectedDuration].months;

  const isCompletedStep = (step: number) => currentStep > step;
  const isActiveStep = (step: number) => currentStep === step;

  const handleNextStep = () => setCurrentStep((prev) => Math.min(prev + 1, 3));
  const handlePrevStep = () => setCurrentStep((prev) => Math.max(prev - 1, 1));

  return (
    <section className="py-20 lg:py-28 bg-background">
      <div className="container mx-auto px-4 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="text-center mb-12"
        >
          <span className="inline-flex items-center gap-2 px-4 py-2 rounded-full border border-border text-sm font-medium text-foreground mb-6">
            <Calendar className="w-4 h-4 text-primary" />
            Concevez Votre Forfait
          </span>
          <h2 className="text-3xl md:text-4xl font-heading font-bold text-foreground mb-4">
            Concevez Votre Forfait Personnalisé Pour{" "}
            <span className="text-primary">Apprendre Les Langues</span>
          </h2>
          <p className="text-muted-foreground max-w-2xl mx-auto">
            JEFAL Privé propose le programme idéal qui s'adapte à vos objectifs et à votre emploi du temps, à des prix compétitifs.
          </p>
        </motion.div>

        {/* Steps indicator */}
        <div className="flex items-center max-w-2xl mx-auto mb-12">
          <div
            className={`w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold ${
              isActiveStep(1)
                ? "bg-accent text-accent-foreground"
                : isCompletedStep(1)
                ? "bg-primary text-primary-foreground"
                : "bg-muted text-muted-foreground"
            }`}
          >
            1
          </div>
          <div className={`flex-1 h-1 mx-1 ${isCompletedStep(1) ? "bg-accent" : "bg-muted"}`} />
          <div
            className={`w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold ${
              isActiveStep(2)
                ? "bg-accent text-accent-foreground"
                : isCompletedStep(2)
                ? "bg-primary text-primary-foreground"
                : "bg-muted text-muted-foreground"
            }`}
          >
            2
          </div>
          <div className={`flex-1 h-1 mx-1 ${isCompletedStep(2) ? "bg-accent" : "bg-muted"}`} />
          <div
            className={`w-8 h-8 rounded-lg flex items-center justify-center text-sm font-bold ${
              isActiveStep(3)
                ? "bg-accent text-accent-foreground"
                : isCompletedStep(3)
                ? "bg-primary text-primary-foreground"
                : "bg-muted text-muted-foreground"
            }`}
          >
            3
          </div>
        </div>

        {currentStep === 1 && (
          <div className="grid lg:grid-cols-3 gap-6 max-w-5xl mx-auto">
            {/* Duration */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.4 }}
              className="bg-card rounded-2xl shadow-card p-6"
            >
              <div className="flex items-center gap-3 mb-6 pb-4 border-b border-border">
                <div className="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                  <Calendar className="w-5 h-5 text-primary" />
                </div>
                <h3 className="font-heading font-semibold text-foreground">Durée Du Forfait</h3>
              </div>
              <div className="space-y-3">
                {durations.map((d, i) => (
                  <button
                    key={d.months}
                    onClick={() => setSelectedDuration(i)}
                    className={`w-full rounded-xl border-2 p-4 text-left transition-all ${
                      selectedDuration === i
                        ? "border-primary bg-accent/10 shadow-md"
                        : "border-border hover:border-primary/40"
                    }`}
                  >
                    <div className="flex items-center gap-2 mb-1">
                      <Calendar className="w-4 h-4 text-primary" />
                      <span className="font-semibold text-primary text-sm">{d.label}</span>
                      {d.bonus && (
                        <span className="text-[10px] font-bold bg-accent text-accent-foreground px-2 py-0.5 rounded ml-auto">
                          {d.bonus}
                        </span>
                      )}
                    </div>
                    <p className="text-xs font-semibold text-accent ml-6">{d.sub}</p>
                  </button>
                ))}
              </div>
            </motion.div>

            {/* Sessions */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.4, delay: 0.1 }}
              className="bg-card rounded-2xl shadow-card p-6"
            >
              <div className="flex items-center gap-3 mb-6 pb-4 border-b border-border">
                <div className="w-10 h-10 rounded-xl bg-primary/10 flex items-center justify-center">
                  <Monitor className="w-5 h-5 text-primary" />
                </div>
                <h3 className="font-heading font-semibold text-foreground">Sessions Par Semaine</h3>
              </div>
              <div className="space-y-3">
                {sessions.map((s, i) => (
                  <button
                    key={i}
                    onClick={() => setSelectedSession(i)}
                    className={`w-full rounded-xl border-2 p-4 text-left transition-all ${
                      selectedSession === i
                        ? "border-primary bg-accent/10 shadow-md"
                        : "border-border hover:border-primary/40"
                    }`}
                  >
                    <div className="flex items-center gap-2 mb-1">
                      {s.isVip ? (
                        <Crown className="w-4 h-4 text-accent" />
                      ) : (
                        <Monitor className="w-4 h-4 text-primary" />
                      )}
                      <span className={`font-semibold text-sm ${s.isVip ? "text-accent" : "text-primary"}`}>
                        {s.label}
                      </span>
                      {s.bonus && (
                        <span className="text-[10px] font-bold bg-accent text-accent-foreground px-2 py-0.5 rounded ml-auto">
                          {s.bonus}
                        </span>
                      )}
                    </div>
                    <p className="text-xs font-semibold text-muted-foreground ml-6">{s.sub}</p>
                  </button>
                ))}
              </div>
            </motion.div>

            {/* Price Summary */}
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.4, delay: 0.2 }}
              className="bg-primary rounded-2xl p-6 text-primary-foreground flex flex-col"
            >
              <div className="flex items-center justify-between mb-4">
                <h3 className="font-heading font-semibold text-lg">Prix Total</h3>
                <span className="text-sm font-bold text-accent bg-primary-foreground/10 px-3 py-1 rounded-full">
                  40% OFF
                </span>
              </div>
              <div className="mb-6">
                <div className="flex items-baseline gap-2">
                  <span className="text-5xl font-heading font-bold">{totalPrice}</span>
                  <span className="text-sm opacity-80">MAD/ {months} Mois</span>
                </div>
              </div>
              <hr className="border-primary-foreground/20 mb-5" />
              <div className="space-y-3 mb-8 flex-1">
                <div className="flex items-center gap-3 text-sm">
                  <Calendar className="w-4 h-4 opacity-80" />
                  Programme Sur {months} Mois
                </div>
                <div className="flex items-center gap-3 text-sm">
                  <Monitor className="w-4 h-4 opacity-80" />
                  {sessions[selectedSession].isVip ? "Cours Particuliers" : "2 Sessions Par Semaine"}
                </div>
                <div className="flex items-center gap-3 text-sm">
                  <Clock className="w-4 h-4 opacity-80" />
                  Sessions De 1 H 30
                </div>
              </div>
              <button
                type="button"
                onClick={handleNextStep}
                className="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-accent text-accent-foreground font-semibold text-sm hover:opacity-90 transition-opacity"
              >
                Suivant <ArrowRight className="w-4 h-4" />
              </button>
            </motion.div>
          </div>
        )}

        {currentStep === 2 && (
          <div className="max-w-4xl mx-auto">
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.4 }}
              className="bg-card rounded-2xl shadow-card p-6"
            >
              <h3 className="font-heading font-semibold text-2xl text-foreground mb-4">Niveau de langue actuel</h3>
              <p className="text-sm text-muted-foreground mb-6">
                Sélectionnez votre niveau actuel pour que nous puissions mieux adapter votre programme.
              </p>
              <div className="grid grid-cols-2 sm:grid-cols-4 gap-4">
                {[
                  { key: "A0", label: "Débutant absolu" },
                  { key: "A1", label: "Débutant" },
                  { key: "A2", label: "Élémentaire" },
                  { key: "B1", label: "Intermédiaire" },
                  { key: "B2", label: "Intermédiaire avancé" },
                  { key: "C1", label: "Avancé" },
                  { key: "C2", label: "Maîtrise" },
                ].map((level) => (
                  <button
                    key={level.key}
                    type="button"
                    onClick={() => setSelectedLevel(level.key)}
                    className={`w-full rounded-2xl border-2 p-6 text-left transition-all ${
                      selectedLevel === level.key
                        ? "border-primary bg-accent/10 shadow-md"
                        : "border-border hover:border-primary/40"
                    }`}
                  >
                    <div className="flex items-start justify-between gap-3">
                      <div>
                        <div className="text-sm font-semibold text-primary">Niveau {level.key}</div>
                        <div className="text-xs font-semibold text-muted-foreground">{level.label}</div>
                      </div>
                      <div className="px-2.5 py-1 rounded-full text-xs font-bold bg-primary/10 text-primary">
                        {level.key}
                      </div>
                    </div>
                  </button>
                ))}
              </div>

              <div className="mt-8 flex items-center justify-between">
                <button type="button" onClick={handlePrevStep} className="text-sm font-semibold text-primary hover:underline">
                  Précédent
                </button>
                <button
                  type="button"
                  onClick={handleNextStep}
                  className="inline-flex items-center gap-2 rounded-full bg-accent px-6 py-3 text-sm font-semibold text-accent-foreground hover:opacity-90 transition-opacity"
                >
                  Suivant <ArrowRight className="w-4 h-4" />
                </button>
              </div>
            </motion.div>
          </div>
        )}

        {currentStep === 3 && (
          <div className="max-w-4xl mx-auto">
            <motion.div
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.4 }}
              className="bg-card rounded-2xl shadow-card p-6"
            >
              <h3 className="font-heading font-semibold text-2xl text-foreground mb-4">Pré-inscription</h3>
              <p className="text-sm text-muted-foreground mb-6">
                Remplissez le formulaire ci-dessous et notre équipe vous contactera rapidement.
              </p>

              <PreRegistrationForm onBack={handlePrevStep} initialProgram={initialProgram} />
            </motion.div>
          </div>
        )}
      </div>
    </section>
  );
}

