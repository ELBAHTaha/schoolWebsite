import { BookOpen, Plus } from "lucide-react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";

const classes = [
  { name: "DELF B2", students: 18, professor: "Marie Laurent", status: "Actif" },
  { name: "TOEFL", students: 14, professor: "John Smith", status: "Actif" },
  { name: "TEF", students: 9, professor: "Ahmed Mansouri", status: "Planifié" },
];

const statusStyles: Record<string, string> = {
  Actif: "bg-success/10 text-success",
  Planifié: "bg-warning/10 text-warning",
};

export default function SecretaryClasses() {
  return (
    <DashboardLayout role="secretary">
      <div className="space-y-6">
        <DashboardHeader
          title="Classes"
          subtitle="Organisation des classes et affectations"
          action={
            <button className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity">
              <Plus className="w-4 h-4" /> Nouvelle classe
            </button>
          }
        />

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <SimpleTable
            columns={[
              { key: "name", label: "Classe", className: "font-medium text-foreground" },
              { key: "professor", label: "Professeur", className: "text-muted-foreground" },
              { key: "students", label: "Étudiants" },
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
                    <BookOpen className="w-3 h-3" /> Détails
                  </button>
                ),
              },
            ]}
            rows={classes}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
