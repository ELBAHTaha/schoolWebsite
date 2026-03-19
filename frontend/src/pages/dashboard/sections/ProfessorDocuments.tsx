import { FileText, Plus, Trash2 } from "lucide-react";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { useState } from "react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";
import { apiDelete, apiGet, apiPostForm } from "@/lib/api";
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

const documentFormSchema = z.object({
  title: z.string().min(1, "Le titre est requis"),
  class_id: z.string().min(1, "Le cours est requis"),
});

type DocumentFormData = z.infer<typeof documentFormSchema>;

interface MaterialsResponse {
  data: Array<{
    id: number;
    title: string;
    class_name: string | null;
    file_url: string | null;
    created_at: string | null;
  }>;
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

export default function ProfessorDocuments() {
  const [isAddModalOpen, setIsAddModalOpen] = useState(false);
  const [selectedFile, setSelectedFile] = useState<File | null>(null);
  const queryClient = useQueryClient();

  const form = useForm<DocumentFormData>({
    resolver: zodResolver(documentFormSchema),
    defaultValues: {
      title: "",
      class_id: "",
    },
  });

  const { data } = useQuery({
    queryKey: ["professor-materials"],
    queryFn: () => apiGet<MaterialsResponse>("/professor/materials"),
  });

  const { data: classesData } = useQuery({
    queryKey: ["professor-classes"],
    queryFn: () => apiGet<ClassesResponse>("/professor/classes"),
  });

  const createMaterialMutation = useMutation({
    mutationFn: async (payload: DocumentFormData) => {
      if (!selectedFile) {
        throw new Error("Veuillez sélectionner un fichier.");
      }
      const formData = new FormData();
      formData.append("title", payload.title);
      formData.append("class_id", payload.class_id);
      formData.append("document", selectedFile);
      return apiPostForm("/professor/materials", formData);
    },
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["professor-materials"] });
      setIsAddModalOpen(false);
      setSelectedFile(null);
      form.reset();
      toast.success("Document ajouté avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de l'ajout du document");
    },
  });

  const deleteMaterialMutation = useMutation({
    mutationFn: (id: number) => apiDelete(`/professor/materials/${id}`),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["professor-materials"] });
      toast.success("Document supprimé");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la suppression");
    },
  });

  const onSubmit = (payload: DocumentFormData) => {
    createMaterialMutation.mutate(payload);
  };

  const documents = (data?.data || []).map((doc) => ({
    id: doc.id,
    title: doc.title,
    className: doc.class_name || "—",
    date: doc.created_at || "",
    fileUrl: doc.file_url,
  }));

  return (
    <DashboardLayout role="professor">
      <div className="space-y-6">
        <DashboardHeader
          title="Documents"
          subtitle="Gérez vos supports pédagogiques"
          action={
            <Dialog open={isAddModalOpen} onOpenChange={setIsAddModalOpen}>
              <DialogTrigger asChild>
                <button
                  onClick={() => setIsAddModalOpen(true)}
                  className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
                >
                  <Plus className="w-4 h-4" /> Ajouter un document
                </button>
              </DialogTrigger>
              <DialogContent className="sm:max-w-[500px]">
                <DialogHeader>
                  <DialogTitle>Ajouter un document</DialogTitle>
                  <DialogDescription>
                    Associez le document à un cours et téléversez le fichier.
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
                            <Input placeholder="Titre du document" {...field} />
                          </FormControl>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                    <FormField
                      control={form.control}
                      name="class_id"
                      render={({ field }) => (
                        <FormItem>
                          <FormLabel>Cours</FormLabel>
                          <Select onValueChange={field.onChange} value={field.value}>
                            <FormControl>
                              <SelectTrigger>
                                <SelectValue placeholder="Sélectionnez un cours" />
                              </SelectTrigger>
                            </FormControl>
                            <SelectContent>
                              {(classesData?.data || []).map((classItem) => (
                                <SelectItem key={classItem.id} value={String(classItem.id)}>
                                  {classItem.name}
                                </SelectItem>
                              ))}
                              {!classesData?.data?.length && (
                                <SelectItem value="__empty__" disabled>
                                  Aucun cours disponible.
                                </SelectItem>
                              )}
                            </SelectContent>
                          </Select>
                          <FormMessage />
                        </FormItem>
                      )}
                    />
                    <div className="space-y-2">
                      <FormLabel>Fichier</FormLabel>
                      <Input
                        type="file"
                        onChange={(event) => setSelectedFile(event.target.files?.[0] || null)}
                      />
                    </div>
                    <div className="flex justify-end gap-3">
                      <Button type="button" variant="outline" onClick={() => setIsAddModalOpen(false)}>
                        Annuler
                      </Button>
                      <Button type="submit" disabled={createMaterialMutation.isPending}>
                        {createMaterialMutation.isPending ? "Enregistrement..." : "Ajouter"}
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
              { key: "title", label: "Document", className: "font-medium text-foreground" },
              { key: "className", label: "Cours", className: "text-muted-foreground" },
              { key: "date", label: "Date", className: "text-muted-foreground" },
              {
                key: "action",
                label: "Action",
                render: (row) => (
                  <div className="flex items-center gap-2">
                    {row.fileUrl && (
                      <a
                        href={row.fileUrl}
                        className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold hover:bg-primary/20"
                        target="_blank"
                        rel="noreferrer"
                      >
                        <FileText className="w-3 h-3" /> Voir
                      </a>
                    )}
                    <button
                      onClick={() => deleteMaterialMutation.mutate(row.id)}
                      className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-accent/10 text-accent text-xs font-semibold hover:bg-accent/20"
                    >
                      <Trash2 className="w-3 h-3" /> Supprimer
                    </button>
                  </div>
                ),
              },
            ]}
            rows={documents}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
