import { Link } from "react-router-dom";
import { motion } from "framer-motion";
import { ArrowRight, Phone } from "lucide-react";

export default function CTASection() {
  return (
    <section className="py-20 bg-creative-spotlight">
      <div className="container mx-auto px-4 lg:px-8 text-center">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="bg-white/85 border border-white/70 rounded-3xl p-8 md:p-12 shadow-card dark:bg-card/85 dark:border-white/10"
        >
          <h2 className="text-3xl md:text-4xl font-heading font-bold text-foreground mb-4">
            Prêt à commencer votre parcours linguistique ?
          </h2>
          <p className="text-foreground/70 mb-8 max-w-lg mx-auto">
            Inscrivez-vous dès maintenant et rejoignez notre communauté d'apprenants passionnés à Settat.
          </p>
          <div className="flex flex-wrap justify-center gap-4">
            <Link
              to="/pre-registration"
              className="inline-flex items-center gap-2 px-8 py-4 rounded-full bg-accent text-accent-foreground font-semibold hover:opacity-90 transition-opacity shadow-md"
            >
              S'inscrire maintenant <ArrowRight className="w-4 h-4" />
            </Link>
            <Link
              to="/contact"
              className="inline-flex items-center gap-2 px-8 py-4 rounded-full border-2 border-foreground/20 text-foreground font-semibold hover:bg-foreground/10 transition-colors"
            >
              <Phone className="w-4 h-4" /> Nous contacter
            </Link>
          </div>
        </motion.div>
      </div>
    </section>
  );
}

