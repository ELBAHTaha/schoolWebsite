import { BookOpen, Clock } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { apiGet } from "@/lib/api";

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

export default function StudentMyCourses() {
  const { data } = useQuery({
    queryKey: ["student-dashboard"],
    queryFn: () => apiGet<StudentDashboardResponse>("/dashboard/student"),
  });

  const courses = (data?.courses || []).map((course) => ({
    id: course.id,
    name: course.name,
    professor: course.professor || "—",
    schedule: formatSchedule(course.schedule ?? undefined),
  }));

  return (
    <DashboardLayout role="student">
      <div className="space-y-6">
        <DashboardHeader
          title="Mes cours"
          subtitle="Suivez vos cours et votre progression"
        />

        <div className="grid md:grid-cols-2 gap-4">
          {courses.map((course) => (
            <div key={course.id} className="bg-card rounded-2xl p-6 shadow-card">
              <div className="flex items-center justify-between mb-4">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <BookOpen className="w-5 h-5 text-primary" />
                  </div>
                  <div>
                    <div className="font-semibold text-foreground">{course.name}</div>
                    <div className="text-xs text-muted-foreground">{course.professor}</div>
                  </div>
                </div>
                <div className="flex items-center gap-1 text-xs text-muted-foreground">
                  <Clock className="w-3 h-3" /> {course.schedule}
                </div>
              </div>
            </div>
          ))}
          {!courses.length && (
            <div className="text-sm text-muted-foreground">Aucun cours assigné.</div>
          )}
        </div>
      </div>
    </DashboardLayout>
  );
}
