import { motion } from "framer-motion";
import { CheckCircle2, Target, Eye, Users, Award, Heart } from "lucide-react";
import PublicLayout from "@/components/PublicLayout";
import AnimatedCounter from "@/components/AnimatedCounter";

const teacherImg =
  "https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=1400&q=80";

const team = [
  { name: "Dr. Ahmed Benali", role: "Directeur Général", initials: "AB" },
  { name: "Prof. Marie Laurent", role: "Responsable Pédagogique", initials: "ML" },
  { name: "Prof. John Smith", role: "Enseignant d'Anglais", initials: "JS" },
  { name: "Prof. Sofia Rossi", role: "Enseignante d'Italien", initials: "SR" },
];

const stats = [
  { value: 1200, suffix: "+", label: "Étudiants formés" },
  { value: 25, suffix: "+", label: "Professeurs" },
  { value: 95, suffix: "%", label: "Taux de réussite" },
  { value: 5, suffix: "", label: "Langues" },
];

const fadeUp = {
  initial: { opacity: 0, y: 30 },
  whileInView: { opacity: 1, y: 0 },
  viewport: { once: true },
  transition: { duration: 0.6 },
};

export default function About() {
  return (
    <PublicLayout>
      {/* Header */}
      <section className="bg-background py-24">
        <div className="container mx-auto px-4 lg:px-8 text-center">
          <motion.div {...fadeUp}>
            <span className="text-xs font-semibold uppercase tracking-widest text-primary mb-3 block">
              À Propos
            </span>
            <h1 className="text-4xl md:text-5xl font-heading font-bold text-foreground mb-4">
              Découvrez <span className="text-primary">JEFAL Privé</span>
            </h1>
            <p className="text-muted-foreground max-w-xl mx-auto">
              Une institution dédiée à l'excellence linguistique et académique depuis plus de 10 ans à Settat.
            </p>
          </motion.div>
        </div>
      </section>

      {/* Stats Bar */}
      <section className="py-12 bg-card border-b border-border">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="grid grid-cols-2 md:grid-cols-4 gap-6">
            {stats.map((s) => (
              <div key={s.label} className="text-center">
                <div className="text-3xl md:text-4xl font-heading font-bold text-primary mb-1">
                  <AnimatedCounter end={s.value} suffix={s.suffix} />
                </div>
                <div className="text-sm text-muted-foreground">{s.label}</div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Presentation */}
      <section className="py-20">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="grid lg:grid-cols-2 gap-16 items-center">
            <motion.div {...fadeUp}>
              <span className="text-xs font-semibold uppercase tracking-widest text-primary mb-3 block">Notre Histoire</span>
              <h2 className="text-3xl font-heading font-bold text-foreground mb-6">
                Fondée sur la passion de l'enseignement
              </h2>
              <p className="text-muted-foreground leading-relaxed mb-4">
                L'Académie Américaine Internationale JEFAL Privé a été fondée avec la vision de fournir une éducation linguistique de classe mondiale à Settat, Maroc. Notre programme s'inspire des standards américains tout en s'adaptant aux besoins locaux.
              </p>
              <p className="text-muted-foreground leading-relaxed mb-6">
                Nous proposons des cours de Français, Anglais, Italien, Allemand et Espagnol, ainsi que des préparations aux examens internationaux comme le DELF, TOEFL, IELTS, TEF et TCF.
              </p>
              <ul className="space-y-3">
                {[
                  "Programme américain avec standards internationaux",
                  "Professeurs certifiés et expérimentés",
                  "Classes modernes et bien équipées",
                  "Activités éducatives, sportives et artistiques",
                ].map((item) => (
                  <li key={item} className="flex items-start gap-3 text-sm text-foreground">
                    <CheckCircle2 className="w-5 h-5 text-success flex-shrink-0 mt-0.5" />
                    <span>{item}</span>
                  </li>
                ))}
              </ul>
            </motion.div>
            <motion.div {...fadeUp} transition={{ delay: 0.2 }}>
              <div className="rounded-3xl overflow-hidden shadow-float">
                <img src={teacherImg} alt="Enseignante JEFAL" className="w-full h-[450px] object-cover" />
              </div>
            </motion.div>
          </div>
        </div>
      </section>

      {/* Mission & Vision */}
      <section className="py-20 bg-secondary/50">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="grid md:grid-cols-2 gap-8">
            <motion.div {...fadeUp} className="bg-card rounded-2xl p-8 shadow-card">
              <div className="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center mb-5">
                <Target className="w-7 h-7 text-primary" />
              </div>
              <h3 className="font-heading font-bold text-xl mb-3 text-foreground">Notre Mission</h3>
              <p className="text-muted-foreground leading-relaxed">
                Former des apprenants compétents et confiants dans les langues internationales, en leur fournissant les outils nécessaires pour exceller dans leurs parcours académiques et professionnels.
              </p>
            </motion.div>
            <motion.div {...fadeUp} transition={{ delay: 0.15 }} className="bg-card rounded-2xl p-8 shadow-card">
              <div className="w-14 h-14 rounded-2xl bg-accent/10 flex items-center justify-center mb-5">
                <Eye className="w-7 h-7 text-accent" />
              </div>
              <h3 className="font-heading font-bold text-xl mb-3 text-foreground">Notre Vision</h3>
              <p className="text-muted-foreground leading-relaxed">
                Devenir la référence en formation linguistique au Maroc, reconnue pour l'excellence de nos programmes et la réussite de nos étudiants.
              </p>
            </motion.div>
          </div>
        </div>
      </section>

    </PublicLayout>
  );
}
