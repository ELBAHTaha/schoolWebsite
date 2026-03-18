import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";
import { apiGet, apiPut } from "@/lib/api";
import {
  Select,
  SelectContent,
  SelectItem,
  SelectTrigger,
  SelectValue,
} from "@/components/ui/select";
import { toast } from "sonner";

const statusStyles: Record<string, string> = {
  paid: "bg-success/10 text-success",
  unpaid: "bg-warning/10 text-warning",
};

const statusLabels: Record<string, string> = {
  paid: "Payé",
  unpaid: "Non payé",
};

interface Student {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  class_name: string | null;
  class_names?: string[];
  class_ids?: number[];
  payment_status: string;
  last_payment_amount?: number | null;
  last_payment_date?: string | null;
}

interface StudentsResponse {
  data: Student[];
}

export default function SecretaryPayments() {
  const queryClient = useQueryClient();

  const { data } = useQuery({
    queryKey: ["secretary-students-payments"],
    queryFn: () => apiGet<StudentsResponse>("/secretary/students?limit=200"),
  });

  const updateStatusMutation = useMutation({
    mutationFn: (payload: { student: Student; status: "paid" | "unpaid" }) =>
      apiPut(`/secretary/students/${payload.student.id}`, {
        name: payload.student.name,
        email: payload.student.email,
        phone: payload.student.phone || "",
        class_id: payload.student.class_ids?.[0] ?? null,
        class_ids: payload.student.class_ids || [],
        payment_status: payload.status === "paid" ? "paid" : "pending",
      }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["secretary-students-payments"] });
      queryClient.invalidateQueries({ queryKey: ["secretary-students"] });
      toast.success("Statut mis à jour");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la mise à jour du statut");
    },
  });

  const students = (data?.data || []).map((student) => {
    const normalizedStatus = student.payment_status === "paid" ? "paid" : "unpaid";
    return {
      ...student,
      status: normalizedStatus,
      classLabel: student.class_names?.length
        ? student.class_names.join(", ")
        : student.class_name || "—",
      amountPaid: student.last_payment_amount ?? 0,
      lastPaymentDate: student.last_payment_date ?? "—",
    };
  });

  const handleChangeStatus = (student: Student, status: "paid" | "unpaid") => {
    updateStatusMutation.mutate({ student, status });
  };

  return (
    <DashboardLayout role="secretary">
      <div className="space-y-6">
        <DashboardHeader
          title="Paiements"
          subtitle="Statut de paiement des étudiants"
        />

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <SimpleTable
            columns={[
              { key: "name", label: "Étudiant", className: "font-medium text-foreground" },
              { key: "email", label: "Email", className: "text-muted-foreground" },
              { key: "classLabel", label: "Classe", className: "text-muted-foreground" },
              {
                key: "amountPaid",
                label: "Montant payé",
                render: (row) => `${Number(row.amountPaid).toLocaleString("fr-FR")} DH`,
              },
              { key: "lastPaymentDate", label: "Date", className: "text-muted-foreground" },
              {
                key: "status",
                label: "Statut",
                render: (row) => (
                  <div className="flex items-center gap-2">
                    <Select
                      defaultValue={row.status}
                      onValueChange={(value) =>
                        handleChangeStatus(row as Student, value as "paid" | "unpaid")
                      }
                    >
                      <SelectTrigger className="w-[140px]">
                        <SelectValue />
                      </SelectTrigger>
                      <SelectContent>
                        <SelectItem value="paid">Payé</SelectItem>
                        <SelectItem value="unpaid">Non payé</SelectItem>
                      </SelectContent>
                    </Select>
                    <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${statusStyles[row.status]}`}>
                      {statusLabels[row.status]}
                    </span>
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
