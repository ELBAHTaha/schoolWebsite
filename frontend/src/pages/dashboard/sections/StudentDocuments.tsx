import { Download } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";
import { apiGet } from "@/lib/api";

interface StudentMaterialsResponse {
  data: Array<{
    id: number;
    title: string;
    course: string | null;
    download_url: string | null;
    created_at: string | null;
  }>;
}

export default function StudentDocuments() {
  const { data } = useQuery({
    queryKey: ["student-materials"],
    queryFn: () => apiGet<StudentMaterialsResponse>("/student/materials"),
  });

  const documents = (data?.data || []).map((doc) => ({
    id: doc.id,
    title: doc.title,
    course: doc.course || "—",
    type: doc.download_url ? "FICHIER" : "—",
    date: doc.created_at || "",
    fileUrl: doc.download_url,
  }));

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
                render: (row) =>
                  row.fileUrl ? (
                    <a
                      href={row.fileUrl}
                      target="_blank"
                      rel="noreferrer"
                      className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold hover:bg-primary/20"
                    >
                      <Download className="w-3 h-3" /> Télécharger
                    </a>
                  ) : (
                    <span className="text-xs text-muted-foreground">—</span>
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
