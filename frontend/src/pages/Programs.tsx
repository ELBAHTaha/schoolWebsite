import { motion } from "framer-motion";
import { Clock, DollarSign, ArrowRight } from "lucide-react";
import { Link } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import PublicLayout from "@/components/PublicLayout";
import { apiGet } from "@/lib/api";

const fallbackPrograms = [
  { title: "Français — DELF / DALF", desc: "Préparation aux diplômes officiels de langue française. Niveaux A1 à C2.", duration: "3 — 6 mois", price: "À partir de 1500 DH", flag: "🇫🇷" },
  { title: "Anglais — TOEFL", desc: "Préparation intensive au test TOEFL pour études et immigration.", duration: "3 mois", price: "À partir de 2000 DH", flag: "🇺🇸" },
  { title: "Anglais — IELTS", desc: "Formation complète pour obtenir le score IELTS requis.", duration: "3 mois", price: "À partir de 2000 DH", flag: "🇬🇧" },
  { title: "Français — TEF / TCF", desc: "Préparation aux tests de français pour l'immigration au Canada.", duration: "2 — 4 mois", price: "À partir de 1800 DH", flag: "🇫🇷" },
  { title: "Italien", desc: "Cours de langue italienne pour tous les niveaux, du débutant à l'avancé.", duration: "4 mois", price: "À partir de 1200 DH", flag: "🇮🇹" },
  { title: "Allemand", desc: "Apprentissage de l'allemand avec focus sur la communication et la grammaire.", duration: "4 mois", price: "À partir de 1200 DH", flag: "🇩🇪" },
  { title: "Espagnol", desc: "Cours d'espagnol couvrant conversation, grammaire et culture.", duration: "4 mois", price: "À partir de 1200 DH", flag: "🇪🇸" },
  { title: "Préparation immigration", desc: "Préparation spéciale pour les examens requis pour l'immigration et les études à l'étranger.", duration: "Variable", price: "Sur devis", flag: "🌍" },
];

interface ClassResponse {
  data: Array<{
    id: number;
    name: string;
    description: string | null;
    price_1_month?: number | null;
    price_3_month?: number | null;
    price_6_month?: number | null;
  }>;
}

const priceLabel = (item: ClassResponse["data"][number]) => {
  const price = item.price_1_month || item.price_3_month || item.price_6_month;
  return price ? `À partir de ${price} DH` : "Sur devis";
};

export default function Programs() {
  const { data } = useQuery({
    queryKey: ["classes"],
    queryFn: () => apiGet<ClassResponse>("/classes"),
  });

  const programs = data?.data?.length
    ? data.data.map((item) => ({
        title: item.name,
        desc: item.description || "Programme linguistique complet, adapté à votre niveau.",
        duration: "Flexible",
        price: priceLabel(item),
        flag: "🎓",
      }))
    : fallbackPrograms;

  return (
    <PublicLayout>
      <section className="bg-background py-24">
        <div className="container mx-auto px-4 lg:px-8 text-center">
          <motion.div
            initial={{ opacity: 0, y: 30 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
          >
            <span className="text-xs font-semibold uppercase tracking-widest text-primary mb-3 block">Nos programmes</span>
            <h1 className="text-4xl md:text-5xl font-heading font-bold text-foreground mb-4">
              Des formations adaptées à <span className="text-primary">vos objectifs</span>
            </h1>
            <p className="text-muted-foreground max-w-xl mx-auto">
              Des formations linguistiques adaptées à vos objectifs académiques et professionnels.
            </p>
          </motion.div>
        </div>
      </section>

      <section className="py-20">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {programs.map((p, i) => (
              <motion.div
                key={`${p.title}-${i}`}
                initial={{ opacity: 0, y: 30 }}
                whileInView={{ opacity: 1, y: 0 }}
                viewport={{ once: true }}
                transition={{ duration: 0.4, delay: i * 0.06 }}
                className="bg-card rounded-2xl shadow-card hover:shadow-card-hover transition-all group flex flex-col"
              >
                <div className="p-6 flex-1">
                  <span className="text-3xl mb-3 block">{p.flag}</span>
                  <h3 className="font-heading font-semibold text-lg text-foreground mb-2">{p.title}</h3>
                  <p className="text-sm text-muted-foreground mb-4 leading-relaxed">{p.desc}</p>
                  <div className="space-y-2 text-sm">
                    <div className="flex items-center gap-2 text-muted-foreground">
                      <Clock className="w-4 h-4" /> {p.duration}
                    </div>
                    <div className="flex items-center gap-2 text-foreground font-semibold">
                      <DollarSign className="w-4 h-4 text-accent" /> {p.price}
                    </div>
                  </div>
                </div>
                <div className="p-6 pt-0">
                  <Link
                    to="/pre-registration"
                    className="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
                  >
                    S'inscrire <ArrowRight className="w-4 h-4" />
                  </Link>
                </div>
              </motion.div>
            ))}
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
