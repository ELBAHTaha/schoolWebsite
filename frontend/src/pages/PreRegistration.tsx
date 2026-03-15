import { motion } from "framer-motion";
import PublicLayout from "@/components/PublicLayout";
import PreRegistrationForm from "@/components/PreRegistrationForm";

export default function PreRegistration() {
  return (
    <PublicLayout>
      <section className="bg-background py-24">
        <div className="container mx-auto px-4 lg:px-8 text-center">
          <motion.div initial={{ opacity: 0, y: 30 }} animate={{ opacity: 1, y: 0 }} transition={{ duration: 0.6 }}>
            <span className="text-xs font-semibold uppercase tracking-widest text-primary mb-3 block">Inscription</span>
            <h1 className="text-4xl md:text-5xl font-heading font-bold text-foreground mb-4">
              Pré-<span className="text-primary">inscription</span>
            </h1>
            <p className="text-muted-foreground max-w-xl mx-auto">
              Remplissez le formulaire ci-dessous et notre équipe vous contactera rapidement.
            </p>
          </motion.div>
        </div>
      </section>

      <section className="py-20">
        <div className="container mx-auto px-4 lg:px-8 max-w-2xl">
          <motion.div
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.5, delay: 0.2 }}
            className="bg-card rounded-2xl p-8 shadow-card"
          >
            <PreRegistrationForm />
          </motion.div>
        </div>
      </section>
    </PublicLayout>
  );
}
