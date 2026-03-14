import { UserPlus } from "lucide-react";
import { useForm } from "react-hook-form";
import { zodResolver } from "@hookform/resolvers/zod";
import { z } from "zod";
import { useMutation, useQueryClient } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { apiPost } from "@/lib/api";
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

const studentFormSchema = z.object({
  name: z.string().min(1, "Le nom est requis"),
  email: z.string().email("Email invalide"),
  password: z.string().min(8, "Le mot de passe doit contenir au moins 8 caractères"),
  phone: z.string().optional(),
  class_id: z.string().min(1, "La classe est requise"),
  notes: z.string().optional(),
});

type StudentFormData = z.infer<typeof studentFormSchema>;

export default function SecretaryAddStudent() {
  const queryClient = useQueryClient();

  const form = useForm<StudentFormData>({
    resolver: zodResolver(studentFormSchema),
    defaultValues: {
      name: "",
      email: "",
      password: "",
      phone: "",
      class_id: "",
      notes: "",
    },
  });

  const createStudentMutation = useMutation({
    mutationFn: (data: StudentFormData) => apiPost("/secretary/students", {
      ...data,
      class_id: data.class_id ? parseInt(data.class_id) : null,
    }),
    onSuccess: () => {
      form.reset();
      toast.success("Étudiant créé avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la création de l'étudiant");
    },
  });

  const onSubmit = (data: StudentFormData) => {
    createStudentMutation.mutate(data);
  };
  return (
    <DashboardLayout role="secretary">
      <div className="space-y-6">
        <DashboardHeader
          title="Ajouter un étudiant"
          subtitle="Créez un nouveau dossier étudiant"
        />

        <div className="bg-card rounded-2xl p-6 shadow-card">
          <Form {...form}>
            <form onSubmit={form.handleSubmit(onSubmit)} className="space-y-6">
              <div className="grid md:grid-cols-2 gap-4">
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
              <div className="grid md:grid-cols-2 gap-4">
                <FormField
                  control={form.control}
                  name="password"
                  render={({ field }) => (
                    <FormItem>
                      <FormLabel>Mot de passe</FormLabel>
                      <FormControl>
                        <Input type="password" placeholder="Entrez le mot de passe" {...field} />
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
                        <SelectItem value="1">DELF B2</SelectItem>
                        <SelectItem value="2">TOEFL</SelectItem>
                        <SelectItem value="3">IELTS</SelectItem>
                        <SelectItem value="4">TEF</SelectItem>
                      </SelectContent>
                    </Select>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <FormField
                control={form.control}
                name="notes"
                render={({ field }) => (
                  <FormItem>
                    <FormLabel>Notes complémentaires</FormLabel>
                    <FormControl>
                      <Textarea
                        placeholder="Entrez des notes complémentaires"
                        className="resize-none"
                        {...field}
                      />
                    </FormControl>
                    <FormMessage />
                  </FormItem>
                )}
              />
              <Button
                type="submit"
                disabled={createStudentMutation.isPending}
                className="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
              >
                <UserPlus className="w-4 h-4" />
                {createStudentMutation.isPending ? "Création..." : "Créer le dossier"}
              </Button>
            </form>
          </Form>
        </div>
      </div>
    </DashboardLayout>
  );
}
