import { useState } from "react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { Search } from "lucide-react";
import { useQuery, useQueryClient } from "@tanstack/react-query";
import { apiGet, apiPost, apiPut } from "@/lib/api";
import { Button } from "@/components/ui/button";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { Input } from "@/components/ui/input";

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
    student_id: number;
    student: string | null;
    email?: string | null;
    phone?: string | null;
    role?: string | null;
    class_ids?: number[];
    payment_status?: string | null;
    course: string | null;
    amount: number;
    date: string | null;
    status: string;
  }>;
}

export default function PaymentsOverview() {
  const [search, setSearch] = useState("");
  const [paymentModalOpen, setPaymentModalOpen] = useState(false);
  const [paymentTarget, setPaymentTarget] = useState<any>(null);
  const [paymentStatusTarget, setPaymentStatusTarget] = useState<"paid" | "pending">("paid");
  const [paymentForm, setPaymentForm] = useState({
    amount: "",
    date: new Date().toISOString().slice(0, 10),
    months: "1",
  });
  const queryClient = useQueryClient();
  const { data } = useQuery({
    queryKey: ["admin-payments", search],
    queryFn: () => apiGet<PaymentsResponse>(`/admin/payments?search=${encodeURIComponent(search)}`),
  });

  const formatNumber = (value: number) => new Intl.NumberFormat("fr-FR").format(value);
  const formatCurrency = (value: number) => `${formatNumber(value)} DH`;

  const summary = [
    { label: "Total collecté", value: formatCurrency(data?.summary.total_collected ?? 0), sub: "Ce mois" },
    { label: "En attente", value: formatCurrency(data?.summary.pending ?? 0), sub: `${data?.summary.pending_count ?? 0} paiements` },
    { label: "Impayés", value: formatCurrency(data?.summary.late ?? 0), sub: `${data?.summary.late_count ?? 0} étudiants` },
  ];

  const apiPayments = Array.isArray(data?.data) ? data.data : [];

  const payments = apiPayments.map((payment) => ({
    id: payment.id,
    studentId: payment.student_id,
    student: payment.student ?? "—",
    email: payment.email ?? "",
    phone: payment.phone ?? "",
    role: payment.role ?? "student",
    class_ids: payment.class_ids ?? [],
    payment_status: payment.payment_status ?? "pending",
    course: payment.course ?? "—",
    amount: formatCurrency(payment.amount),
    amountValue: payment.amount,
    date: payment.date ?? "",
    status: statusLabels[payment.status] ?? "En attente",
  }));

  const openPaymentModal = (payment: any) => {
    setPaymentTarget(payment);
    setPaymentStatusTarget(payment.status === "Payé" ? "paid" : "pending");
    setPaymentForm({
      amount: String(payment.amountValue ?? ""),
      date: new Date().toISOString().slice(0, 10),
      months: "1",
    });
    setPaymentModalOpen(true);
  };

  const handleConfirmPaymentStatus = async () => {
    if (!paymentTarget) {
      return;
    }

    if (paymentStatusTarget === "paid") {
      if (!paymentForm.amount || !paymentForm.date || !paymentForm.months) {
        return;
      }
      await apiPost("/admin/payments", {
        student_id: paymentTarget.studentId,
        amount: parseFloat(paymentForm.amount),
        status: "paid",
        payment_date: paymentForm.date,
        months: parseInt(paymentForm.months, 10),
      });
    } else {
      await apiPut(`/admin/users/${paymentTarget.studentId}`, {
        name: paymentTarget.student,
        email: paymentTarget.email,
        role: paymentTarget.role,
        phone: paymentTarget.phone || "",
        class_ids: paymentTarget.class_ids || [],
        payment_status: "pending",
      });
    }

    setPaymentModalOpen(false);
    setPaymentTarget(null);
    queryClient.invalidateQueries({ queryKey: ["admin-payments"] });
  };

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
                      <button
                        type="button"
                        onClick={() => openPaymentModal(p)}
                        className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${statusStyles[p.status]}`}
                      >
                        {p.status}
                      </button>
                    </td>
                  </tr>
                ))}
                {!payments.length && (
                  <tr>
                    <td colSpan={5} className="px-6 py-6 text-sm text-muted-foreground">
                      Aucun paiement trouvé.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <Dialog open={paymentModalOpen} onOpenChange={setPaymentModalOpen}>
        <DialogContent className="sm:max-w-[500px]">
          <DialogHeader>
            <DialogTitle>Mettre à jour le paiement</DialogTitle>
            <DialogDescription>
              Choisissez le statut et complétez les informations du paiement.
            </DialogDescription>
          </DialogHeader>
          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium mb-1.5 text-foreground">Statut</label>
              <Select
                value={paymentStatusTarget}
                onValueChange={(value) => setPaymentStatusTarget(value as "paid" | "pending")}
              >
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="paid">Payé</SelectItem>
                  <SelectItem value="pending">Non payé</SelectItem>
                </SelectContent>
              </Select>
            </div>
            {paymentStatusTarget === "paid" && (
              <>
                <div>
                  <label className="block text-sm font-medium mb-1.5 text-foreground">Montant</label>
                  <Input
                    type="number"
                    step="0.01"
                    placeholder="0.00"
                    value={paymentForm.amount}
                    onChange={(e) => setPaymentForm((prev) => ({ ...prev, amount: e.target.value }))}
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1.5 text-foreground">Date</label>
                  <Input
                    type="date"
                    value={paymentForm.date}
                    onChange={(e) => setPaymentForm((prev) => ({ ...prev, date: e.target.value }))}
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1.5 text-foreground">Nombre de mois</label>
                  <Input
                    type="number"
                    min="1"
                    value={paymentForm.months}
                    onChange={(e) => setPaymentForm((prev) => ({ ...prev, months: e.target.value }))}
                  />
                </div>
              </>
            )}
            <div className="flex justify-end gap-3 pt-2">
              <Button type="button" variant="outline" onClick={() => setPaymentModalOpen(false)}>
                Annuler
              </Button>
              <Button type="button" onClick={handleConfirmPaymentStatus}>
                Enregistrer
              </Button>
            </div>
          </div>
        </DialogContent>
      </Dialog>
    </DashboardLayout>
  );
}
