import { BookOpen, ClipboardList, Calendar, Bell } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { apiGet } from "@/lib/api";

interface ProfessorDashboardResponse {
  stats: {
    classes: number;
    assignments: number;
    sessions: number;
    notifications: number;
  };
  upcoming: Array<{
    id: number;
    title: string;
    time: string;
    room: string;
  }>;
}

export default function ProfessorDashboard() {
  const { data } = useQuery({
    queryKey: ["professor-dashboard"],
    queryFn: () => apiGet<ProfessorDashboardResponse>("/dashboard/professor"),
  });

  const stats = [
    { label: "Mes cours", value: String(data?.stats.classes ?? 0), icon: BookOpen, color: "bg-primary/10 text-primary" },
    { label: "Devoirs à corriger", value: String(data?.stats.assignments ?? 0), icon: ClipboardList, color: "bg-warning/10 text-warning" },
    { label: "Séances cette semaine", value: String(data?.stats.sessions ?? 0), icon: Calendar, color: "bg-success/10 text-success" },
    { label: "Notifications", value: String(data?.stats.notifications ?? 0), icon: Bell, color: "bg-accent/10 text-accent" },
  ];

  const upcoming = data?.upcoming || [];

  return (
    <DashboardLayout role="professor">
      <div className="space-y-6">
        <DashboardHeader
          title="Espace professeur"
          subtitle="Gérez vos cours, devoirs et planning"
        />

        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
          {stats.map((s) => (
            <div key={s.label} className="bg-card rounded-2xl p-5 shadow-card">
              <div className={`w-10 h-10 rounded-lg flex items-center justify-center mb-3 ${s.color}`}>
                <s.icon className="w-5 h-5" />
              </div>
              <div className="text-xl font-bold text-foreground">{s.value}</div>
              <div className="text-xs text-muted-foreground">{s.label}</div>
            </div>
          ))}
        </div>

        <div className="bg-card rounded-2xl shadow-card p-6">
          <h3 className="font-heading font-semibold mb-4">À venir</h3>
          <div className="space-y-3">
            {upcoming.map((item) => (
              <div key={item.id} className="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 p-4 rounded-lg bg-secondary/50">
                <div>
                  <div className="font-semibold text-foreground">{item.title}</div>
                  <div className="text-xs text-muted-foreground mt-1">{item.time}</div>
                </div>
                <span className="text-xs font-medium text-muted-foreground">{item.room}</span>
              </div>
            ))}
            {!upcoming.length && (
              <div className="text-sm text-muted-foreground">Aucune séance à venir.</div>
            )}
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
