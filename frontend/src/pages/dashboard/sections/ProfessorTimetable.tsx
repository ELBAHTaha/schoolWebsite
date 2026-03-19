import { Calendar } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { apiGet } from "@/lib/api";

interface TimetableResponse {
  data: Array<{
    id: number;
    day_of_week: string;
    starts_at: string;
    ends_at: string;
    course: string | null;
    room: string | null;
  }>;
  working_hours: Array<{
    day_of_week: string;
    starts_at: string;
    ends_at: string;
  }>;
}

const dayLabels: Record<string, string> = {
  Monday: "Lundi",
  Tuesday: "Mardi",
  Wednesday: "Mercredi",
  Thursday: "Jeudi",
  Friday: "Vendredi",
  Saturday: "Samedi",
  Sunday: "Dimanche",
};

const formatTime = (time: string) => time?.slice(0, 5)?.replace(":", "h") ?? time;

export default function ProfessorTimetable() {
  const { data } = useQuery({
    queryKey: ["professor-timetable"],
    queryFn: () => apiGet<TimetableResponse>("/professor/timetable"),
  });

  const sessions = (data?.data || []).map((item) => ({
    id: item.id,
    day: dayLabels[item.day_of_week] ?? item.day_of_week,
    time: `${formatTime(item.starts_at)} - ${formatTime(item.ends_at)}`,
    course: item.course || "Cours",
    room: item.room || "Salle à confirmer",
  }));

  return (
    <DashboardLayout role="professor">
      <div className="space-y-6">
        <DashboardHeader
          title="Emploi du temps"
          subtitle="Planning des séances de la semaine"
        />

        <div className="grid md:grid-cols-2 gap-4">
          {sessions.map((item) => (
            <div key={item.id} className="bg-card rounded-2xl p-6 shadow-card">
              <div className="flex items-center gap-3 mb-3">
                <div className="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                  <Calendar className="w-5 h-5 text-primary" />
                </div>
                <div>
                  <div className="font-semibold text-foreground">{item.day}</div>
                  <div className="text-xs text-muted-foreground">{item.time}</div>
                </div>
              </div>
              <div className="text-sm font-medium text-foreground">{item.course}</div>
              <div className="text-xs text-muted-foreground">{item.room}</div>
            </div>
          ))}
          {!sessions.length && (
            <div className="text-sm text-muted-foreground">
              Aucun créneau trouvé pour le moment.
            </div>
          )}
        </div>
      </div>
    </DashboardLayout>
  );
}
