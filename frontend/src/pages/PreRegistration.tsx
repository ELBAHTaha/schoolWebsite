import { motion } from "framer-motion";
import { Send, CheckCircle2 } from "lucide-react";
import PublicLayout from "@/components/PublicLayout";
import { useState } from "react";
import { useMutation, useQuery } from "@tanstack/react-query";
import { apiGet, apiPost, ApiError } from "@/lib/api";

const fallbackCourses = [
  "Français — DELF / DALF",
  "Anglais — TOEFL",
  "Anglais — IELTS",
  "Français — TEF / TCF",
  "Italien",
  "Allemand",
  "Espagnol",
  "Préparation immigration",
];

interface ClassResponse {
  data: Array<{ id: number; name: string }>;
}

export default function PreRegistration() {
  const [submitted, setSubmitted] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [form, setForm] = useState({
    name: "",
    email: "",
    phone: "",
    desired_program: "",
    message: "",
  });

  const { data } = useQuery({
    queryKey: ["classes"],
    queryFn: () => apiGet<ClassResponse>("/classes"),
  });

  const courses = data?.data?.length ? data.data.map((c) => c.name) : fallbackCourses;

  const mutation = useMutation({
    mutationFn: () => apiPost("/pre-registration", form),
    onSuccess: () => {
      setSubmitted(true);
      setError(null);
    },
    onError: (err) => {
      const apiError = err as ApiError;
      setError(apiError?.message || "Une erreur est survenue.");
    },
  });

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
            {submitted ? (
              <div className="text-center py-12">
                <div className="w-16 h-16 rounded-full bg-success/10 flex items-center justify-center mx-auto mb-4">
                  <CheckCircle2 className="w-8 h-8 text-success" />
                </div>
                <h3 className="font-heading font-semibold text-2xl mb-2 text-foreground">Inscription reçue !</h3>
                <p className="text-muted-foreground">Nous vous contacterons sous 24h pour confirmer votre inscription.</p>
              </div>
            ) : (
              <form
                onSubmit={(e) => {
                  e.preventDefault();
                  mutation.mutate();
                }}
                className="space-y-5"
              >
                {error && (
                  <div className="rounded-xl border border-destructive/40 bg-destructive/10 px-4 py-3 text-sm text-destructive">
                    {error}
                  </div>
                )}
                <div>
                  <label className="block text-sm font-medium mb-1.5 text-foreground">Nom complet *</label>
                  <input
                    required
                    value={form.name}
                    onChange={(e) => setForm((prev) => ({ ...prev, name: e.target.value }))}
                    className="w-full px-4 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                    placeholder="Prénom et nom"
                  />
                </div>
                <div className="grid sm:grid-cols-2 gap-5">
                  <div>
                    <label className="block text-sm font-medium mb-1.5 text-foreground">Email *</label>
                    <input
                      required
                      type="email"
                      value={form.email}
                      onChange={(e) => setForm((prev) => ({ ...prev, email: e.target.value }))}
                      className="w-full px-4 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                      placeholder="votre@email.com"
                    />
                  </div>
                  <div>
                    <label className="block text-sm font-medium mb-1.5 text-foreground">Téléphone</label>
                    <input
                      type="tel"
                      value={form.phone}
                      onChange={(e) => setForm((prev) => ({ ...prev, phone: e.target.value }))}
                      className="w-full px-4 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                      placeholder="06 XX XX XX XX"
                    />
                  </div>
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1.5 text-foreground">Programme souhaité *</label>
                  <select
                    required
                    value={form.desired_program}
                    onChange={(e) => setForm((prev) => ({ ...prev, desired_program: e.target.value }))}
                    className="w-full px-4 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                  >
                    <option value="">Choisir un programme...</option>
                    {courses.map((c) => (
                      <option key={c} value={c}>{c}</option>
                    ))}
                  </select>
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1.5 text-foreground">Message (optionnel)</label>
                  <textarea
                    rows={4}
                    value={form.message}
                    onChange={(e) => setForm((prev) => ({ ...prev, message: e.target.value }))}
                    className="w-full px-4 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring resize-none"
                    placeholder="Informations supplémentaires..."
                  />
                </div>
                <button
                  type="submit"
                  disabled={mutation.isPending}
                  className="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 rounded-full bg-accent text-accent-foreground font-semibold text-sm hover:opacity-90 transition-opacity shadow-md disabled:opacity-60"
                >
                  <Send className="w-4 h-4" /> Soumettre la pré-inscription
                </button>
              </form>
            )}
          </motion.div>
        </div>
      </section>
    </PublicLayout>
  );
}
