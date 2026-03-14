import { motion } from "framer-motion";
import { Phone, Mail, MapPin, Send, Clock } from "lucide-react";
import PublicLayout from "@/components/PublicLayout";
import { useState } from "react";
import { useMutation } from "@tanstack/react-query";
import { apiPost, ApiError } from "@/lib/api";

export default function Contact() {
  const [submitted, setSubmitted] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [form, setForm] = useState({
    name: "",
    email: "",
    message: "",
  });

  const mutation = useMutation({
    mutationFn: () => apiPost("/contact", form),
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
            <span className="text-xs font-semibold uppercase tracking-widest text-primary mb-3 block">Contact</span>
            <h1 className="text-4xl md:text-5xl font-heading font-bold text-foreground mb-4">
              Contactez-<span className="text-primary">nous</span>
            </h1>
            <p className="text-muted-foreground max-w-xl mx-auto">
              Nous sommes à votre disposition pour répondre à toutes vos questions.
            </p>
          </motion.div>
        </div>
      </section>

      <section className="py-20">
        <div className="container mx-auto px-4 lg:px-8">
          <div className="grid lg:grid-cols-5 gap-12">
            {/* Info */}
            <motion.div
              initial={{ opacity: 0, x: -30 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6 }}
              className="lg:col-span-2 space-y-6"
            >
              <h2 className="text-2xl font-heading font-bold text-foreground mb-6">Informations</h2>
              {[
                { icon: Phone, label: "Téléphone", value: "06 64 42 66 02" },
                { icon: Mail, label: "Email", value: "academyjefalcentre@gmail.com" },
                { icon: MapPin, label: "Adresse", value: "Rue Mohammed V, Immeuble Al Qods, App N6, Settat" },
                { icon: Clock, label: "Horaires", value: "Lun-Sam : 9h - 19h" },
              ].map((item) => (
                <div key={item.label} className="flex items-start gap-4">
                  <div className="w-12 h-12 rounded-2xl bg-primary/10 flex items-center justify-center flex-shrink-0">
                    <item.icon className="w-5 h-5 text-primary" />
                  </div>
                  <div>
                    <div className="text-sm font-semibold text-foreground">{item.label}</div>
                    <div className="text-sm text-muted-foreground">{item.value}</div>
                  </div>
                </div>
              ))}

              <div className="rounded-2xl overflow-hidden border border-border h-52 bg-muted flex items-center justify-center">
                <span className="text-sm text-muted-foreground">📍 Google Maps — Settat, Maroc</span>
              </div>
            </motion.div>

            {/* Form */}
            <motion.div
              initial={{ opacity: 0, x: 30 }}
              whileInView={{ opacity: 1, x: 0 }}
              viewport={{ once: true }}
              transition={{ duration: 0.6, delay: 0.2 }}
              className="lg:col-span-3"
            >
              <div className="bg-card rounded-2xl p-8 shadow-card">
                <h2 className="text-2xl font-heading font-bold text-foreground mb-6">Envoyez-nous un message</h2>
                {submitted ? (
                  <div className="text-center py-12">
                    <div className="text-5xl mb-4">✅</div>
                    <h3 className="font-heading font-semibold text-xl mb-2 text-foreground">Message envoyé !</h3>
                    <p className="text-muted-foreground">Nous vous répondrons dans les plus brefs délais.</p>
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
                    <div className="grid sm:grid-cols-2 gap-5">
                      <div>
                        <label className="block text-sm font-medium mb-1.5 text-foreground">Nom complet</label>
                        <input
                          required
                          value={form.name}
                          onChange={(e) => setForm((prev) => ({ ...prev, name: e.target.value }))}
                          className="w-full px-4 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                          placeholder="Votre nom"
                        />
                      </div>
                      <div>
                        <label className="block text-sm font-medium mb-1.5 text-foreground">Email</label>
                        <input
                          required
                          type="email"
                          value={form.email}
                          onChange={(e) => setForm((prev) => ({ ...prev, email: e.target.value }))}
                          className="w-full px-4 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                          placeholder="votre@email.com"
                        />
                      </div>
                    </div>
                    <div>
                      <label className="block text-sm font-medium mb-1.5 text-foreground">Message</label>
                      <textarea
                        required
                        rows={5}
                        value={form.message}
                        onChange={(e) => setForm((prev) => ({ ...prev, message: e.target.value }))}
                        className="w-full px-4 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring resize-none"
                        placeholder="Votre message..."
                      />
                    </div>
                    <button
                      type="submit"
                      disabled={mutation.isPending}
                      className="inline-flex items-center gap-2 px-7 py-3 rounded-full bg-primary text-primary-foreground font-semibold text-sm hover:opacity-90 transition-opacity disabled:opacity-60"
                    >
                      <Send className="w-4 h-4" /> Envoyer
                    </button>
                  </form>
                )}
              </div>
            </motion.div>
          </div>
        </div>
      </section>
    </PublicLayout>
  );
}
