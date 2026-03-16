import { motion } from "framer-motion";
import { Lock, Mail, Eye, EyeOff, Phone, User } from "lucide-react";
import { useState } from "react";
import { Link, useNavigate } from "react-router-dom";
import { useMutation } from "@tanstack/react-query";
import { apiPost, ApiError } from "@/lib/api";
import { useAuth } from "@/contexts/AuthContext";

interface LoginResponse {
  user: {
    id: number;
    name: string;
    email: string;
    role: string;
  };
}

const roleDashboardPath = (role: string) => {
  switch (role) {
    case "admin":
      return "/dashboard/admin";
    case "directeur":
      return "/dashboard/admin";
    case "student":
    case "professor":
    case "secretary":
      return `/dashboard/${role}`;
    case "commercial":
      return "/dashboard/commercial";
    default:
      return "/";
  }
};

export default function Login() {
  const [isLogin, setIsLogin] = useState(true);
  const [showPassword, setShowPassword] = useState(false);
  const [error, setError] = useState<string | null>(null);
  const [success, setSuccess] = useState<string | null>(null);
  const [form, setForm] = useState({
    name: "",
    email: "",
    password: "",
    phone: "",
    remember: false,
  });
  const navigate = useNavigate();
  const { login } = useAuth();

  const loginMutation = useMutation({
    mutationFn: (payload: { email: string; password: string; remember: boolean }) =>
      apiPost<LoginResponse>("/login", payload),
  });

  const registerMutation = useMutation({
    mutationFn: (payload: { name: string; email: string; password: string; phone?: string }) =>
      apiPost("/register", payload),
  });

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setError(null);
    setSuccess(null);

    try {
      if (isLogin) {
        const response = await loginMutation.mutateAsync({
          email: form.email,
          password: form.password,
          remember: form.remember,
        });
        login(response.user);
        navigate(roleDashboardPath(response.user.role));
      } else {
        await registerMutation.mutateAsync({
          name: form.name,
          email: form.email,
          password: form.password,
          phone: form.phone || undefined,
        });
        setSuccess("Compte créé. Vous pouvez vous connecter.");
        setIsLogin(true);
      }
    } catch (err) {
      const apiError = err as ApiError;
      setError(apiError?.message || "Une erreur est survenue.");
    }
  };

  return (
    <div className="min-h-screen bg-background flex items-center justify-center p-4">
      <motion.div
        initial={{ opacity: 0, y: 30 }}
        animate={{ opacity: 1, y: 0 }}
        transition={{ duration: 0.5 }}
        className="w-full max-w-md"
      >
        <div className="bg-card rounded-3xl shadow-float p-8">
          <div className="text-center mb-8">
            <Link to="/">
              <img src="/logo.png" alt="JEFAL Privé" className="h-16 w-auto mx-auto mb-4" />
            </Link>
            <h1 className="text-2xl font-heading font-bold text-foreground">
              {isLogin ? "Connexion" : "Créer un compte"}
            </h1>
            <p className="text-sm text-muted-foreground mt-1">
              {isLogin ? "Accédez à votre espace personnel" : "Rejoignez JEFAL Privé"}
            </p>
          </div>

          {error && (
            <div className="mb-4 rounded-xl border border-destructive/40 bg-destructive/10 px-4 py-3 text-sm text-destructive">
              {error}
            </div>
          )}

          {success && (
            <div className="mb-4 rounded-xl border border-success/30 bg-success/10 px-4 py-3 text-sm text-success">
              {success}
            </div>
          )}

          <form onSubmit={handleSubmit} className="space-y-4">
            {!isLogin && (
              <div>
                <label className="block text-sm font-medium mb-1.5 text-foreground">Nom complet</label>
                <div className="relative">
                  <User className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                  <input
                    required
                    value={form.name}
                    onChange={(e) => setForm((prev) => ({ ...prev, name: e.target.value }))}
                    className="w-full pl-10 pr-4 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                    placeholder="Prénom et nom"
                  />
                </div>
              </div>
            )}
            <div>
              <label className="block text-sm font-medium mb-1.5 text-foreground">Email</label>
              <div className="relative">
                <Mail className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <input
                  required
                  type="email"
                  value={form.email}
                  onChange={(e) => setForm((prev) => ({ ...prev, email: e.target.value }))}
                  className="w-full pl-10 pr-4 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                  placeholder="votre@email.com"
                />
              </div>
            </div>
            {!isLogin && (
              <div>
                <label className="block text-sm font-medium mb-1.5 text-foreground">Téléphone (optionnel)</label>
                <div className="relative">
                  <Phone className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                  <input
                    value={form.phone}
                    onChange={(e) => setForm((prev) => ({ ...prev, phone: e.target.value }))}
                    className="w-full pl-10 pr-4 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                    placeholder="06 XX XX XX XX"
                  />
                </div>
              </div>
            )}
            <div>
              <label className="block text-sm font-medium mb-1.5 text-foreground">Mot de passe</label>
              <div className="relative">
                <Lock className="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
                <input
                  required
                  type={showPassword ? "text" : "password"}
                  value={form.password}
                  onChange={(e) => setForm((prev) => ({ ...prev, password: e.target.value }))}
                  className="w-full pl-10 pr-10 py-3 rounded-xl border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                  placeholder="••••••••"
                />
                <button
                  type="button"
                  onClick={() => setShowPassword(!showPassword)}
                  className="absolute right-3.5 top-1/2 -translate-y-1/2 text-muted-foreground hover:text-foreground"
                >
                  {showPassword ? <EyeOff className="w-4 h-4" /> : <Eye className="w-4 h-4" />}
                </button>
              </div>
            </div>

            {isLogin && (
              <div className="flex items-center justify-between text-sm">
                <label className="flex items-center gap-2 text-muted-foreground">
                  <input
                    type="checkbox"
                    checked={form.remember}
                    onChange={(e) => setForm((prev) => ({ ...prev, remember: e.target.checked }))}
                    className="rounded border-input"
                  />
                  <span>Se souvenir</span>
                </label>
                <a href="#" className="text-primary hover:underline font-medium">Mot de passe oublié ?</a>
              </div>
            )}

            <button
              type="submit"
              disabled={loginMutation.isPending || registerMutation.isPending}
              className="w-full py-3.5 rounded-full bg-primary text-primary-foreground font-semibold text-sm hover:opacity-90 transition-opacity disabled:opacity-60"
            >
              {isLogin ? "Se connecter" : "Créer le compte"}
            </button>
          </form>

          <div className="mt-6 text-center text-sm text-muted-foreground">
            {isLogin ? "Pas encore de compte ?" : "Déjà inscrit ?"}{" "}
            <button onClick={() => setIsLogin(!isLogin)} className="text-primary font-semibold hover:underline">
              {isLogin ? "S'inscrire" : "Se connecter"}
            </button>
          </div>
        </div>

        <div className="text-center mt-6">
          <Link to="/" className="text-sm text-muted-foreground hover:text-foreground transition-colors">
            ← Retour à l'accueil
          </Link>
        </div>
      </motion.div>
    </div>
  );
}
