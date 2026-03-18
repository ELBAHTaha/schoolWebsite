import { BookOpen, Plus } from "lucide-react";
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
  name: z.string().min(1, "Le nom de la classe est requis"),
  professor_id: z.string().optional(),
  room_id: z.string().optional(),
  price_1_month: z.string().optional(),
});

type ClassFormData = z.infer<typeof classFormSchema>;

const fallbackClasses = [
  { name: "DELF B2", students: 18, professor: "Marie Laurent", status: "Actif" },
  { name: "TOEFL", students: 14, professor: "John Smith", status: "Actif" },
  { name: "TEF", students: 9, professor: "Ahmed Mansouri", status: "Planifié" },
];

const statusStyles: Record<string, string> = {
  Actif: "bg-success/10 text-success",
  Planifié: "bg-warning/10 text-warning",
};

interface ClassesResponse {
  data: Array<{
    id: number;
    name: string;
    professor_name: string | null;
    room_name: string | null;
    students_count: number;
    status: string;
  }>;
}

export default function SecretaryClasses() {
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const queryClient = useQueryClient();

  const form = useForm<ClassFormData>({
    resolver: zodResolver(classFormSchema),
    defaultValues: {
      name: "",
      professor_id: "",
      room_id: "",
      price_1_month: "",
    },
  });

  const { data } = useQuery({
    queryKey: ["secretary-classes"],
    queryFn: () => apiGet<ClassesResponse>("/secretary/classes"),
  });

  const createClassMutation = useMutation({
    mutationFn: (payload: ClassFormData) =>
      apiPost("/secretary/classes", {
        name: payload.name,
        professor_id: payload.professor_id ? parseInt(payload.professor_id) : null,
        room_id: payload.room_id ? parseInt(payload.room_id) : null,
        price_1_month: payload.price_1_month ? parseFloat(payload.price_1_month) : null,
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["secretary-classes"] });
      setIsAddModalOpen(false);
      form.reset();
      toast.success("Classe créée avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la création de la classe");
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

  const rows = data
    ? data.data.map((classItem) => ({
        name: classItem.name,
        professor: classItem.professor_name || "—",
        students: classItem.students_count ?? 0,
        status: classItem.status || "Actif",
      }))
    : fallbackClasses;

  return (
    <DashboardLayout role="secretary">
      <div className="space-y-6">
        <DashboardHeader
          title="Classes"
          subtitle="Organisation des classes et affectations"
          action={
            <Dialog open={isAddModalOpen} onOpenChange={handleModalClose}>
              <DialogTrigger asChild>
                <button
                  onClick={() => setIsAddModalOpen(true)}
                  className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
                >
                  <Plus className="w-4 h-4" /> Nouvelle classe
                </button>
              </DialogTrigger>
              <DialogContent className="sm:max-w-[500px]">
                <DialogHeader>
                  <DialogTitle>Créer une nouvelle classe</DialogTitle>
                  <DialogDescription>
                    Remplissez les informations pour créer une nouvelle classe.
                  </DialogDescription>
                </DialogHeader>
                <Form {...form}>
                  <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
                    <FormField
                      control={form.control}
                      name="name"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Nom de la classe</FormLabel>
                          <FormControl>
                            <Input placeholder="Entrez le nom de la classe" {...field} />
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
                                <SelectItem value="1">Marie Laurent</SelectItem>
                                <SelectItem value="2">John Smith</SelectItem>
                                <SelectItem value="3">Sara Benali</SelectItem>
                                <SelectItem value="4">Ahmed Mansouri</SelectItem>
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
                                <SelectItem value="1">Salle A</SelectItem>
                                <SelectItem value="2">Salle B</SelectItem>
                                <SelectItem value="3">Salle C</SelectItem>
                                <SelectItem value="4">Salle D</SelectItem>
                              </SelectContent>
                            </Select>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                    </div>
                    <FormField
                      control={form.control}
                      name="price_1_month"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Prix 1 mois (DH, optionnel)</FormLabel>
                          <FormControl>
                            <Input type="number" placeholder="2000" {...field} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                    <div className="flex justify-end gap-3">
                      <Button type="button" variant="outline" onClick={() => handleModalClose(false)}>
                        Annuler
                      </Button>
                      <Button type="submit" disabled={createClassMutation.isPending}>
                        {createClassMutation.isPending ? "Création..." : "Créer la classe"}
                      </Button>
                    </div>
                  </form>
                </Form>
              </DialogContent>
            </Dialog>
          }
        />

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <SimpleTable
            columns={[
              { key: "name", label: "Classe", className: "font-medium text-foreground" },
              { key: "professor", label: "Professeur", className: "text-muted-foreground" },
              { key: "students", label: "Étudiants" },
              {
                key: "status",
                label: "Statut",
                render: (row) => (
                  <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${statusStyles[row.status]}`}>
                    {row.status}
                  </span>
                ),
              },
              {
                key: "action",
                label: "Action",
                render: () => (
                  <button className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold hover:bg-primary/20">
                    <BookOpen className="w-3 h-3" /> Détails
                  </button>
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
