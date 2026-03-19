import { ClipboardList } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";
import { apiGet } from "@/lib/api";

interface StudentAssignmentsResponse {
  data: Array<{
    id: number;
    title: string;
    course: string | null;
    due: string | null;
    status: string;
    download_url: string | null;
  }>;
}

const statusStyles: Record<string, string> = {
  "À faire": "bg-warning/10 text-warning",
  "A faire": "bg-warning/10 text-warning",
  "Soumis": "bg-success/10 text-success",
  "En retard": "bg-accent/10 text-accent",
};

export default function StudentHomework() {
  const { data } = useQuery({
    queryKey: ["student-assignments"],
    queryFn: () => apiGet<StudentAssignmentsResponse>("/student/assignments"),
  });

  const homework = (data?.data || []).map((item) => ({
    title: item.title,
    course: item.course ?? "—",
    due: item.due ?? "",
    status: item.status,
    downloadUrl: item.download_url,
  }));

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
                  <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${statusStyles[row.status] || "bg-warning/10 text-warning"}`}>
                    {row.status}
                  </span>
                ),
              },
              {
                key: "action",
                label: "Action",
                render: (row) =>
                  row.downloadUrl ? (
                    <a
                      href={row.downloadUrl}
                      target="_blank"
                      rel="noreferrer"
                      className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold hover:bg-primary/20"
                    >
                      <ClipboardList className="w-3 h-3" /> Détails
                    </a>
                  ) : (
                    <span className="text-xs text-muted-foreground">—</span>
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
