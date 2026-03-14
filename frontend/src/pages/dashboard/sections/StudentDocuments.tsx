import { Download } from "lucide-react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";

const documents = [
  { title: "Grammaire B2", course: "DELF B2", type: "PDF", date: "2026-03-10" },
  { title: "Liste vocabulaire TOEFL", course: "TOEFL", type: "PDF", date: "2026-03-05" },
  { title: "Exercices A2", course: "Espagnol A2", type: "PDF", date: "2026-02-28" },
];

export default function StudentDocuments() {
  return (
    <DashboardLayout role="student">
      <div className="space-y-6">
        <DashboardHeader
          title="Documents"
          subtitle="Accédez à vos supports de cours"
        />

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <SimpleTable
            columns={[
              { key: "title", label: "Document", className: "font-medium text-foreground" },
              { key: "course", label: "Cours", className: "text-muted-foreground" },
              { key: "type", label: "Type" },
              { key: "date", label: "Date", className: "text-muted-foreground" },
              {
                key: "action",
                label: "Télécharger",
                render: () => (
                  <button className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold hover:bg-primary/20">
                    <Download className="w-3 h-3" /> Télécharger
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
