import { BookOpen, Users } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { apiGet } from "@/lib/api";

interface ProfessorClassesResponse {
  data: Array<{
    id: number;
    name: string;
    room_name: string | null;
    students_count: number;
    next_schedule: {
      day_of_week: string;
      starts_at: string;
      ends_at: string;
    } | null;
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

const formatNextSession = (schedule?: { day_of_week: string; starts_at: string }) => {
  if (!schedule) {
    return "À confirmer";
  }
  const day = dayLabels[schedule.day_of_week] ?? schedule.day_of_week;
  const time = schedule.starts_at?.slice(0, 5)?.replace(":", "h") ?? schedule.starts_at;
  return `${day} • ${time}`;
};

export default function ProfessorMyClasses() {
  const { data } = useQuery({
    queryKey: ["professor-classes"],
    queryFn: () => apiGet<ProfessorClassesResponse>("/professor/classes"),
  });

  const classes = (data?.data || []).map((item) => ({
    id: item.id,
    name: item.name,
    room: item.room_name || "Salle à confirmer",
    students: item.students_count ?? 0,
    nextSession: formatNextSession(item.next_schedule ?? undefined),
  }));

  return (
    <DashboardLayout role="professor">
      <div className="space-y-6">
        <DashboardHeader
          title="Mes cours"
          subtitle="Suivez vos cours et prochaines séances"
        />

        <div className="grid md:grid-cols-2 gap-4">
          {classes.map((item) => (
            <div key={item.id} className="bg-card rounded-2xl p-6 shadow-card">
              <div className="flex items-center justify-between mb-4">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <BookOpen className="w-5 h-5 text-primary" />
                  </div>
                  <div>
                    <div className="font-semibold text-foreground">{item.name}</div>
                    <div className="text-xs text-muted-foreground">{item.room}</div>
                  </div>
                </div>
                <div className="flex items-center gap-2 text-xs text-muted-foreground">
                  <Users className="w-4 h-4" /> {item.students} étudiants
                </div>
              </div>
              <div className="text-xs text-muted-foreground">Prochaine séance</div>
              <div className="text-sm font-medium text-foreground mt-1">{item.nextSession}</div>
            </div>
          ))}
          {!classes.length && (
            <div className="text-sm text-muted-foreground">Aucun cours assigné.</div>
          )}
        </div>
      </div>
    </DashboardLayout>
  );
}
