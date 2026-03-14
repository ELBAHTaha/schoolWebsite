import { motion } from "framer-motion";
import { Users, BookOpen, CreditCard, GraduationCap, ArrowUpRight, ArrowDownRight } from "lucide-react";
import { Link } from "react-router-dom";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { useQuery } from "@tanstack/react-query";
import { apiGet } from "@/lib/api";

const baseStatsCards = [
  { key: "students", label: "Total étudiants", value: "1,247", change: "+12%", up: true, icon: Users, color: "bg-primary/10 text-primary" },
  { key: "classes", label: "Classes actives", value: "34", change: "+3", up: true, icon: BookOpen, color: "bg-success/10 text-success" },
  { key: "monthly_revenue", label: "Revenus du mois", value: "145,000 DH", change: "+8%", up: true, icon: CreditCard, color: "bg-gold/10 text-gold" },
  { key: "success_rate", label: "Taux de réussite", value: "95%", change: "-1%", up: false, icon: GraduationCap, color: "bg-accent/10 text-accent" },
];

const fallbackRecentStudents = [
  { name: "Fatima Zahra", course: "DELF B2", date: "2026-03-03", status: "Payé" },
  { name: "Youssef Alami", course: "TOEFL", date: "2026-03-02", status: "En attente" },
  { name: "Sara Benali", course: "IELTS", date: "2026-03-01", status: "Payé" },
  { name: "Ahmed Mansouri", course: "TEF", date: "2026-02-28", status: "Impayé" },
  { name: "Khadija El Idrissi", course: "Italien", date: "2026-02-27", status: "Payé" },
];

const statusStyles: Record<string, string> = {
  "Payé": "bg-success/10 text-success",
  "En attente": "bg-warning/10 text-warning",
  "Impayé": "bg-accent/10 text-accent",
  "En retard": "bg-accent/10 text-accent",
};

const paymentStatusLabels: Record<string, string> = {
  paid: "Payé",
  pending: "En attente",
  late: "En retard",
  unpaid: "Impayé",
};

interface AdminDashboardResponse {
  stats: {
    students: number;
    classes: number;
    monthly_revenue: number;
    success_rate: number | null;
  };
  recent_students: Array<{
    id: number;
    name: string;
    course: string | null;
    date: string | null;
    payment_status: string;
  }>;
}

export default function AdminDashboard() {
  const { data } = useQuery({
    queryKey: ["admin-dashboard"],
    queryFn: () => apiGet<AdminDashboardResponse>("/dashboard/admin"),
  });

  const formatNumber = (value: number) => new Intl.NumberFormat("fr-FR").format(value);
  const formatCurrency = (value: number) => `${formatNumber(value)} DH`;

  const statsCards = baseStatsCards.map((card) => {
    if (!data?.stats) return card;

    switch (card.key) {
      case "students":
        return { ...card, value: formatNumber(data.stats.students) };
      case "classes":
        return { ...card, value: formatNumber(data.stats.classes) };
      case "monthly_revenue":
        return { ...card, value: formatCurrency(data.stats.monthly_revenue) };
      case "success_rate":
        return data.stats.success_rate === null
          ? card
          : { ...card, value: `${data.stats.success_rate}%` };
      default:
        return card;
    }
  });

  const recentStudents = data
    ? data.recent_students.map((student) => ({
        name: student.name,
        course: student.course ?? "—",
        date: student.date ?? "",
        status: paymentStatusLabels[student.payment_status] || "En attente",
      }))
    : fallbackRecentStudents;

  return (
    <DashboardLayout role="admin">
      <div className="space-y-6">
        <DashboardHeader
          title="Tableau de bord"
          subtitle="Vue d'ensemble de l'académie"
        />

        {/* Stats */}
        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
          {statsCards.map((s, i) => (
            <motion.div
              key={s.label}
              initial={{ opacity: 0, y: 20 }}
              animate={{ opacity: 1, y: 0 }}
              transition={{ duration: 0.4, delay: i * 0.1 }}
              className="bg-card rounded-2xl p-5 shadow-card"
            >
              <div className="flex items-center justify-between mb-3">
                <div className={`w-10 h-10 rounded-lg flex items-center justify-center ${s.color}`}>
                  <s.icon className="w-5 h-5" />
                </div>
                <span className={`flex items-center gap-1 text-xs font-medium ${s.up ? "text-success" : "text-accent"}`}>
                  {s.up ? <ArrowUpRight className="w-3 h-3" /> : <ArrowDownRight className="w-3 h-3" />}
                  {s.change}
                </span>
              </div>
              <div className="text-2xl font-bold text-foreground">{s.value}</div>
              <div className="text-xs text-muted-foreground mt-1">{s.label}</div>
            </motion.div>
          ))}
        </div>

        {/* Recent students table */}
        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <div className="px-6 py-4 border-b border-border flex items-center justify-between">
            <h3 className="font-heading font-semibold">Inscriptions récentes</h3>
            <button className="text-xs text-primary font-medium hover:underline">Voir tout →</button>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border bg-secondary/50">
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Étudiant</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Cours</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Date</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Statut</th>
                </tr>
              </thead>
              <tbody>
                {recentStudents.map((s) => (
                  <tr key={s.name} className="border-b border-border last:border-0 hover:bg-secondary/30 transition-colors">
                    <td className="px-6 py-3 font-medium text-foreground">{s.name}</td>
                    <td className="px-6 py-3 text-muted-foreground">{s.course}</td>
                    <td className="px-6 py-3 text-muted-foreground">{s.date}</td>
                    <td className="px-6 py-3">
                      <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${statusStyles[s.status]}`}>
                        {s.status}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>

        {/* Quick actions */}
        <div className="grid sm:grid-cols-3 gap-4">
          {[
            { label: "Gestion utilisateurs", desc: "Ajouter, modifier ou supprimer", href: "/dashboard/admin/users" },
            { label: "Gestion classes", desc: "Créer et organiser les classes", href: "/dashboard/admin/classes" },
            { label: "Gestion paiements", desc: "Suivre les paiements", href: "/dashboard/admin/payments" },
          ].map((a) => (
            <Link
              key={a.label}
              to={a.href}
              className="bg-card rounded-2xl p-5 shadow-card hover:shadow-card-hover transition-shadow group"
            >
              <h4 className="font-semibold text-foreground group-hover:text-primary transition-colors">{a.label}</h4>
              <p className="text-xs text-muted-foreground mt-1">{a.desc}</p>
            </Link>
          ))}
        </div>
      </div>
    </DashboardLayout>
  );
}
