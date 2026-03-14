import { Plus, FileText } from "lucide-react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";

const documents = [
  { title: "Synthèse TOEFL", className: "TOEFL", date: "2026-03-07" },
  { title: "Exercices B2", className: "DELF B2", date: "2026-03-02" },
];

export default function ProfessorDocuments() {
  return (
    <DashboardLayout role="professor">
      <div className="space-y-6">
        <DashboardHeader
          title="Documents"
          subtitle="Gérez vos supports pédagogiques"
          action={
            <button className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity">
              <Plus className="w-4 h-4" /> Ajouter un document
            </button>
          }
        />

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <SimpleTable
            columns={[
              { key: "title", label: "Document", className: "font-medium text-foreground" },
              { key: "className", label: "Classe", className: "text-muted-foreground" },
              { key: "date", label: "Date", className: "text-muted-foreground" },
              {
                key: "action",
                label: "Action",
                render: () => (
                  <button className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold hover:bg-primary/20">
                    <FileText className="w-3 h-3" /> Voir
                  </button>
                ),
              },
            ]}
            rows={documents}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
