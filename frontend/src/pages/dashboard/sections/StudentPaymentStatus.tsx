import { CreditCard } from "lucide-react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";

const payments = [
  { month: "Mars 2026", amount: "2,000 DH", status: "Payé", method: "Cash" },
  { month: "Avril 2026", amount: "2,000 DH", status: "En attente", method: "—" },
  { month: "Mai 2026", amount: "2,000 DH", status: "En attente", method: "—" },
];

const statusStyles: Record<string, string> = {
  "Payé": "bg-success/10 text-success",
  "En attente": "bg-warning/10 text-warning",
};

export default function StudentPaymentStatus() {
  return (
    <DashboardLayout role="student">
      <div className="space-y-6">
        <DashboardHeader
          title="Paiements"
          subtitle="Consultez votre statut de paiement"
        />

        <div className="bg-card rounded-2xl p-6 shadow-card flex items-center gap-4">
          <div className="w-12 h-12 rounded-xl bg-primary/10 flex items-center justify-center">
            <CreditCard className="w-6 h-6 text-primary" />
          </div>
          <div>
            <div className="text-sm text-muted-foreground">Solde actuel</div>
            <div className="text-2xl font-bold text-foreground">0 DH</div>
            <div className="text-xs text-muted-foreground">Dernier paiement le 2 mars 2026</div>
          </div>
        </div>

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <SimpleTable
            columns={[
              { key: "month", label: "Mois", className: "font-medium text-foreground" },
              { key: "amount", label: "Montant" },
              {
                key: "status",
                label: "Statut",
                render: (row) => (
                  <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${statusStyles[row.status]}`}>
                    {row.status}
                  </span>
                ),
              },
              { key: "method", label: "Méthode", className: "text-muted-foreground" },
            ]}
            rows={payments}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
