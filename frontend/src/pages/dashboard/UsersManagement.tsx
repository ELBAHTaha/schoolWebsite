import { useMemo, useState } from "react";
import { Search, Plus, Filter, Edit, Trash2, ChevronLeft, ChevronRight } from "lucide-react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { useForm } from "react-hook-form";
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
import { toast } from "sonner";

const userFormSchema = z.object({
  name: z.string().min(1, "Le nom est requis"),
  email: z.string().email("Email invalide"),
  password: z.string().optional(),
  role: z.enum(["admin", "professor", "secretary", "student", "visitor"], {
    required_error: "Le rôle est requis",
  }),
  phone: z.string().optional(),
});

type UserFormData = z.infer<typeof userFormSchema>;

const mockUsers = Array.from({ length: 20 }, (_, i) => ({
  id: i + 1,
  name: ["Fatima Zahra", "Youssef Alami", "Sara Benali", "Ahmed Mansouri", "Khadija El Idrissi", "Omar Tahiri", "Leila Bouazza", "Rachid Moussaoui"][i % 8],
  email: `user${i + 1}@email.com`,
  role: ["Étudiant", "Professeur", "Secrétaire", "Admin"][i % 4],
  status: i % 5 === 0 ? "Inactif" : "Actif",
}));

const roleStyles: Record<string, string> = {
  Admin: "bg-accent/10 text-accent",
  Professeur: "bg-primary/10 text-primary",
  Secrétaire: "bg-gold/10 text-gold",
  Étudiant: "bg-success/10 text-success",
};

const roleLabels: Record<string, string> = {
  admin: "Admin",
  professor: "Professeur",
  secretary: "Secrétaire",
  student: "Étudiant",
  visitor: "Visiteur",
};

interface UsersResponse {
  data: Array<{
    id: number;
    name: string;
    email: string;
    role: string;
    status: string;
  }>;
  total: number;
}

export default function UsersManagement() {
  const [search, setSearch] = useState("");
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [editingUser, setEditingUser] = useState<any>(null);
  const queryClient = useQueryClient();

  const form = useForm<UserFormData>({
    resolver: zodResolver(userFormSchema),
    defaultValues: {
      name: "",
      email: "",
      password: "",
      role: "student",
      phone: "",
    },
  });

  const { data } = useQuery({
    queryKey: ["admin-users", search],
    queryFn: () => apiGet<UsersResponse>(`/admin/users?search=${encodeURIComponent(search)}`),
  });

  const createUserMutation = useMutation({
    mutationFn: (data: UserFormData) => apiPost("/admin/users", data),
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
      apiPut(`/admin/users/${id}`, data),
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
    });
  };

  const handleDeleteUser = (user: any) => {
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

    if (editingUser) {
      const updateData = { ...data };
      if (!updateData.password) {
        delete updateData.password; // Don't send empty password for updates
      }
      updateUserMutation.mutate({
        id: editingUser.id,
        data: updateData,
      });
    } else {
      createUserMutation.mutate(data as Required<UserFormData>);
    }
  };

  const handleModalClose = () => {
    setIsAddModalOpen(false);
    setEditingUser(null);
    form.reset();
  };

  const fallbackUsers = useMemo(
    () =>
      mockUsers.filter((u) =>
        u.name.toLowerCase().includes(search.toLowerCase()) || u.email.includes(search.toLowerCase())
      ),
    [search]
  );

  const users = data
    ? data.data.map((user) => ({
        id: user.id,
        name: user.name,
        email: user.email,
        role: roleLabels[user.role] ?? "Utilisateur",
        status: user.status === "inactive" ? "Inactif" : "Actif",
      }))
    : fallbackUsers;

  return (
    <DashboardLayout role="admin">
      <div className="space-y-6">
        <DashboardHeader
          title="Gestion des utilisateurs"
          subtitle={`${data?.total ?? mockUsers.length} utilisateurs au total`}
          action={
            <Dialog open={isAddModalOpen || !!editingUser} onOpenChange={handleModalClose}>
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
                              <SelectItem value="student">Étudiant</SelectItem>
                              <SelectItem value="professor">Professeur</SelectItem>
                              <SelectItem value="secretary">Secrétaire</SelectItem>
                              <SelectItem value="admin">Admin</SelectItem>
                              <SelectItem value="visitor">Visiteur</SelectItem>
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
                        {u.role}
                      </span>
                    </td>
                    <td className="px-6 py-3">
                      <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${u.status === "Actif" ? "bg-success/10 text-success" : "bg-muted text-muted-foreground"}`}>
                        {u.status}
                      </span>
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
                          className="p-1.5 rounded-lg hover:bg-accent/10 transition-colors"
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
