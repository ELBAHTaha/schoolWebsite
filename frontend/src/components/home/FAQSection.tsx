import { useState } from "react";
import { motion } from "framer-motion";
import { ChevronDown } from "lucide-react";

const faqs = [
  {
    q: "Comment réserver ma première séance ?",
    a: "Choisissez votre programme sur notre page programmes, remplissez le formulaire de pré-inscription, et notre équipe vous contactera pour organiser votre première séance.",
  },
  {
    q: "Quels modes de paiement acceptez-vous ?",
    a: "Nous acceptons le paiement en espèces, par virement bancaire, ainsi que par CashPlus et WafaCash. Contactez-nous pour plus de détails.",
  },
  {
    q: "Puis-je modifier mon planning ?",
    a: "Oui, vous pouvez ajuster votre emploi du temps en contactant notre secrétariat au moins 24h à l'avance.",
  },
  {
    q: "Le matériel de cours est-il inclus ?",
    a: "Oui, votre inscription inclut l'accès à tous les supports pédagogiques : manuels, fiches, exercices et accès à notre plateforme numérique.",
  },
  {
    q: "Dois-je passer un test de niveau ?",
    a: "Nous effectuons une évaluation rapide lors de votre première séance pour vous placer dans le niveau approprié. Aucun test séparé n'est requis.",
  },
];

export default function FAQSection() {
  const [openIdx, setOpenIdx] = useState<number | null>(0);

  return (
    <section className="py-20 lg:py-28">
      <div className="container mx-auto px-4 lg:px-8">
        <motion.div
          initial={{ opacity: 0, y: 30 }}
          whileInView={{ opacity: 1, y: 0 }}
          viewport={{ once: true }}
          transition={{ duration: 0.6 }}
          className="text-center mb-14"
        >
          <span className="text-xs font-semibold uppercase tracking-widest text-primary mb-3 block">FAQ</span>
          <h2 className="text-3xl md:text-4xl font-heading font-bold text-foreground">
            Découvrez les réponses aux{" "}
            <span className="text-primary">questions fréquentes</span>
          </h2>
        </motion.div>

        <div className="max-w-2xl mx-auto space-y-3">
          {faqs.map((faq, i) => (
            <motion.div
              key={i}
              initial={{ opacity: 0, y: 20 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.4, delay: i * 0.08 }}
            >
              <button
                onClick={() => setOpenIdx(openIdx === i ? null : i)}
                className="w-full flex items-center justify-between p-5 bg-card rounded-2xl shadow-card text-left hover:shadow-card-hover transition-shadow"
              >
                <span className="font-heading font-semibold text-foreground pr-4">{faq.q}</span>
                <ChevronDown
                  className={`w-5 h-5 text-muted-foreground flex-shrink-0 transition-transform ${
                    openIdx === i ? "rotate-180" : ""
                  }`}
                />
              </button>
              {openIdx === i && (
                <div className="px-5 pb-5 pt-2 text-sm text-muted-foreground leading-relaxed bg-card rounded-b-2xl -mt-2">
                  {faq.a}
                </div>
              )}
            </motion.div>
          ))}
        </div>
      </div>
    </section>
  );
}
