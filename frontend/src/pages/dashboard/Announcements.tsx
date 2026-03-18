import { useState } from "react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { Megaphone, Plus, Clock } from "lucide-react";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { apiGet, apiPost } from "@/lib/api";
import { useForm } from "react-hook-form";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
  DialogTrigger,
} from "@/components/ui/dialog";
import {
  Form,
  FormControl,
  FormField,
  FormItem,
  FormLabel,
  FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { Button } from "@/components/ui/button";
import { toast } from "sonner";

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

const announcementFormSchema = z.object({
  title: z.string().min(1, "Le titre est requis"),
  content: z.string().min(1, "Le contenu est requis"),
  start_date: z.string().optional(),
  end_date: z.string().optional(),
});

type AnnouncementFormData = z.infer<typeof announcementFormSchema>;

export default function Announcements({ role = "admin" }: AnnouncementsProps) {
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const queryClient = useQueryClient();

  const form = useForm<AnnouncementFormData>({
    resolver: zodResolver(announcementFormSchema),
    defaultValues: {
      title: "",
      content: "",
      start_date: "",
      end_date: "",
    },
  });

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

  const createAnnouncementMutation = useMutation({
    mutationFn: (payload: AnnouncementFormData) =>
      apiPost("/admin/announcements", {
        title: payload.title,
        content: payload.content,
        start_date: payload.start_date || null,
        end_date: payload.end_date || null,
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["public-announcements"] });
      setIsAddModalOpen(false);
      form.reset();
      toast.success("Annonce créée avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la création de l'annonce");
    },
  });

  const onSubmit = (payload: AnnouncementFormData) => {
    createAnnouncementMutation.mutate(payload);
  };

  return (
    <DashboardLayout role={role}>
      <div className="space-y-6">
        <DashboardHeader
          title="Annonces"
          subtitle="Les dernières informations importantes de l'académie"
          action={
            role === "admin" ? (
              <Dialog open={isAddModalOpen} onOpenChange={setIsAddModalOpen}>
                <DialogTrigger asChild>
                  <button className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity">
                    <Plus className="w-4 h-4" /> Nouvelle annonce
                  </button>
                </DialogTrigger>
                <DialogContent className="sm:max-w-[520px]">
                  <DialogHeader>
                    <DialogTitle>Publier une annonce</DialogTitle>
                    <DialogDescription>
                      Remplissez les informations pour publier une nouvelle annonce publique.
                    </DialogDescription>
                  </DialogHeader>
                  <Form {...form}>
                    <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
                      <FormField
                        control={form.control}
                        name="title"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Titre</FormLabel>
                            <FormControl>
                              <Input placeholder="Titre de l'annonce" {...field} />
                            </FormControl>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                      <FormField
                        control={form.control}
                        name="content"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Contenu</FormLabel>
                            <FormControl>
                              <Textarea placeholder="Contenu de l'annonce" {...field} />
                            </FormControl>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                      <div className="grid grid-cols-2 gap-4">
                        <FormField
                          control={form.control}
                          name="start_date"
                          render={({ field }) => (
                            <FormItem>
                              <FormLabel>Date de début</FormLabel>
                              <FormControl>
                                <Input type="date" {...field} />
                              </FormControl>
                              <FormMessage />
                            </FormItem>
                          )}
                        />
                        <FormField
                          control={form.control}
                          name="end_date"
                          render={({ field }) => (
                            <FormItem>
                              <FormLabel>Date de fin</FormLabel>
                              <FormControl>
                                <Input type="date" {...field} />
                              </FormControl>
                              <FormMessage />
                            </FormItem>
                          )}
                        />
                      </div>
                      <div className="flex justify-end gap-3">
                        <Button type="button" variant="outline" onClick={() => setIsAddModalOpen(false)}>
                          Annuler
                        </Button>
                        <Button type="submit" disabled={createAnnouncementMutation.isPending}>
                          {createAnnouncementMutation.isPending ? "Publication..." : "Publier"}
                        </Button>
                      </div>
                    </form>
                  </Form>
                </DialogContent>
              </Dialog>
            ) : null
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
