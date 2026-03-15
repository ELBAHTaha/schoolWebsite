import { useState } from "react";
import { motion } from "framer-motion";
import { Send, CheckCircle2 } from "lucide-react";
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

interface PreRegistrationFormProps {
  onBack?: () => void;
}

export default function PreRegistrationForm({ onBack }: PreRegistrationFormProps) {
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
    queryFn: () => apiGet<{ data: Array<{ id: number; name: string }> }>("/classes"),
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
    <div>
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
                <option key={c} value={c}>
                  {c}
                </option>
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

          <div className="flex items-center justify-between">
            {onBack ? (
              <button type="button" onClick={onBack} className="text-sm font-semibold text-primary hover:underline">
                Précédent
              </button>
            ) : (
              <span />
            )}
            <button
              type="submit"
              disabled={mutation.isPending}
              className="inline-flex items-center justify-center gap-2 rounded-full bg-accent px-6 py-3 text-sm font-semibold text-accent-foreground hover:opacity-90 transition-opacity shadow-md disabled:opacity-60"
            >
              <Send className="w-4 h-4" /> Soumettre la pré-inscription
            </button>
          </div>
        </form>
      )}
    </div>
  );
}
