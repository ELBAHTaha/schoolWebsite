import { BookOpen, Clock } from "lucide-react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";

const courses = [
  { name: "Français — DELF B2", professor: "Marie Laurent", schedule: "Lun, Mer, Ven • 10h-12h", progress: 65 },
  { name: "Anglais — TOEFL", professor: "John Smith", schedule: "Mar, Jeu • 14h-16h", progress: 40 },
  { name: "Espagnol — A2", professor: "Leila Bouazza", schedule: "Sam • 09h-11h", progress: 20 },
];

export default function StudentMyCourses() {
  return (
    <DashboardLayout role="student">
      <div className="space-y-6">
        <DashboardHeader
          title="Mes cours"
          subtitle="Suivez vos cours et votre progression"
        />

        <div className="grid md:grid-cols-2 gap-4">
          {courses.map((course) => (
            <div key={course.name} className="bg-card rounded-2xl p-6 shadow-card">
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
              <div className="text-xs text-muted-foreground mb-2">Progression</div>
              <div className="h-2 rounded-full bg-muted overflow-hidden">
                <div className="h-full rounded-full bg-primary" style={{ width: `${course.progress}%` }} />
              </div>
            </div>
          ))}
        </div>
      </div>
    </DashboardLayout>
  );
}
