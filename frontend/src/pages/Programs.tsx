import { motion } from "framer-motion";
import { Clock, DollarSign, ArrowRight } from "lucide-react";
import { Link, createSearchParams } from "react-router-dom";
import { useQuery } from "@tanstack/react-query";
import PublicLayout from "@/components/PublicLayout";
import { apiGet } from "@/lib/api";

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
  const { data, isLoading, isError } = useQuery({
    queryKey: ["classes"],
    queryFn: () => apiGet<ClassResponse>("/classes"),
  });

  const programs = data?.data ?? [];
  const programCards = programs.map((item) => ({
    title: item.name,
    desc: item.description || "Programme linguistique complet, adapté à votre niveau.",
    duration: "Flexible",
    price: priceLabel(item),
    flag: "🎓",
  }));

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
          {isLoading ? (
            <div className="text-center text-muted-foreground">Chargement des programmes...</div>
          ) : programCards.length ? (
            <div className="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
              {programCards.map((p, i) => (
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
                      to={{
                        pathname: "/pre-registration",
                        search: createSearchParams({ program: p.title }).toString(),
                      }}
                      className="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
                    >
                      S'inscrire <ArrowRight className="w-4 h-4" />
                    </Link>
                  </div>
                </motion.div>
              ))}
            </div>
          ) : (
            <div className="text-center bg-card rounded-2xl border border-border p-10 max-w-2xl mx-auto">
              <h3 className="font-heading font-semibold text-2xl text-foreground mb-2">Aucun programme disponible</h3>
              <p className="text-muted-foreground mb-6">
                Nos nouvelles sessions arrivent bientôt. Laissez-nous vos coordonnées et nous vous préviendrons.
              </p>
              <Link
                to="/pre-registration"
                className="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
              >
                Pré-inscription <ArrowRight className="w-4 h-4" />
              </Link>
              {isError && (
                <p className="text-xs text-muted-foreground mt-4">
                  Impossible de charger les programmes pour le moment.
                </p>
              )}
            </div>
          )}
        </div>
      </section>
    </PublicLayout>
  );
}
