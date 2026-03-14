import { Users, CreditCard, FileText, ClipboardList } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { apiGet } from "@/lib/api";

const fallbackTasks = [
  { title: "Valider 3 paiements", status: "En attente" },
  { title: "Créer 2 dossiers étudiants", status: "Aujourd'hui" },
  { title: "Envoyer 5 reçus", status: "Cette semaine" },
];

interface SecretaryDashboardResponse {
  stats: {
    students: number;
    payments_today: number;
    receipts: number;
    requests: number;
  };
  tasks: Array<{
    title: string;
    status: string;
  }>;
}

export default function SecretaryDashboard() {
  const { data } = useQuery({
    queryKey: ["secretary-dashboard"],
    queryFn: () => apiGet<SecretaryDashboardResponse>("/dashboard/secretary"),
  });

  const stats = data?.stats
    ? [
        { label: "Étudiants actifs", value: String(data.stats.students), icon: Users, color: "bg-primary/10 text-primary" },
        { label: "Paiements du jour", value: String(data.stats.payments_today), icon: CreditCard, color: "bg-success/10 text-success" },
        { label: "Reçus à envoyer", value: String(data.stats.receipts), icon: FileText, color: "bg-warning/10 text-warning" },
        { label: "Demandes", value: String(data.stats.requests), icon: ClipboardList, color: "bg-accent/10 text-accent" },
      ]
    : [
        { label: "Étudiants actifs", value: "512", icon: Users, color: "bg-primary/10 text-primary" },
        { label: "Paiements du jour", value: "8", icon: CreditCard, color: "bg-success/10 text-success" },
        { label: "Reçus à envoyer", value: "5", icon: FileText, color: "bg-warning/10 text-warning" },
        { label: "Demandes", value: "12", icon: ClipboardList, color: "bg-accent/10 text-accent" },
      ];

  const tasks = data ? data.tasks : fallbackTasks;

  return (
    <DashboardLayout role="secretary">
      <div className="space-y-6">
        <DashboardHeader
          title="Espace secrétariat"
          subtitle="Suivi des étudiants, paiements et documents"
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
          <h3 className="font-heading font-semibold mb-4">Tâches prioritaires</h3>
          <div className="space-y-3">
            {tasks.map((task) => (
              <div key={task.title} className="flex items-center justify-between p-4 rounded-lg bg-secondary/50">
                <div className="font-medium text-foreground">{task.title}</div>
                <span className="text-xs text-muted-foreground">{task.status}</span>
              </div>
            ))}
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
