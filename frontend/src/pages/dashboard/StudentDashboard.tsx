import { BookOpen, Download, ClipboardList, CreditCard } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { apiGet } from "@/lib/api";

const fallbackCourses = [
  { name: "Français — DELF B2", prof: "Prof. Marie Laurent", schedule: "Lun, Mer, Ven — 10h-12h", progress: 65 },
  { name: "Anglais — TOEFL", prof: "Prof. John Smith", schedule: "Mar, Jeu — 14h-16h", progress: 40 },
];

const fallbackHomework = [
  { title: "Exercice de compréhension orale", course: "DELF B2", due: "2026-03-08", status: "À faire" },
  { title: "Essay Writing — Topic 3", course: "TOEFL", due: "2026-03-10", status: "À faire" },
  { title: "Grammaire — Chapitre 5", course: "DELF B2", due: "2026-03-03", status: "Soumis" },
];

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
    schedule: string | null;
  }>;
  homework: Array<{
    id: number;
    title: string;
    course: string | null;
    due: string | null;
    status: string;
  }>;
}

export default function StudentDashboard() {
  const { data } = useQuery({
    queryKey: ["student-dashboard"],
    queryFn: () => apiGet<StudentDashboardResponse>("/dashboard/student"),
  });

  const stats = data?.stats
    ? [
        { label: "Mes cours", value: String(data.stats.courses), icon: BookOpen, color: "bg-primary/10 text-primary" },
        { label: "Devoirs en cours", value: String(data.stats.homework_pending), icon: ClipboardList, color: "bg-warning/10 text-warning" },
        { label: "Documents", value: String(data.stats.documents), icon: Download, color: "bg-success/10 text-success" },
        { label: "Paiement", value: paymentStatusLabel[data.stats.payment_status] ?? "En attente", icon: CreditCard, color: "bg-success/10 text-success" },
      ]
    : [
        { label: "Mes cours", value: "2", icon: BookOpen, color: "bg-primary/10 text-primary" },
        { label: "Devoirs en cours", value: "2", icon: ClipboardList, color: "bg-warning/10 text-warning" },
        { label: "Documents", value: "8", icon: Download, color: "bg-success/10 text-success" },
        { label: "Paiement", value: "Payé", icon: CreditCard, color: "bg-success/10 text-success" },
      ];

  const courses = data
    ? data.courses.map((course, index) => ({
        name: course.name,
        prof: course.professor ?? "—",
        schedule: course.schedule ?? "Horaires à venir",
        progress: 30 + index * 10,
      }))
    : fallbackCourses;

  const homework = data
    ? data.homework.map((work) => ({
        title: work.title,
        course: work.course ?? "—",
        due: work.due ?? "",
        status: work.status,
      }))
    : fallbackHomework;

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
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
