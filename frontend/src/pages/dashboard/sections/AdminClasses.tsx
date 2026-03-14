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
  name: z.string().min(1, "Le nom de la classe est requis"),
  professor_id: z.string().min(1, "Le professeur est requis"),
  room_id: z.string().min(1, "La salle est requise"),
  price: z.string().min(1, "Le prix est requis"),
  max_students: z.string().min(1, "Le nombre maximum d'étudiants est requis"),
});

type ClassFormData = z.infer<typeof classFormSchema>;

const classes = [
  { name: "DELF B2", professor: "Marie Laurent", room: "Salle A", students: 18, price: "2,000 DH", status: "Actif" },
  { name: "TOEFL", professor: "John Smith", room: "Salle C", students: 14, price: "2,500 DH", status: "Actif" },
  { name: "IELTS", professor: "Sara Benali", room: "Salle B", students: 12, price: "2,500 DH", status: "Planifié" },
  { name: "TEF", professor: "Ahmed Mansouri", room: "Salle D", students: 9, price: "1,800 DH", status: "Pause" },
];

const statusStyles: Record<string, string> = {
  Actif: "bg-success/10 text-success",
  Planifié: "bg-warning/10 text-warning",
  Pause: "bg-muted text-muted-foreground",
};

export default function AdminClasses() {
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [search, setSearch] = useState("");
  const queryClient = useQueryClient();

  const form = useForm<ClassFormData>({
    resolver: zodResolver(classFormSchema),
    defaultValues: {
      name: "",
      professor_id: "",
      room_id: "",
      price: "",
      max_students: "",
    },
  });

  const createClassMutation = useMutation({
    mutationFn: (data: ClassFormData) => apiPost("/admin/classes", {
      ...data,
      professor_id: parseInt(data.professor_id),
      room_id: parseInt(data.room_id),
      price: parseFloat(data.price),
      max_students: parseInt(data.max_students),
    }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin-classes"] });
      setIsAddModalOpen(false);
      form.reset();
      toast.success("Classe créée avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la création de la classe");
    },
  });

  const onSubmit = (data: ClassFormData) => {
    createClassMutation.mutate(data);
  };

  const filteredClasses = classes.filter((classItem) =>
    classItem.name.toLowerCase().includes(search.toLowerCase()) ||
    classItem.professor.toLowerCase().includes(search.toLowerCase()) ||
    classItem.room.toLowerCase().includes(search.toLowerCase())
  );
  return (
    <DashboardLayout role="admin">
      <div className="space-y-6">
        <DashboardHeader
          title="Gestion des classes"
          subtitle="Créez, organisez et suivez les classes"
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
                            <FormLabel>Professeur</FormLabel>
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
                            <FormLabel>Salle</FormLabel>
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
                    <div className="grid grid-cols-2 gap-4">
                      <FormField
                        control={form.control}
                        name="price"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Prix (DH)</FormLabel>
                            <FormControl>
                              <Input type="number" placeholder="2000" {...field} />
                            </FormControl>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                      <FormField
                        control={form.control}
                        name="max_students"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Nombre max d'étudiants</FormLabel>
                            <FormControl>
                              <Input type="number" placeholder="20" {...field} />
                            </FormControl>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                    </div>
                    <div className="flex justify-end gap-3">
                      <Button type="button" variant="outline" onClick={handleModalClose}>
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
          <div className="px-6 py-4 border-b border-border flex gap-3">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="w-full pl-10 pr-4 py-2 rounded-lg border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                placeholder="Rechercher une classe..."
              />
            </div>
          </div>
          <SimpleTable
            columns={[
              { key: "name", label: "Classe", className: "font-medium text-foreground" },
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
            rows={filteredClasses}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
