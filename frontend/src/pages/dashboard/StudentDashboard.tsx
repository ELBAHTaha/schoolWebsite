import { BookOpen, Download, ClipboardList, CreditCard } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { apiGet } from "@/lib/api";

const paymentStatusLabel: Record<string, string> = {
  paid: "Payé",
  pending: "En attente",
  late: "En retard",
};

interface StudentDashboardResponse {
  stats: {
    courses: number;
    homework_pending: number;
    documents: number;
    payment_status: string;
  };
  courses: Array<{
    id: number;
    name: string;
    professor: string | null;
    schedule: {
      day_of_week: string;
      starts_at: string;
      ends_at: string;
    } | null;
  }>;
  homework: Array<{
    id: number;
    title: string;
    course: string | null;
    due: string | null;
    status: string;
  }>;
}

const dayLabels: Record<string, string> = {
  Monday: "Lun",
  Tuesday: "Mar",
  Wednesday: "Mer",
  Thursday: "Jeu",
  Friday: "Ven",
  Saturday: "Sam",
  Sunday: "Dim",
};

const formatSchedule = (schedule?: { day_of_week: string; starts_at: string; ends_at: string }) => {
  if (!schedule) {
    return "À confirmer";
  }
  const day = dayLabels[schedule.day_of_week] ?? schedule.day_of_week;
  const start = schedule.starts_at?.slice(0, 5)?.replace(":", "h") ?? schedule.starts_at;
  const end = schedule.ends_at?.slice(0, 5)?.replace(":", "h") ?? schedule.ends_at;
  return `${day} • ${start}-${end}`;
};

export default function StudentDashboard() {
  const { data } = useQuery({
    queryKey: ["student-dashboard"],
    queryFn: () => apiGet<StudentDashboardResponse>("/dashboard/student"),
  });

  const stats = [
    { label: "Mes cours", value: String(data?.stats.courses ?? 0), icon: BookOpen, color: "bg-primary/10 text-primary" },
    { label: "Devoirs en cours", value: String(data?.stats.homework_pending ?? 0), icon: ClipboardList, color: "bg-warning/10 text-warning" },
    { label: "Documents", value: String(data?.stats.documents ?? 0), icon: Download, color: "bg-success/10 text-success" },
    { label: "Paiement", value: paymentStatusLabel[data?.stats.payment_status ?? "pending"] ?? "En attente", icon: CreditCard, color: "bg-success/10 text-success" },
  ];

  const courses = (data?.courses || []).map((course, index) => ({
    name: course.name,
    prof: course.professor ?? "—",
    schedule: formatSchedule(course.schedule ?? undefined),
    progress: 30 + index * 10,
  }));

  const homework = (data?.homework || []).map((work) => ({
    title: work.title,
    course: work.course ?? "—",
    due: work.due ?? "",
    status: work.status,
  }));

  return (
    <DashboardLayout role="student">
      <div className="space-y-6">
        <DashboardHeader
          title="Mon espace étudiant"
          subtitle="Bienvenue dans votre espace personnel"
        />

        {/* Quick stats */}
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

        {/* My courses */}
        <div className="bg-card rounded-2xl shadow-card p-6">
          <h3 className="font-heading font-semibold mb-4">Mes cours</h3>
          <div className="space-y-4">
            {courses.map((c) => (
              <div key={c.name} className="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 p-4 rounded-lg bg-secondary/50">
                <div>
                  <div className="font-semibold text-foreground">{c.name}</div>
                  <div className="text-xs text-muted-foreground mt-1">{c.prof} • {c.schedule}</div>
                </div>
                <div className="w-full lg:w-24">
                  <div className="flex items-center justify-between text-xs mb-1">
                    <span className="text-muted-foreground">Progrès</span>
                    <span className="font-medium text-foreground">{c.progress}%</span>
                  </div>
                  <div className="h-2 rounded-full bg-muted overflow-hidden">
                    <div className="h-full rounded-full bg-primary transition-all" style={{ width: `${c.progress}%` }} />
                  </div>
                </div>
              </div>
            ))}
            {!courses.length && (
              <div className="text-sm text-muted-foreground">Aucun cours assigné.</div>
            )}
          </div>
        </div>

        {/* Homework */}
        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <div className="px-6 py-4 border-b border-border">
            <h3 className="font-heading font-semibold">Devoirs</h3>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border bg-secondary/50">
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Devoir</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Cours</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Date limite</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Statut</th>
                </tr>
              </thead>
              <tbody>
                {homework.map((h) => (
                  <tr key={h.title} className="border-b border-border last:border-0 hover:bg-secondary/30">
                    <td className="px-6 py-3 font-medium text-foreground">{h.title}</td>
                    <td className="px-6 py-3 text-muted-foreground">{h.course}</td>
                    <td className="px-6 py-3 text-muted-foreground">{h.due}</td>
                    <td className="px-6 py-3">
                      <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${h.status === "Soumis" ? "bg-success/10 text-success" : "bg-warning/10 text-warning"}`}>
                        {h.status}
                      </span>
                    </td>
                  </tr>
                ))}
                {!homework.length && (
                  <tr>
                    <td colSpan={4} className="px-6 py-6 text-sm text-muted-foreground">
                      Aucun devoir pour le moment.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
