import { BookOpen, Users } from "lucide-react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";

const classes = [
  { name: "DELF B2", students: 18, nextSession: "Lun • 10h00", room: "Salle A" },
  { name: "TOEFL", students: 14, nextSession: "Mar • 14h00", room: "Salle C" },
  { name: "IELTS", students: 12, nextSession: "Jeu • 09h00", room: "Salle B" },
];

export default function ProfessorMyClasses() {
  return (
    <DashboardLayout role="professor">
      <div className="space-y-6">
        <DashboardHeader
          title="Mes classes"
          subtitle="Suivez vos classes et prochaines séances"
        />

        <div className="grid md:grid-cols-2 gap-4">
          {classes.map((item) => (
            <div key={item.name} className="bg-card rounded-2xl p-6 shadow-card">
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
        </div>
      </div>
    </DashboardLayout>
  );
}
