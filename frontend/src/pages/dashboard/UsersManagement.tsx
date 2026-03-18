import { useMemo, useState } from "react";
import { Search, Plus, Filter, Edit, Trash2, ChevronLeft, ChevronRight } from "lucide-react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { useFieldArray, useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
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
import { useAuth } from "@/contexts/AuthContext";

const userFormSchema = z.object({
  name: z.string().min(1, "Le nom est requis"),
  email: z.string().email("Email invalide"),
  password: z.string().optional(),
  role: z.enum(["admin", "directeur", "professor", "secretary", "student", "visitor", "commercial"], {
    required_error: "Le rôle est requis",
  }),
  phone: z.string().optional(),
  class_ids: z.array(z.string()).optional(),
  payment_status: z.enum(["paid", "pending", "late"]).optional(),
  working_hours: z
    .array(
      z.object({
        day: z.string().optional(),
        starts_at: z.string().optional(),
        ends_at: z.string().optional(),
      })
    )
    .optional(),
});

type UserFormData = z.infer<typeof userFormSchema>;

const mockUsers = Array.from({ length: 20 }, (_, i) => ({
  id: i + 1,
  name: ["Fatima Zahra", "Youssef Alami", "Sara Benali", "Ahmed Mansouri", "Khadija El Idrissi", "Omar Tahiri", "Leila Bouazza", "Rachid Moussaoui"][i % 8],
  email: `user${i + 1}@email.com`,
  role: ["student", "professor", "secretary", "admin"][i % 4],
  status: i % 5 === 0 ? "Inactif" : "Actif",
}));

const roleStyles: Record<string, string> = {
  admin: "bg-accent/10 text-accent",
  directeur: "bg-primary/10 text-primary",
  professor: "bg-primary/10 text-primary",
  secretary: "bg-gold/10 text-gold",
  student: "bg-success/10 text-success",
  visitor: "bg-muted text-muted-foreground",
  commercial: "bg-warning/10 text-warning",
};

const roleLabels: Record<string, string> = {
  admin: "Admin",
  directeur: "Directeur",
  professor: "Professeur",
  secretary: "Secrétaire",
  student: "Étudiant",
  visitor: "Visiteur",
  commercial: "Commercial",
};

const paymentStatusLabels: Record<string, string> = {
  paid: "Payé",
  pending: "Non payé",
  late: "Non payé",
};

const paymentStatusStyles: Record<string, string> = {
  paid: "bg-success/10 text-success",
  pending: "bg-warning/10 text-warning",
  late: "bg-warning/10 text-warning",
};

interface UsersResponse {
  data: Array<{
    id: number;
    name: string;
    email: string;
    phone?: string | null;
    role: string;
    status: string;
    class_ids?: number[];
    payment_status?: string;
  }>;
  total: number;
}

interface ClassesResponse {
  data: Array<{
    id: number;
    name: string;
  }>;
}

export default function UsersManagement() {
  const [search, setSearch] = useState("");
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [editingUser, setEditingUser] = useState<any>(null);
  const queryClient = useQueryClient();
  const { user: currentUser } = useAuth();
  const isAdmin = currentUser?.role === "admin";

  const roleOptions = isAdmin
    ? [
        { value: "student", label: "Étudiant" },
        { value: "professor", label: "Professeur" },
        { value: "secretary", label: "Secrétaire" },
        { value: "commercial", label: "Commercial" },
        { value: "directeur", label: "Directeur" },
        { value: "admin", label: "Admin" },
        { value: "visitor", label: "Visiteur" },
      ]
    : [
        { value: "student", label: "Étudiant" },
        { value: "professor", label: "Professeur" },
        { value: "visitor", label: "Visiteur" },
      ];

  const form = useForm<UserFormData>({
    resolver: zodResolver(userFormSchema),
    defaultValues: {
      name: "",
      email: "",
      password: "",
      role: "student",
      phone: "",
      class_ids: [],
      payment_status: "pending",
      working_hours: [{ day: "", starts_at: "", ends_at: "" }],
    },
  });
  const { fields: workingHourFields, append: appendWorkingHour, remove: removeWorkingHour } =
    useFieldArray({
      control: form.control,
      name: "working_hours",
    });
  const selectedRole = form.watch("role");

  const { data } = useQuery({
    queryKey: ["admin-users", search],
    queryFn: () => apiGet<UsersResponse>(`/admin/users?search=${encodeURIComponent(search)}`),
  });

  const { data: classesData } = useQuery({
    queryKey: ["public-classes"],
    queryFn: () => apiGet<ClassesResponse>("/classes"),
  });

  const createUserMutation = useMutation({
    mutationFn: (data: UserFormData) =>
      apiPost("/admin/users", {
        ...data,
        class_ids: data.role === "student" ? (data.class_ids || []).map((id) => parseInt(id)) : [],
        payment_status: data.role === "student" ? data.payment_status : undefined,
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin-users"] });
      setIsAddModalOpen(false);
      form.reset();
      toast.success("Utilisateur créé avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la création de l'utilisateur");
    },
  });

  const updateUserMutation = useMutation({
    mutationFn: ({ id, data }: { id: number; data: Partial<UserFormData> }) =>
      apiPut(`/admin/users/${id}`, {
        ...data,
        class_ids:
          data.role === "student"
            ? (data.class_ids || []).map((id) => parseInt(id))
            : [],
        payment_status: data.role === "student" ? data.payment_status : undefined,
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin-users"] });
      setEditingUser(null);
      form.reset();
      toast.success("Utilisateur mis à jour avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la mise à jour de l'utilisateur");
    },
  });

  const deleteUserMutation = useMutation({
    mutationFn: (id: number) => apiDelete(`/admin/users/${id}`),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin-users"] });
      toast.success("Utilisateur supprimé avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la suppression de l'utilisateur");
    },
  });

  const handleEditUser = (user: any) => {
    setEditingUser(user);
    form.reset({
      name: user.name,
      email: user.email,
      password: "", // Don't prefill password
      role: user.role,
      phone: user.phone || "",
      class_ids: (user.class_ids || []).map((id: number) => String(id)),
      payment_status: user.payment_status || "pending",
      working_hours: [{ day: "", starts_at: "", ends_at: "" }],
    });
  };

  const updatePaymentStatusMutation = useMutation({
    mutationFn: (payload: { user: any; status: "paid" | "pending" | "late" }) =>
      apiPut(`/admin/users/${payload.user.id}`, {
        name: payload.user.name,
        email: payload.user.email,
        role: payload.user.role,
        phone: payload.user.phone || "",
        class_ids: payload.user.class_ids || [],
        payment_status: payload.status,
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin-users"] });
      toast.success("Statut de paiement mis à jour");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la mise à jour du statut");
    },
  });

  const handleDeleteUser = (user: any) => {
    if (currentUser?.role === "directeur" && user.role === "admin") {
      toast.error("Un directeur ne peut pas supprimer un administrateur.");
      return;
    }
    if (confirm(`Êtes-vous sûr de vouloir supprimer ${user.name} ?`)) {
      deleteUserMutation.mutate(user.id);
    }
  };

  const onSubmit = (data: UserFormData) => {
    if (!editingUser && (!data.password || data.password.length < 8)) {
      form.setError("password", {
        message: "Le mot de passe doit contenir au moins 8 caractères",
      });
      return;
    }

    const preparedData: UserFormData = { ...data };
    const normalizedWorkingHours =
      preparedData.working_hours?.filter(
        (slot) => slot.day && slot.starts_at && slot.ends_at
      ) ?? [];

    if (preparedData.role === "professor" && normalizedWorkingHours.length > 0) {
      preparedData.working_hours = normalizedWorkingHours;
    } else {
      delete preparedData.working_hours;
    }

    if (editingUser) {
      const updateData = { ...preparedData };
      if (!updateData.password) {
        delete updateData.password; // Don't send empty password for updates
      }
      if (updateData.role !== "student") {
        delete updateData.class_ids;
        delete updateData.payment_status;
      }
      updateUserMutation.mutate({
        id: editingUser.id,
        data: updateData,
      });
    } else {
      if (preparedData.role !== "student") {
        delete preparedData.class_ids;
        delete preparedData.payment_status;
      }
      createUserMutation.mutate(preparedData as Required<UserFormData>);
    }
  };

  const handleModalClose = () => {
    setIsAddModalOpen(false);
    setEditingUser(null);
    form.reset();
  };

  const fallbackUsers = useMemo(
    () =>
      mockUsers
        .filter((u) =>
          u.name.toLowerCase().includes(search.toLowerCase()) || u.email.includes(search.toLowerCase())
        )
        .map((u) => ({
          ...u,
          roleLabel: roleLabels[u.role] ?? "Utilisateur",
        })),
    [search]
  );

  const users = data
    ? data.data.map((user) => ({
        id: user.id,
        name: user.name,
        email: user.email,
        phone: user.phone ?? "",
        role: user.role,
        roleLabel: roleLabels[user.role] ?? "Utilisateur",
        status: user.status === "inactive" ? "Inactif" : "Actif",
        class_ids: user.class_ids || [],
        payment_status: user.payment_status,
      }))
    : fallbackUsers;

  return (
    <DashboardLayout role="admin">
      <div className="space-y-6">
        <DashboardHeader
          title="Gestion des utilisateurs"
          subtitle={`${data?.total ?? mockUsers.length} utilisateurs au total`}
          action={
            <Dialog
              open={isAddModalOpen || !!editingUser}
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
              <DialogContent className="sm:max-w-[425px]">
                <DialogHeader>
                  <DialogTitle>
                    {editingUser ? "Modifier l'utilisateur" : "Ajouter un utilisateur"}
                  </DialogTitle>
                  <DialogDescription>
                    {editingUser
                      ? "Modifiez les informations de l'utilisateur ci-dessous."
                      : "Remplissez les informations pour créer un nouvel utilisateur."}
                  </DialogDescription>
                </DialogHeader>
                <Form {...form}>
                  <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
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
                    <FormField
                      control={form.control}
                      name="password"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>
                            Mot de passe {editingUser && "(laisser vide pour ne pas changer)"}
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
                      name="role"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Rôle</FormLabel>
                          <Select onValueChange={field.onChange} defaultValue={field.value}>
                            <FormControl>
                              <SelectTrigger>
                                <SelectValue placeholder="Sélectionnez un rôle" />
                              </SelectTrigger>
                            </FormControl>
                            <SelectContent>
                              {roleOptions.map((role) => (
                                <SelectItem key={role.value} value={role.value}>
                                  {role.label}
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
                      name="phone"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Téléphone (optionnel)</FormLabel>
                          <FormControl>
                            <Input placeholder="Entrez le numéro de téléphone" {...field} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                    {selectedRole === "student" && (
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
                                  <label
                                    key={classItem.id}
                                    className="flex items-center gap-2 text-sm text-foreground"
                                  >
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
                                  Aucune classe disponible.
                                </div>
                              )}
                            </div>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                    )}
                    {selectedRole === "student" && (
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
                                <SelectItem value="late">Non payé</SelectItem>
                              </SelectContent>
                            </Select>
                            <FormMessage />
                          </FormItem>
                        )}
                      />
                    )}
                    {selectedRole === "professor" && (
                      <div className="space-y-3 rounded-lg border border-border p-3">
                        <div className="flex items-center justify-between">
                          <FormLabel className="text-sm">Horaires de travail</FormLabel>
                          <Button
                            type="button"
                            variant="outline"
                            onClick={() =>
                              appendWorkingHour({ day: "", starts_at: "", ends_at: "" })
                            }
                          >
                            Ajouter
                          </Button>
                        </div>
                        {workingHourFields.map((field, index) => (
                          <div key={field.id} className="grid grid-cols-3 gap-2">
                            <select
                              className="w-full rounded-lg border border-input bg-background px-3 py-2 text-sm"
                              {...form.register(`working_hours.${index}.day` as const)}
                            >
                              <option value="">Jour</option>
                              <option value="Monday">Monday</option>
                              <option value="Tuesday">Tuesday</option>
                              <option value="Wednesday">Wednesday</option>
                              <option value="Thursday">Thursday</option>
                              <option value="Friday">Friday</option>
                              <option value="Saturday">Saturday</option>
                              <option value="Sunday">Sunday</option>
                            </select>
                            <Input
                              type="time"
                              {...form.register(`working_hours.${index}.starts_at` as const)}
                            />
                            <div className="flex gap-2">
                              <Input
                                type="time"
                                {...form.register(`working_hours.${index}.ends_at` as const)}
                              />
                              <Button
                                type="button"
                                variant="outline"
                                onClick={() => removeWorkingHour(index)}
                              >
                                Retirer
                              </Button>
                            </div>
                          </div>
                        ))}
                      </div>
                    )}
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
                        disabled={createUserMutation.isPending || updateUserMutation.isPending}
                      >
                        {createUserMutation.isPending || updateUserMutation.isPending
                          ? "Enregistrement..."
                          : editingUser
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
          {/* Search */}
          <div className="px-6 py-4 border-b border-border flex flex-col sm:flex-row gap-3">
            <div className="relative flex-1">
              <Search className="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-muted-foreground" />
              <input
                value={search}
                onChange={(e) => setSearch(e.target.value)}
                className="w-full pl-10 pr-4 py-2 rounded-lg border border-input bg-background text-sm focus:outline-none focus:ring-2 focus:ring-ring"
                placeholder="Rechercher un utilisateur..."
              />
            </div>
            <button className="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-input text-sm hover:bg-secondary transition-colors">
              <Filter className="w-4 h-4" /> Filtrer
            </button>
          </div>

          {/* Table */}
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border bg-secondary/50">
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">ID</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Nom</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Email</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Rôle</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Statut</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Paiement</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Actions</th>
                </tr>
              </thead>
              <tbody>
                {users.slice(0, 10).map((u) => (
                  <tr key={u.id} className="border-b border-border last:border-0 hover:bg-secondary/30 transition-colors">
                    <td className="px-6 py-3 text-muted-foreground">#{u.id}</td>
                    <td className="px-6 py-3 font-medium text-foreground">{u.name}</td>
                    <td className="px-6 py-3 text-muted-foreground">{u.email}</td>
                    <td className="px-6 py-3">
                      <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${roleStyles[u.role] ?? "bg-muted text-muted-foreground"}`}>
                        {u.roleLabel ?? roleLabels[u.role] ?? "Utilisateur"}
                      </span>
                    </td>
                    <td className="px-6 py-3">
                      <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${u.status === "Actif" ? "bg-success/10 text-success" : "bg-muted text-muted-foreground"}`}>
                        {u.status}
                      </span>
                    </td>
                    <td className="px-6 py-3">
                      {u.role === "student" ? (
                        <div className="flex items-center gap-2">
                          <Select
                            defaultValue={u.payment_status || "pending"}
                            onValueChange={(value) =>
                              updatePaymentStatusMutation.mutate({
                                user: u,
                                status: value as "paid" | "pending" | "late",
                              })
                            }
                          >
                            <SelectTrigger className="w-[140px]">
                              <SelectValue />
                            </SelectTrigger>
                            <SelectContent>
                              <SelectItem value="paid">Payé</SelectItem>
                              <SelectItem value="pending">Non payé</SelectItem>
                            </SelectContent>
                          </Select>
                          <span
                            className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${
                              paymentStatusStyles[u.payment_status || "pending"]
                            }`}
                          >
                            {paymentStatusLabels[u.payment_status || "pending"]}
                          </span>
                        </div>
                      ) : (
                        <span className="text-muted-foreground">—</span>
                      )}
                    </td>
                    <td className="px-6 py-3">
                      <div className="flex items-center gap-1">
                        <button
                          onClick={() => handleEditUser(u)}
                          className="p-1.5 rounded-lg hover:bg-secondary transition-colors"
                        >
                          <Edit className="w-4 h-4 text-muted-foreground" />
                        </button>
                        <button
                          onClick={() => handleDeleteUser(u)}
                          className="p-1.5 rounded-lg hover:bg-accent/10 transition-colors disabled:opacity-50"
                          disabled={currentUser?.role === "directeur" && u.role === "admin"}
                        >
                          <Trash2 className="w-4 h-4 text-accent" />
                        </button>
                      </div>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>

          {/* Pagination */}
          <div className="px-6 py-4 border-t border-border flex items-center justify-between text-sm text-muted-foreground">
            <span>Affichage 1-10 sur {users.length}</span>
            <div className="flex items-center gap-1">
              <button className="p-2 rounded-lg hover:bg-secondary"><ChevronLeft className="w-4 h-4" /></button>
              <button className="px-3 py-1 rounded-lg bg-primary text-primary-foreground text-xs font-medium">1</button>
              <button className="px-3 py-1 rounded-lg hover:bg-secondary text-xs">2</button>
              <button className="p-2 rounded-lg hover:bg-secondary"><ChevronRight className="w-4 h-4" /></button>
            </div>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
