import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { ArrowRight, XCircle } from "lucide-react";

const painPoints = [
  "Manquer des opportunités professionnelles et académiques.",
  "L'anxiété de voyager ou de travailler à l'étranger.",
  "Hésiter lors d'entretiens et de réunions importantes.",
];

export default function PainPoints() {
  return (
    <section className="py-20 lg:py-28">
      <div className="container mx-auto px-4 lg:px-8">
        <div className="max-w-3xl mx-auto text-center">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ duration: 0.6 }}
          >
            <span className="text-xs font-semibold uppercase tracking-widest text-primary mb-3 block">
              Dès Aujourd'hui
            </span>
            <h2 className="text-3xl md:text-4xl font-heading font-bold text-foreground mb-4">
              Ne laissez plus les langues être{" "}
              <span className="text-accent">un obstacle</span>
            </h2>
            <ul className="space-y-4 mt-8 text-left max-w-md mx-auto">
              {painPoints.map((p) => (
                <li key={p} className="flex items-start gap-3">
                  <XCircle className="w-5 h-5 text-destructive flex-shrink-0 mt-0.5" />
                  <span className="text-muted-foreground">{p}</span>
                </li>
              ))}
            </ul>
            <Link
              to="/pre-registration"
              className="inline-flex items-center gap-2 mt-10 px-8 py-4 rounded-full bg-accent text-accent-foreground font-semibold hover:opacity-90 transition-opacity shadow-md"
            >
              <ArrowRight className="w-4 h-4" /> Commencez Maintenant
            </Link>
          </motion.div>
        </div>
      </div>
    </section>
  );
}
