import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { Megaphone, Plus, Clock } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import { apiGet } from "@/lib/api";

const fallbackAnnouncements = [
  { title: "Rentrée des classes — Mars 2026", content: "Les cours reprendront le 10 mars 2026. Veuillez vérifier vos emplois du temps.", date: "2026-03-02", author: "Admin", priority: "Important" },
  { title: "Nouveau cours d'Espagnol", content: "Un nouveau cours d'espagnol débutant ouvrira en avril. Inscriptions ouvertes.", date: "2026-02-28", author: "Admin", priority: "Info" },
  { title: "Résultats DELF — Session Février", content: "Les résultats de la session DELF de février sont disponibles au secrétariat.", date: "2026-02-25", author: "Secrétariat", priority: "Info" },
];

const priorityStyles: Record<string, string> = {
  Important: "bg-accent/10 text-accent",
  Info: "bg-primary/10 text-primary",
};

interface AnnouncementResponse {
  data: Array<{
    id: number;
    title: string;
    content: string;
    start_date?: string | null;
    end_date?: string | null;
  }>;
}

interface AnnouncementsProps {
  role?: "admin" | "student" | "professor" | "secretary";
}

export default function Announcements({ role = "admin" }: AnnouncementsProps) {
  const { data } = useQuery({
    queryKey: ["public-announcements"],
    queryFn: () => apiGet<AnnouncementResponse>("/public-announcements"),
  });

  const announcements = data?.data?.length
    ? data.data.map((item) => ({
        title: item.title,
        content: item.content,
        date: item.start_date || "",
        author: "Administration",
        priority: "Info",
      }))
    : fallbackAnnouncements;

  return (
    <DashboardLayout role={role}>
      <div className="space-y-6">
        <DashboardHeader
          title="Annonces"
          subtitle="Les dernières informations importantes de l'académie"
          action={
            <button className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity">
              <Plus className="w-4 h-4" /> Nouvelle annonce
            </button>
          }
        />

        <div className="space-y-4">
          {announcements.map((a) => (
            <div key={`${a.title}-${a.date}`} className="bg-card rounded-2xl p-6 shadow-card">
              <div className="flex items-start justify-between gap-4 mb-3">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-lg bg-primary/10 flex items-center justify-center">
                    <Megaphone className="w-5 h-5 text-primary" />
                  </div>
                  <div>
                    <h3 className="font-semibold text-foreground">{a.title}</h3>
                    <div className="flex items-center gap-2 text-xs text-muted-foreground mt-0.5">
                      <Clock className="w-3 h-3" /> {a.date} • {a.author}
                    </div>
                  </div>
                </div>
                <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${priorityStyles[a.priority]}`}>
                  {a.priority}
                </span>
              </div>
              <p className="text-sm text-muted-foreground ml-[52px]">{a.content}</p>
            </div>
          ))}
        </div>
      </div>
    </DashboardLayout>
  );
}
