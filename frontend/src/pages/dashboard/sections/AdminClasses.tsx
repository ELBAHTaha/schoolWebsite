import { Plus, Search } from "lucide-react";
import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";
import { apiGet, apiPost } from "@/lib/api";
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
import { Button } from "@/components/ui/button";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { toast } from "sonner";

const classFormSchema = z.object({
  name: z.string().min(1, "Le nom du cours est requis"),
  description: z.string().optional(),
  professor_id: z.string().optional(),
  room_id: z.string().optional(),
  price_1_month: z.string().optional(),
  price_3_month: z.string().optional(),
  price_6_month: z.string().optional(),
});

type ClassFormData = z.infer<typeof classFormSchema>;

const statusStyles: Record<string, string> = {
  Actif: "bg-success/10 text-success",
  Planifié: "bg-warning/10 text-warning",
  Pause: "bg-muted text-muted-foreground",
};

interface ClassesResponse {
  data: Array<{
    id: number;
    name: string;
    description: string | null;
    professor_name: string | null;
    room_name: string | null;
    price_1_month: number | null;
    price_3_month: number | null;
    price_6_month: number | null;
    students_count: number;
    status: string;
  }>;
  total: number;
  per_page: number;
  current_page: number;
}

interface ProfessorsResponse {
  data: Array<{ id: number; name: string }>;
}

interface RoomsResponse {
  data: Array<{ id: number; name: string; capacity: number; description: string | null; status?: string }>;
}

export default function AdminClasses() {
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [search, setSearch] = useState("");
  const queryClient = useQueryClient();

  const form = useForm<ClassFormData>({
    resolver: zodResolver(classFormSchema),
    defaultValues: {
      name: "",
      description: "",
      professor_id: "none",
      room_id: "none",
      price_1_month: "",
      price_3_month: "",
      price_6_month: "",
    },
  });

  const { data } = useQuery({
    queryKey: ["admin-classes", search],
    queryFn: () => apiGet<ClassesResponse>(`/admin/classes?search=${encodeURIComponent(search)}`),
  });

  const { data: professorsData } = useQuery({
    queryKey: ["admin-professors"],
    queryFn: () => apiGet<ProfessorsResponse>("/admin/users?role=professor&limit=200"),
  });

  const { data: roomsData } = useQuery({
    queryKey: ["admin-rooms"],
    queryFn: () => apiGet<RoomsResponse>("/admin/rooms"),
  });

  const createClassMutation = useMutation({
    mutationFn: (payload: ClassFormData) =>
      apiPost("/admin/classes", {
        name: payload.name,
        description: payload.description || null,
        professor_id:
          payload.professor_id && payload.professor_id !== "none"
            ? parseInt(payload.professor_id, 10)
            : null,
        room_id:
          payload.room_id && payload.room_id !== "none"
            ? parseInt(payload.room_id, 10)
            : null,
        price_1_month: payload.price_1_month ? parseFloat(payload.price_1_month) : null,
        price_3_month: payload.price_3_month ? parseFloat(payload.price_3_month) : null,
        price_6_month: payload.price_6_month ? parseFloat(payload.price_6_month) : null,
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin-classes"] });
      setIsAddModalOpen(false);
      form.reset();
      queryClient.invalidateQueries({ queryKey: ["public-classes"] });
      toast.success("Cours créé avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la création du cours");
    },
  });

  const onSubmit = (payload: ClassFormData) => {
    createClassMutation.mutate(payload);
  };

  const handleModalClose = (open: boolean) => {
    if (!open) {
      setIsAddModalOpen(false);
      form.reset();
      return;
    }
    setIsAddModalOpen(true);
  };

  const rows = data?.data?.length
    ? data.data.map((classItem) => ({
        name: classItem.name,
        professor: classItem.professor_name || "—",
        room: classItem.room_name || "—",
        students: classItem.students_count ?? 0,
        price: classItem.price_1_month ? `${classItem.price_1_month} DH` : "—",
        status: classItem.status || "Actif",
      }))
    : [];

  return (
    <DashboardLayout role="admin">
      <div className="space-y-6">
        <DashboardHeader
          title="Gestion des cours"
          subtitle="Créez, organisez et suivez les cours"
          action={
            <Dialog open={isAddModalOpen} onOpenChange={handleModalClose}>
              <DialogTrigger asChild>
                <button
                  onClick={() => setIsAddModalOpen(true)}
                  className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
                >
                  <Plus className="w-4 h-4" /> Nouveau cours
                </button>
              </DialogTrigger>
              <DialogContent className="sm:max-w-[560px]">
                <DialogHeader>
                  <DialogTitle>Créer un nouveau cours</DialogTitle>
                  <DialogDescription>
                    Remplissez les informations pour créer un nouveau cours.
                  </DialogDescription>
                </DialogHeader>
                <Form {...form}>
                  <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
                    <FormField
                      control={form.control}
                      name="name"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Nom du cours</FormLabel>
                          <FormControl>
                            <Input placeholder="Entrez le nom du cours" {...field} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                    <FormField
                      control={form.control}
                      name="description"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Description (optionnel)</FormLabel>
                          <FormControl>
                            <Input placeholder="Description" {...field} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                    <div className="grid grid-cols-2 gap-4">
                      <FormField
                        control={form.control}
                        name="professor_id"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Professeur (optionnel)</FormLabel>
                            <Select onValueChange={field.onChange} defaultValue={field.value}>
                              <FormControl>
                                <SelectTrigger>
                                  <SelectValue placeholder="Sélectionnez un professeur" />
                                </SelectTrigger>
                              </FormControl>
                              <SelectContent>
                                <SelectItem value="none">Aucun</SelectItem>
                                {(professorsData?.data || []).map((professor) => (
                                  <SelectItem key={professor.id} value={String(professor.id)}>
                                    {professor.name}
                                  </SelectItem>
                                ))}
                              </SelectContent>
                            </Select>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                      <FormField
                        control={form.control}
                        name="room_id"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Salle (optionnel)</FormLabel>
                            <Select onValueChange={field.onChange} defaultValue={field.value}>
                              <FormControl>
                                <SelectTrigger>
                                  <SelectValue placeholder="Sélectionnez une salle" />
                                </SelectTrigger>
                              </FormControl>
                              <SelectContent>
                                <SelectItem value="none">Aucune</SelectItem>
                                {(roomsData?.data || []).map((room) => (
                                  <SelectItem key={room.id} value={String(room.id)}>
                                    {room.name}
                                  </SelectItem>
                                ))}
                              </SelectContent>
                            </Select>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                    </div>
                    <div className="grid grid-cols-3 gap-4">
                      <FormField
                        control={form.control}
                        name="price_1_month"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Prix 1 mois (DH)</FormLabel>
                            <FormControl>
                              <Input type="number" placeholder="2000" {...field} />
                            </FormControl>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                      <FormField
                        control={form.control}
                        name="price_3_month"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Prix 3 mois (DH)</FormLabel>
                            <FormControl>
                              <Input type="number" placeholder="5500" {...field} />
                            </FormControl>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                      <FormField
                        control={form.control}
                        name="price_6_month"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Prix 6 mois (DH)</FormLabel>
                            <FormControl>
                              <Input type="number" placeholder="10000" {...field} />
                            </FormControl>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                    </div>
                    <div className="flex justify-end gap-3">
                      <Button type="button" variant="outline" onClick={() => handleModalClose(false)}>
                        Annuler
                      </Button>
                      <Button type="submit" disabled={createClassMutation.isPending}>
                        {createClassMutation.isPending ? "Création..." : "Créer le cours"}
                      </Button>
                    </div>
                  </form>
                </Form>
              </DialogContent>
            </Dialog>
          }
        />

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <div className="px-6 py-4 border-b border-border flex gap-3">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="w-full pl-10 pr-4 py-2 rounded-lg border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                placeholder="Rechercher un cours..."
              />
            </div>
          </div>
          <SimpleTable
            columns={[
              { key: "name", label: "Cours", className: "font-medium text-foreground" },
              { key: "professor", label: "Professeur", className: "text-muted-foreground" },
              { key: "room", label: "Salle", className: "text-muted-foreground" },
              { key: "students", label: "Étudiants" },
              { key: "price", label: "Tarif" },
              {
                key: "status",
                label: "Statut",
                render: (row) => (
                  <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${statusStyles[row.status]}`}>
                    {row.status}
                  </span>
                ),
              },
            ]}
            rows={rows}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}

