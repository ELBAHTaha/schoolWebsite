import { ClipboardList } from "lucide-react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";

const homework = [
  { title: "Compréhension orale", course: "DELF B2", due: "2026-03-18", status: "À faire" },
  { title: "Essay Writing — Topic 4", course: "TOEFL", due: "2026-03-16", status: "À faire" },
  { title: "Grammaire — Chapitre 5", course: "DELF B2", due: "2026-03-10", status: "Soumis" },
];

const statusStyles: Record<string, string> = {
  "À faire": "bg-warning/10 text-warning",
  "Soumis": "bg-success/10 text-success",
};

export default function StudentHomework() {
  return (
    <DashboardLayout role="student">
      <div className="space-y-6">
        <DashboardHeader
          title="Devoirs"
          subtitle="Suivez vos devoirs et échéances"
        />

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <SimpleTable
            columns={[
              { key: "title", label: "Devoir", className: "font-medium text-foreground" },
              { key: "course", label: "Cours", className: "text-muted-foreground" },
              { key: "due", label: "Date limite", className: "text-muted-foreground" },
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
                label: "Action",
                render: () => (
                  <button className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold hover:bg-primary/20">
                    <ClipboardList className="w-3 h-3" /> Détails
                  </button>
                ),
              },
            ]}
            rows={homework}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
