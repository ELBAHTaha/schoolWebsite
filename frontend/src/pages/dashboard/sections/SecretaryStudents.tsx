import { Plus, Search, Edit, Trash2 } from "lucide-react";
import { useState } from "react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";
import { apiGet, apiPost, apiPut, apiDelete } from "@/lib/api";
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
import { Checkbox } from "@/components/ui/checkbox";
import { toast } from "sonner";

const studentFormSchema = z.object({
  name: z.string().min(1, "Le nom est requis"),
  email: z.string().email("Email invalide").optional(),
  password: z.string().optional(),
  phone: z.string().optional(),
  class_id: z.string().optional(),
  class_ids: z.array(z.string()).optional(),
  payment_status: z.enum(["paid", "pending", "late"], {
    required_error: "Le statut de paiement est requis",
  }),
  amount_paid: z.string().optional(),
});

type StudentFormData = z.infer<typeof studentFormSchema>;

const statusStyles: Record<string, string> = {
  Payé: "bg-success/10 text-success",
  "Non payé": "bg-warning/10 text-warning",
};

interface Student {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  class_name: string | null;
  class_names?: string[];
  class_ids?: number[];
  account_balance: number;
  payment_status: string;
  status: string;
  created_at: string;
}

interface StudentsResponse {
  data: Student[];
  total: number;
  per_page: number;
  current_page: number;
}

interface ClassesResponse {
  data: Array<{
    id: number;
    name: string;
  }>;
}

export default function SecretaryStudents() {
  const [search, setSearch] = useState("");
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [editingStudent, setEditingStudent] = useState<Student | null>(null);
  const queryClient = useQueryClient();

  const form = useForm<StudentFormData>({
    resolver: zodResolver(studentFormSchema),
    defaultValues: {
      name: "",
      email: "",
      password: "",
      phone: "",
      class_id: "",
      class_ids: [],
      payment_status: "pending",
      amount_paid: "",
    },
  });

  const { data } = useQuery({
    queryKey: ["secretary-students", search],
    queryFn: () => apiGet<StudentsResponse>(`/secretary/students?search=${encodeURIComponent(search)}&limit=200`),
  });

  const { data: classesData } = useQuery({
    queryKey: ["public-classes"],
    queryFn: () => apiGet<ClassesResponse>("/classes"),
  });

  const createStudentMutation = useMutation({
    mutationFn: (data: StudentFormData) => apiPost("/secretary/students", {
      ...data,
      class_id: data.class_id ? parseInt(data.class_id) : null,
      class_ids: (data.class_ids || []).map((id) => parseInt(id)),
      amount_paid: parseFloat(data.amount_paid),
    }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["secretary-students"] });
      setIsAddModalOpen(false);
      form.reset();
      toast.success("Étudiant créé avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la création de l'étudiant");
    },
  });

  const updateStudentMutation = useMutation({
    mutationFn: ({ id, data }: { id: number; data: Partial<StudentFormData> }) =>
      apiPut(`/secretary/students/${id}`, {
        ...data,
        class_id: data.class_id ? parseInt(data.class_id) : null,
        class_ids: (data.class_ids || []).map((id) => parseInt(id)),
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["secretary-students"] });
      setEditingStudent(null);
      form.reset();
      toast.success("Étudiant mis à jour avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la mise à jour de l'étudiant");
    },
  });

  const deleteStudentMutation = useMutation({
    mutationFn: (id: number) => apiDelete(`/secretary/students/${id}`),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["secretary-students"] });
      toast.success("Étudiant supprimé avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la suppression de l'étudiant");
    },
  });

  const students = (data?.data || []).map((student) => ({
    id: student.id,
    name: student.name,
    className: student.class_names?.length
      ? student.class_names.join(", ")
      : student.class_name || "—",
    phone: student.phone || "—",
    status: student.payment_status === "paid" ? "Payé" : "Non payé",
    class_ids: student.class_ids || [],
  }));

  const handleEditStudent = (student: Student) => {
    setEditingStudent(student);
    form.reset({
      name: student.name,
      email: student.email,
      password: "", // Don't prefill password
      phone: student.phone || "",
      class_id: student.class_name ? String(student.class_ids?.[0] ?? "") : "",
      class_ids: (student.class_ids || []).map((id) => String(id)),
      payment_status: student.payment_status as "paid" | "pending" | "late",
      amount_paid: "",
    });
  };

  const handleDeleteStudent = (student: Student) => {
    if (confirm(`Êtes-vous sûr de vouloir supprimer ${student.name} ?`)) {
      deleteStudentMutation.mutate(student.id);
    }
  };

  const onSubmit = (formData: StudentFormData) => {
    if (editingStudent) {
      const updateData = { ...formData };
      if (!updateData.email || updateData.email.trim() === "") {
        updateData.email = editingStudent.email;
      }
      if (!updateData.password) {
        delete updateData.password; // Don't send empty password for updates
      }
      delete updateData.amount_paid;
      updateStudentMutation.mutate({
        id: editingStudent.id,
        data: updateData,
      });
    } else {
      if (!formData.email || formData.email.trim() === "") {
        form.setError("email", {
          message: "Email invalide",
        });
        return;
      }
      if (!formData.password || formData.password.length < 8) {
        form.setError("password", {
          message: "Le mot de passe doit contenir au moins 8 caractères",
        });
        return;
      }
      if (!formData.amount_paid || formData.amount_paid.trim() === "") {
        form.setError("amount_paid", {
          message: "Le montant payé est requis",
        });
        return;
      }
      createStudentMutation.mutate(formData);
    }
  };

  const handleModalClose = () => {
    setIsAddModalOpen(false);
    setEditingStudent(null);
    form.reset();
  };

  return (
    <DashboardLayout role="secretary">
      <div className="space-y-6">
        <DashboardHeader
          title="Étudiants"
          subtitle="Liste et suivi des étudiants"
          action={
            <Dialog
              open={isAddModalOpen || !!editingStudent}
              onOpenChange={(open) => {
                if (!open) {
                  handleModalClose();
                } else {
                  setIsAddModalOpen(true);
                }
              }}
            >
              <DialogTrigger asChild>
                <button
                  onClick={() => setIsAddModalOpen(true)}
                  className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
                >
                  <Plus className="w-4 h-4" /> Ajouter
                </button>
              </DialogTrigger>
              <DialogContent className="sm:max-w-[500px] max-h-[85vh] overflow-hidden">
                <DialogHeader>
                  <DialogTitle>
                    {editingStudent ? "Modifier l'étudiant" : "Ajouter un étudiant"}
                  </DialogTitle>
                  <DialogDescription>
                    {editingStudent
                      ? "Modifiez les informations de l'étudiant ci-dessous."
                      : "Remplissez les informations pour créer un nouvel étudiant."}
                  </DialogDescription>
                </DialogHeader>
                <Form {...form}>
                  <form onSubmit={form.handleSubmit(onSubmit)} className="flex max-h-[75vh] flex-col">
                    <div className="flex-1 space-y-4 overflow-y-auto pr-1">
                      <div className="grid grid-cols-2 gap-4">
                        <FormField
                          control={form.control}
                          name="name"
                          render={({ field }) => (
                            <FormItem>
                              <FormLabel>Nom complet</FormLabel>
                              <FormControl>
                                <Input placeholder="Entrez le nom complet" {...field} />
                              </FormControl>
                              <FormMessage />
                            </FormItem>
                          )}
                        />
                        <FormField
                          control={form.control}
                          name="email"
                          render={({ field }) => (
                            <FormItem>
                              <FormLabel>Email</FormLabel>
                              <FormControl>
                                <Input type="email" placeholder="Entrez l'email" {...field} />
                              </FormControl>
                              <FormMessage />
                            </FormItem>
                          )}
                        />
                      </div>
                      <FormField
                        control={form.control}
                        name="class_ids"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Cours suivis</FormLabel>
                            <div className="grid grid-cols-2 gap-3 rounded-lg border border-input bg-background p-3">
                              {(classesData?.data || []).map((classItem) => {
                                const isChecked = (field.value || []).includes(String(classItem.id));
                                return (
                                  <label key={classItem.id} className="flex items-center gap-2 text-sm text-foreground">
                                    <Checkbox
                                      checked={isChecked}
                                      onCheckedChange={(checked) => {
                                        const current = field.value || [];
                                        if (checked) {
                                          field.onChange([...current, String(classItem.id)]);
                                        } else {
                                          field.onChange(current.filter((id) => id !== String(classItem.id)));
                                        }
                                      }}
                                    />
                                    {classItem.name}
                                  </label>
                                );
                              })}
                              {!classesData?.data?.length && (
                                <div className="text-xs text-muted-foreground">
                                  Aucun cours disponible.
                                </div>
                              )}
                            </div>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                      <div className="grid grid-cols-2 gap-4">
                        <FormField
                          control={form.control}
                          name="password"
                          render={({ field }) => (
                            <FormItem>
                              <FormLabel>
                                Mot de passe {editingStudent && "(laisser vide pour ne pas changer)"}
                              </FormLabel>
                              <FormControl>
                                <Input
                                  type="password"
                                  placeholder="Entrez le mot de passe"
                                  {...field}
                                />
                              </FormControl>
                              <FormMessage />
                            </FormItem>
                          )}
                        />
                        <FormField
                          control={form.control}
                          name="phone"
                          render={({ field }) => (
                            <FormItem>
                              <FormLabel>Téléphone</FormLabel>
                              <FormControl>
                                <Input placeholder="Entrez le numéro de téléphone" {...field} />
                              </FormControl>
                              <FormMessage />
                            </FormItem>
                          )}
                        />
                      </div>
                      {!editingStudent && (
                        <div className="grid grid-cols-2 gap-4">
                          <FormField
                            control={form.control}
                            name="payment_status"
                            render={({ field }) => (
                              <FormItem>
                                <FormLabel>Statut de paiement</FormLabel>
                                <Select onValueChange={field.onChange} defaultValue={field.value}>
                                  <FormControl>
                                    <SelectTrigger>
                                      <SelectValue placeholder="Sélectionnez un statut" />
                                    </SelectTrigger>
                                  </FormControl>
                                  <SelectContent>
                                    <SelectItem value="paid">Payé</SelectItem>
                                    <SelectItem value="pending">En attente</SelectItem>
                                    <SelectItem value="late">En retard</SelectItem>
                                  </SelectContent>
                                </Select>
                                <FormMessage />
                              </FormItem>
                            )}
                          />
                        </div>
                      )}
                      {!editingStudent && (
                        <FormField
                          control={form.control}
                          name="amount_paid"
                          render={({ field }) => (
                            <FormItem>
                              <FormLabel>Montant payé</FormLabel>
                              <FormControl>
                                <Input type="number" step="0.01" placeholder="0.00" {...field} />
                              </FormControl>
                              <FormMessage />
                            </FormItem>
                          )}
                        />
                      )}
                    </div>
                    <div className="flex justify-end gap-3 pt-4">
                      <Button
                        type="button"
                        variant="outline"
                        onClick={handleModalClose}
                      >
                        Annuler
                      </Button>
                      <Button
                        type="submit"
                        disabled={createStudentMutation.isPending || updateStudentMutation.isPending}
                      >
                        {createStudentMutation.isPending || updateStudentMutation.isPending
                          ? "Enregistrement..."
                          : editingStudent
                          ? "Modifier"
                          : "Ajouter"}
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
                placeholder="Rechercher un étudiant..."
              />
            </div>
          </div>
          <SimpleTable
            columns={[
              { key: "name", label: "Étudiant", className: "font-medium text-foreground" },
              { key: "className", label: "Cours", className: "text-muted-foreground" },
              { key: "phone", label: "Téléphone", className: "text-muted-foreground" },
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
                key: "actions",
                label: "Actions",
                render: (row) => (
                  <div className="flex items-center gap-1">
                    <button
                      onClick={() => handleEditStudent(row as Student)}
                      className="p-1.5 rounded-lg hover:bg-secondary transition-colors"
                    >
                      <Edit className="w-4 h-4 text-muted-foreground" />
                    </button>
                    <button
                      onClick={() => handleDeleteStudent(row as Student)}
                      className="p-1.5 rounded-lg hover:bg-accent/10 transition-colors"
                    >
                      <Trash2 className="w-4 h-4 text-accent" />
                    </button>
                  </div>
                ),
              },
            ]}
            rows={students}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
