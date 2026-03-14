import { Calendar } from "lucide-react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";

const timetable = [
  { day: "Lundi", time: "10h00 - 12h00", course: "DELF B2", room: "Salle A" },
  { day: "Mercredi", time: "10h00 - 12h00", course: "DELF B2", room: "Salle A" },
  { day: "Jeudi", time: "14h00 - 16h00", course: "TOEFL", room: "Salle C" },
  { day: "Samedi", time: "09h00 - 11h00", course: "Espagnol A2", room: "Salle B" },
];

export default function StudentTimetable() {
  return (
    <DashboardLayout role="student">
      <div className="space-y-6">
        <DashboardHeader
          title="Emploi du temps"
          subtitle="Votre planning hebdomadaire"
        />

        <div className="grid md:grid-cols-2 gap-4">
          {timetable.map((item) => (
            <div key={`${item.day}-${item.course}`} className="bg-card rounded-2xl p-6 shadow-card">
              <div className="flex items-center gap-3 mb-3">
                <div className="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                  <Calendar className="w-5 h-5 text-primary" />
                </div>
                <div>
                  <div className="font-semibold text-foreground">{item.day}</div>
                  <div className="text-xs text-muted-foreground">{item.time}</div>
                </div>
              </div>
              <div className="text-sm text-foreground font-medium">{item.course}</div>
              <div className="text-xs text-muted-foreground">{item.room}</div>
            </div>
          ))}
        </div>
      </div>
    </DashboardLayout>
  );
}
