import { motion } from "framer-motion";
import { Monitor, Shield, Clock } from "lucide-react";

const features = [
  {
    icon: Monitor,
    title: "Interaction en direct",
    desc: "Mise en pratique directe et conseils immédiats pour améliorer vos performances.",
  },
  {
    icon: Shield,
    title: "Professeurs certifiés",
    desc: "Experts linguistiques avec des méthodes d'enseignement éprouvées et efficaces.",
  },
  {
    icon: Clock,
    title: "Horaires flexibles",
    desc: "Des cours le matin, le soir et le week-end adaptés à votre emploi du temps.",
  },
];

export default function FeaturesBar() {
  return (
    <section className="py-16 bg-card border-b border-border">
      <div className="container mx-auto px-4 lg:px-8">
        <div className="grid md:grid-cols-3 gap-8">
          {features.map((f, i) => (
            <motion.div
              key={f.title}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.5, delay: i * 0.1 }}
              className="flex items-start gap-4 p-6 rounded-2xl bg-secondary/50 hover:bg-secondary transition-colors"
            >
              <div className="w-14 h-14 rounded-2xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                <f.icon className="w-7 h-7 text-primary" />
              </div>
              <div>
                <h3 className="font-heading font-semibold text-foreground mb-1">{f.title}</h3>
                <p className="text-sm text-muted-foreground leading-relaxed">{f.desc}</p>
              </div>
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
