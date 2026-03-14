import { FileText } from "lucide-react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";

const receipts = [
  { student: "Fatima Zahra", period: "Mars 2026", status: "Disponible" },
  { student: "Youssef Alami", period: "Mars 2026", status: "En attente" },
];

const statusStyles: Record<string, string> = {
  Disponible: "bg-success/10 text-success",
  "En attente": "bg-warning/10 text-warning",
};

export default function SecretaryReceipts() {
  return (
    <DashboardLayout role="secretary">
      <div className="space-y-6">
        <DashboardHeader
          title="Reçus"
          subtitle="Gestion des reçus et attestations"
        />

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <SimpleTable
            columns={[
              { key: "student", label: "Étudiant", className: "font-medium text-foreground" },
              { key: "period", label: "Période", className: "text-muted-foreground" },
              {
                key: "status",
                label: "Statut",
                render: (row) => (
                  <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${statusStyles[row.status]}`}>
                    {row.status}
                  </span>
                ),
              },
              {
                key: "action",
                label: "Télécharger",
                render: () => (
                  <button className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold hover:bg-primary/20">
                    <FileText className="w-3 h-3" /> PDF
                  </button>
                ),
              },
            ]}
            rows={receipts}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
