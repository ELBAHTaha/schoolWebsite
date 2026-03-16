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
  const [openIdx, setOpenIdx] = useState(null);

  return (
    <section
      className="pt-0 pb-20 lg:pt-0 lg:pb-28"
      style={{
        backgroundImage: `url(/src/assets/faq.jpg)`,
        backgroundSize: "cover",
        backgroundPosition: "center top",
        backgroundRepeat: "no-repeat",
      }}
    >
      <div className="container mx-auto px-4 lg:px-8">
        <div className="grid lg:grid-cols-2 gap-12 items-center">

          {/* LEFT SIDE */}
          <div>
            <motion.div
              initial={{ opacity: 0, y: 30 }}
              whileInView={{ opacity: 1, y: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6 }}
              className="mt-16 mb-14"
            >
              <span className="text-xs font-semibold uppercase tracking-widest text-white mb-3 block">
                FAQ
              </span>

              <h2 className="text-3xl md:text-4xl font-heading font-bold text-white">
                Découvrez les réponses aux <span className="text-primary">questions fréquentes</span>
              </h2>
            </motion.div>

            <div className="space-y-3">
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
                    className="w-full flex items-center justify-between p-5 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl shadow-card text-left hover:shadow-card-hover transition-shadow"
                  >
                    <span className="font-heading font-semibold text-white pr-4">
                      {faq.q}
                    </span>

                    <ChevronDown
                      className={`w-5 h-5 text-white flex-shrink-0 transition-transform ${
                        openIdx === i ? "rotate-180" : ""
                      }`}
                    />
                  </button>

                  {openIdx === i && (
                    <div className="px-5 pb-5 pt-2 text-sm text-white leading-relaxed bg-transparent rounded-b-2xl">
                      {faq.a}
                    </div>
                  )}
                </motion.div>
              ))}
            </div>
          </div>

        </div>
      </div>
    </section>
  );
}