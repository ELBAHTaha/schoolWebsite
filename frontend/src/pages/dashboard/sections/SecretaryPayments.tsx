import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import { useState } from "react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";
import { apiGet, apiPost, apiPut } from "@/lib/api";
import {
  Dialog,
  DialogContent,
  DialogDescription,
  DialogHeader,
  DialogTitle,
} from "@/components/ui/dialog";
import { Input } from "@/components/ui/input";
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
  const [paymentModalOpen, setPaymentModalOpen] = useState(false);
  const [selectedStudent, setSelectedStudent] = useState<Student | null>(null);
  const [selectedStatus, setSelectedStatus] = useState<"paid" | "unpaid">("paid");
  const [paymentForm, setPaymentForm] = useState({
    amount: "",
    date: new Date().toISOString().slice(0, 10),
    months: "1",
  });

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
    setSelectedStudent(student);
    setSelectedStatus(status);
    setPaymentModalOpen(true);
  };

  const handleConfirmPayment = async () => {
    if (!selectedStudent) {
      return;
    }
    if (selectedStatus === "paid") {
      if (!paymentForm.amount || !paymentForm.date || !paymentForm.months) {
        toast.error("Veuillez renseigner le montant, la date et le nombre de mois.");
        return;
      }
      try {
        await apiPost("/secretary/payments", {
          student_id: selectedStudent.id,
          amount: parseFloat(paymentForm.amount),
          status: "paid",
          payment_date: paymentForm.date,
          months: parseInt(paymentForm.months, 10),
        });
        await updateStatusMutation.mutateAsync({ student: selectedStudent, status: "paid" });
      } catch (error: any) {
        toast.error(error.message || "Erreur lors de la mise à jour du statut");
        return;
      }
    } else {
      await updateStatusMutation.mutateAsync({ student: selectedStudent, status: "unpaid" });
    }

    setPaymentModalOpen(false);
    setSelectedStudent(null);
    queryClient.invalidateQueries({ queryKey: ["secretary-students-payments"] });
    queryClient.invalidateQueries({ queryKey: ["secretary-students"] });
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
                  <button
                    type="button"
                    onClick={() => handleChangeStatus(row as Student, row.status)}
                    className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${statusStyles[row.status]}`}
                  >
                    {statusLabels[row.status]}
                  </button>
                ),
              },
            ]}
            rows={students}
          />
        </div>
      </div>

      <Dialog open={paymentModalOpen} onOpenChange={setPaymentModalOpen}>
        <DialogContent className="sm:max-w-[500px]">
          <DialogHeader>
            <DialogTitle>Mettre à jour le paiement</DialogTitle>
            <DialogDescription>
              Choisissez le statut et complétez les informations du paiement.
            </DialogDescription>
          </DialogHeader>
          <div className="space-y-4">
            <div>
              <label className="block text-sm font-medium mb-1.5 text-foreground">Statut</label>
              <Select value={selectedStatus} onValueChange={(value) => setSelectedStatus(value as "paid" | "unpaid")}>
                <SelectTrigger>
                  <SelectValue />
                </SelectTrigger>
                <SelectContent>
                  <SelectItem value="paid">Payé</SelectItem>
                  <SelectItem value="unpaid">Non payé</SelectItem>
                </SelectContent>
              </Select>
            </div>
            {selectedStatus === "paid" && (
              <>
                <div>
                  <label className="block text-sm font-medium mb-1.5 text-foreground">Montant</label>
                  <Input
                    type="number"
                    step="0.01"
                    placeholder="0.00"
                    value={paymentForm.amount}
                    onChange={(e) => setPaymentForm((prev) => ({ ...prev, amount: e.target.value }))}
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1.5 text-foreground">Date</label>
                  <Input
                    type="date"
                    value={paymentForm.date}
                    onChange={(e) => setPaymentForm((prev) => ({ ...prev, date: e.target.value }))}
                  />
                </div>
                <div>
                  <label className="block text-sm font-medium mb-1.5 text-foreground">Nombre de mois</label>
                  <Input
                    type="number"
                    min="1"
                    value={paymentForm.months}
                    onChange={(e) => setPaymentForm((prev) => ({ ...prev, months: e.target.value }))}
                  />
                </div>
              </>
            )}
            <div className="flex justify-end gap-3 pt-2">
              <button
                type="button"
                onClick={() => setPaymentModalOpen(false)}
                className="px-4 py-2 rounded-lg border border-input text-sm"
              >
                Annuler
              </button>
              <button
                type="button"
                onClick={handleConfirmPayment}
                className="px-4 py-2 rounded-lg bg-primary text-primary-foreground text-sm font-semibold"
              >
                Enregistrer
              </button>
            </div>
          </div>
        </DialogContent>
      </Dialog>
    </DashboardLayout>
  );
}
