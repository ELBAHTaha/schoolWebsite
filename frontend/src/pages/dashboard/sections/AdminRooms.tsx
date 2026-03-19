import { Plus } from "lucide-react";
import { useState } from "react";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { useForm } from "react-hook-form";
import { z } from "zod";
import { zodResolver } from "@hookform/resolvers/zod";
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
import { toast } from "sonner";

const roomFormSchema = z.object({
  name: z.string().min(1, "Le nom de la salle est requis"),
  capacity: z.string().min(1, "La capacité est requise"),
  description: z.string().optional(),
});

type RoomFormData = z.infer<typeof roomFormSchema>;

const statusStyles: Record<string, string> = {
  Disponible: "bg-success/10 text-success",
  Occupée: "bg-warning/10 text-warning",
  Maintenance: "bg-accent/10 text-accent",
};

export default function AdminRooms() {
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const queryClient = useQueryClient();

  const form = useForm<RoomFormData>({
    resolver: zodResolver(roomFormSchema),
    defaultValues: {
      name: "",
      capacity: "",
      description: "",
    },
  });

  const { data } = useQuery({
    queryKey: ["admin-rooms"],
    queryFn: () =>
      apiGet<{
        data: Array<{ id: number; name: string; capacity: number; description: string | null; status?: string }>;
      }>("/admin/rooms"),
  });

  const createRoomMutation = useMutation({
    mutationFn: (payload: RoomFormData) =>
      apiPost("/admin/rooms", {
        name: payload.name,
        capacity: parseInt(payload.capacity, 10),
        description: payload.description || null,
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["admin-rooms"] });
      setIsAddModalOpen(false);
      form.reset();
      toast.success("Salle créée avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la création de la salle");
    },
  });

  const onSubmit = (payload: RoomFormData) => {
    createRoomMutation.mutate(payload);
  };

  const rooms = (data?.data || []).map((room) => ({
    name: room.name,
    capacity: room.capacity,
    description: room.description || "—",
    status: room.status || "Disponible",
  }));

  return (
    <DashboardLayout role="admin">
      <div className="space-y-6">
        <DashboardHeader
          title="Gestion des salles"
          subtitle="Suivi des salles et disponibilités"
          action={
            <Dialog open={isAddModalOpen} onOpenChange={setIsAddModalOpen}>
              <DialogTrigger asChild>
                <button className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity">
                  <Plus className="w-4 h-4" /> Ajouter une salle
                </button>
              </DialogTrigger>
              <DialogContent className="sm:max-w-[500px]">
                <DialogHeader>
                  <DialogTitle>Ajouter une salle</DialogTitle>
                  <DialogDescription>
                    Renseignez les informations pour créer une nouvelle salle.
                  </DialogDescription>
                </DialogHeader>
                <Form {...form}>
                  <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
                    <FormField
                      control={form.control}
                      name="name"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Nom</FormLabel>
                          <FormControl>
                            <Input placeholder="Salle E" {...field} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                    <div className="grid grid-cols-2 gap-4">
                      <FormField
                        control={form.control}
                        name="capacity"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Capacité</FormLabel>
                            <FormControl>
                              <Input type="number" placeholder="20" {...field} />
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
                            <FormLabel>Équipement</FormLabel>
                            <FormControl>
                              <Input placeholder="Projecteur, Tableau" {...field} />
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
                      <Button type="submit" disabled={createRoomMutation.isPending}>
                        {createRoomMutation.isPending ? "Création..." : "Créer la salle"}
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
              { key: "name", label: "Salle", className: "font-medium text-foreground" },
              { key: "capacity", label: "Capacité" },
              { key: "description", label: "Équipement", className: "text-muted-foreground" },
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
            rows={rooms}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
