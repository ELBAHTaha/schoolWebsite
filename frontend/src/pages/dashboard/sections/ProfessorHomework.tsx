import { ClipboardList, Plus } from "lucide-react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { useState } from "react";
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
import { Textarea } from "@/components/ui/textarea";
import { toast } from "sonner";

const assignmentFormSchema = z.object({
  title: z.string().min(1, "Le titre est requis"),
  description: z.string().optional(),
  class_id: z.string().min(1, "La classe est requise"),
  due_date: z.string().min(1, "La date limite est requise"),
});

type AssignmentFormData = z.infer<typeof assignmentFormSchema>;

const statusStyles: Record<string, string> = {
  "À corriger": "bg-warning/10 text-warning",
  "En cours": "bg-primary/10 text-primary",
};

interface Assignment {
  id: number;
  title: string;
  description: string | null;
  due_date: string | null;
  class_name: string;
  document_path: string | null;
  created_at: string;
}

interface AssignmentsResponse {
  data: Assignment[];
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

const mockAssignments = [
  { id: 1, title: "Essay Writing", className: "TOEFL", due: "2026-03-18", status: "À corriger" },
  { id: 2, title: "Compréhension orale", className: "DELF B2", due: "2026-03-12", status: "En cours" },
];

export default function ProfessorHomework() {
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const queryClient = useQueryClient();

  const form = useForm<AssignmentFormData>({
    resolver: zodResolver(assignmentFormSchema),
    defaultValues: {
      title: "",
      description: "",
      class_id: "",
      due_date: "",
    },
  });

  const { data } = useQuery({
    queryKey: ["professor-assignments"],
    queryFn: () => apiGet<AssignmentsResponse>("/professor/assignments"),
  });

  const { data: classesData } = useQuery({
    queryKey: ["professor-classes"],
    queryFn: () => apiGet<ClassesResponse>("/professor/classes"),
  });

  const createAssignmentMutation = useMutation({
    mutationFn: (payload: AssignmentFormData) =>
      apiPost("/professor/assignments", {
        ...payload,
        class_id: parseInt(payload.class_id),
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["professor-assignments"] });
      setIsAddModalOpen(false);
      form.reset();
      toast.success("Devoir créé avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la création du devoir");
    },
  });

  const onSubmit = (data: AssignmentFormData) => {
    createAssignmentMutation.mutate(data);
  };

  const handleModalClose = (open: boolean) => {
    if (!open) {
      setIsAddModalOpen(false);
      form.reset();
      return;
    }
    setIsAddModalOpen(true);
  };

  const assignments = data
    ? data.data.map((assignment) => ({
        id: assignment.id,
        title: assignment.title,
        className: assignment.class_name,
        due: assignment.due_date || "",
        status: "À corriger", // This would need logic based on submissions
      }))
    : mockAssignments;

  return (
    <DashboardLayout role="professor">
      <div className="space-y-6">
        <DashboardHeader
          title="Devoirs"
          subtitle="Suivez et créez des devoirs"
          action={
            <Dialog open={isAddModalOpen} onOpenChange={handleModalClose}>
              <DialogTrigger asChild>
                <button
                  onClick={() => setIsAddModalOpen(true)}
                  className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
                >
                  <Plus className="w-4 h-4" /> Nouveau devoir
                </button>
              </DialogTrigger>
              <DialogContent className="sm:max-w-[500px]">
                <DialogHeader>
                  <DialogTitle>Créer un nouveau devoir</DialogTitle>
                  <DialogDescription>
                    Remplissez les informations pour créer un nouveau devoir.
                  </DialogDescription>
                </DialogHeader>
                <Form {...form}>
                  <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-4">
                    <FormField
                      control={form.control}
                      name="title"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Titre du devoir</FormLabel>
                          <FormControl>
                            <Input placeholder="Entrez le titre du devoir" {...field} />
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
                            <Textarea
                              placeholder="Entrez la description du devoir"
                              className="resize-none"
                              {...field}
                            />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                    <div className="grid grid-cols-2 gap-4">
                      <FormField
                        control={form.control}
                        name="class_id"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Classe</FormLabel>
                            <Select onValueChange={field.onChange} defaultValue={field.value}>
                              <FormControl>
                                <SelectTrigger>
                                  <SelectValue placeholder="Sélectionnez une classe" />
                                </SelectTrigger>
                              </FormControl>
                              <SelectContent>
                                {(classesData?.data || []).map((classItem) => (
                                  <SelectItem key={classItem.id} value={String(classItem.id)}>
                                    {classItem.name}
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
                        name="due_date"
                        render={({ field }) => (
                          <FormItem>
                            <FormLabel>Date limite</FormLabel>
                            <FormControl>
                              <Input type="date" {...field} />
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
                      <Button type="submit" disabled={createAssignmentMutation.isPending}>
                        {createAssignmentMutation.isPending ? "Création..." : "Créer le devoir"}
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
              { key: "title", label: "Devoir", className: "font-medium text-foreground" },
              { key: "className", label: "Classe", className: "text-muted-foreground" },
              { key: "due", label: "Date limite", className: "text-muted-foreground" },
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
                    <ClipboardList className="w-3 h-3" /> Gérer
                  </button>
                ),
              },
            ]}
            rows={assignments}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
