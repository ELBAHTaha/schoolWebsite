import { useState } from "react";
import { motion } from "framer-motion";
import { Calendar, Monitor, Clock, Crown, ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";

const durations = [
  { months: 3, label: "(X3) Month", sub: "x3 Mois", bonus: "", price: 900 },
  { months: 6, label: "(X6) Month", sub: "+ 1 Mois Gratuit", bonus: "Le plus demandé", price: 1500 },
  { months: 12, label: "(X12) Month", sub: "+ 2 Mois Gratuits", bonus: "", price: 2400 },
];

const sessions = [
  { count: 2, label: "(X2) Sessions", sub: "Petit groupe", bonus: "Le plus demandé", multiplier: 1 },
  { count: 0, label: "Cours particuliers", sub: "Prof particulier", bonus: "", multiplier: 2.2, isVip: true },
];

export default function PackageConfigurator() {
  const [selectedDuration, setSelectedDuration] = useState(0);
  const [selectedSession, setSelectedSession] = useState(0);

  const basePrice = durations[selectedDuration].price;
  const multiplier = sessions[selectedSession].multiplier;
  const totalPrice = Math.round(basePrice * multiplier);
  const originalPrice = Math.round(totalPrice / 0.6);
  const months = durations[selectedDuration].months;

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
          <div className="w-8 h-8 rounded-lg bg-accent text-accent-foreground flex items-center justify-center text-sm font-bold">1</div>
          <div className="flex-1 h-1 bg-accent/30 mx-1" />
          <div className="w-8 h-8 rounded-lg bg-muted text-muted-foreground flex items-center justify-center text-sm font-bold">2</div>
          <div className="flex-1 h-1 bg-muted mx-1" />
          <div className="w-8 h-8 rounded-lg bg-muted text-muted-foreground flex items-center justify-center text-sm font-bold">3</div>
        </div>

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
            <Link
              to="/pre-registration"
              className="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-accent text-accent-foreground font-semibold text-sm hover:opacity-90 transition-opacity"
            >
              Commencez maintenant <ArrowRight className="w-4 h-4" />
            </Link>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
