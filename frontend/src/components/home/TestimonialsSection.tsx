import { motion } from "framer-motion";
import { Star, Quote } from "lucide-react";

const testimonials = [
  {
    name: "Sara B.",
    role: "Étudiante DELF B2",
    text: "Grâce à JEFAL Privé, j'ai obtenu mon DELF B2 du premier coup. Les professeurs sont exceptionnels et la méthode d'enseignement est très efficace.",
  },
  {
    name: "Youssef M.",
    role: "Étudiant IELTS",
    text: "La préparation à l'IELTS était très complète. J'ai pu obtenir le score nécessaire pour mon admission à l'université au Canada.",
  },
  {
    name: "Amina K.",
    role: "Étudiante Anglais",
    text: "L'ambiance à l'académie est formidable. Les cours sont interactifs et les activités parascolaires sont très enrichissantes.",
  },
];

export default function TestimonialsSection() {
  return (
    <section className="py-20 lg:py-28 bg-creative-waves">
      <div className="container mx-auto px-4 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="text-center mb-14"
        >
          <span className="text-xs font-semibold uppercase tracking-widest text-primary mb-3 block">Avis</span>
          <h2 className="text-3xl md:text-4xl font-heading font-bold text-foreground">
            Les Étudiants de <span className="text-primary">JEFAL Privé</span> en parlent
          </h2>
        </motion.div>

        <div className="grid md:grid-cols-3 gap-6">
          {testimonials.map((t, i) => (
            <motion.div
              key={t.name}
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: i * 0.15 }}
              className="bg-white/85 rounded-2xl p-7 shadow-card relative border border-white/70 dark:bg-card/85 dark:border-white/10"
            >
              <Quote className="w-8 h-8 text-primary/10 absolute top-6 right-6" />
              <div className="flex items-center gap-3 mb-4">
                <div className="w-11 h-11 rounded-full bg-primary/10 flex items-center justify-center text-primary font-heading font-bold text-sm">
                  {t.name[0]}
                </div>
                <div>
                  <div className="font-semibold text-sm text-foreground">{t.name}</div>
                  <div className="text-xs text-muted-foreground">{t.role}</div>
                </div>
              </div>
              <p className="text-sm text-muted-foreground leading-relaxed mb-4">{t.text}</p>
              <div className="flex gap-1">
                {[1, 2, 3, 4, 5].map((s) => (
                  <Star key={s} className="w-4 h-4 fill-accent text-accent" />
                ))}
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
