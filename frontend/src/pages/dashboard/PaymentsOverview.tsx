import { useState } from "react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { Search } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import { apiGet } from "@/lib/api";

const fallbackPayments = [
  { id: 1, student: "Fatima Zahra", course: "DELF B2", amount: 2000, date: "2026-03-01", status: "Payé" },
  { id: 2, student: "Youssef Alami", course: "TOEFL", amount: 2500, date: "2026-02-28", status: "En attente" },
  { id: 3, student: "Sara Benali", course: "IELTS", amount: 2500, date: "2026-02-25", status: "Payé" },
  { id: 4, student: "Ahmed Mansouri", course: "TEF", amount: 1800, date: "2026-02-20", status: "Impayé" },
  { id: 5, student: "Khadija El Idrissi", course: "Italien", amount: 1200, date: "2026-02-18", status: "En retard" },
  { id: 6, student: "Omar Tahiri", course: "Allemand", amount: 1200, date: "2026-02-15", status: "Payé" },
];

const statusStyles: Record<string, string> = {
  "Payé": "bg-success/10 text-success",
  "En attente": "bg-warning/10 text-warning",
  "Impayé": "bg-accent/10 text-accent",
  "En retard": "bg-accent/10 text-accent",
};

const statusLabels: Record<string, string> = {
  paid: "Payé",
  unpaid: "Impayé",
  late: "En retard",
};

interface PaymentsResponse {
  summary: {
    total_collected: number;
    pending: number;
    late: number;
    pending_count: number;
    late_count: number;
  };
  data: Array<{
    id: number;
    student: string | null;
    course: string | null;
    amount: number;
    date: string | null;
    status: string;
  }>;
}

export default function PaymentsOverview() {
  const [search, setSearch] = useState("");
  const { data } = useQuery({
    queryKey: ["admin-payments", search],
    queryFn: () => apiGet<PaymentsResponse>(`/admin/payments?search=${encodeURIComponent(search)}`),
  });

  const formatNumber = (value: number) => new Intl.NumberFormat("fr-FR").format(value);
  const formatCurrency = (value: number) => `${formatNumber(value)} DH`;

  const summary = data?.summary
    ? [
        { label: "Total collecté", value: formatCurrency(data.summary.total_collected), sub: "Ce mois" },
        { label: "En attente", value: formatCurrency(data.summary.pending), sub: `${data.summary.pending_count} paiements` },
        { label: "Impayés", value: formatCurrency(data.summary.late), sub: `${data.summary.late_count} étudiants` },
      ]
    : [
        { label: "Total collecté", value: "145,000 DH", sub: "Ce mois" },
        { label: "En attente", value: "12,500 DH", sub: "5 paiements" },
        { label: "Impayés", value: "8,200 DH", sub: "3 étudiants" },
      ];

  const payments = data
    ? data.data.map((payment) => ({
        id: payment.id,
        student: payment.student ?? "—",
        course: payment.course ?? "—",
        amount: formatCurrency(payment.amount),
        date: payment.date ?? "",
        status: statusLabels[payment.status] ?? "En attente",
      }))
    : fallbackPayments.map((payment) => ({
        ...payment,
        amount: formatCurrency(payment.amount),
      }));

  return (
    <DashboardLayout role="admin">
      <div className="space-y-6">
        <DashboardHeader
          title="Gestion des paiements"
          subtitle="Suivez les paiements, retards et revenus"
        />

        {/* Summary cards */}
        <div className="grid sm:grid-cols-3 gap-4">
          {summary.map((c) => (
            <div key={c.label} className="bg-card rounded-2xl p-5 shadow-card">
              <div className="text-xs text-muted-foreground mb-1">{c.label}</div>
              <div className="text-xl font-bold text-foreground">{c.value}</div>
              <div className="text-xs text-muted-foreground mt-1">{c.sub}</div>
            </div>
          ))}
        </div>

        {/* Table */}
        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <div className="px-6 py-4 border-b border-border flex gap-3">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input
                value={search}
                onChange={(event) => setSearch(event.target.value)}
                className="w-full pl-10 pr-4 py-2 rounded-lg border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                placeholder="Rechercher..."
              />
            </div>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border bg-secondary/50">
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Étudiant</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Cours</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Montant</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Date</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Statut</th>
                </tr>
              </thead>
              <tbody>
                {payments.map((p) => (
                  <tr key={p.id} className="border-b border-border last:border-0 hover:bg-secondary/30 transition-colors">
                    <td className="px-6 py-3 font-medium text-foreground">{p.student}</td>
                    <td className="px-6 py-3 text-muted-foreground">{p.course}</td>
                    <td className="px-6 py-3 font-semibold text-foreground">{p.amount}</td>
                    <td className="px-6 py-3 text-muted-foreground">{p.date}</td>
                    <td className="px-6 py-3">
                      <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${statusStyles[p.status]}`}>
                        {p.status}
                      </span>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
