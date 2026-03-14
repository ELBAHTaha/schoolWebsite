import { CreditCard, CheckCircle } from "lucide-react";
import { useQuery, useMutation, useQueryClient } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";
import { apiGet, apiPut } from "@/lib/api";
import { toast } from "sonner";

const statusStyles: Record<string, string> = {
  paid: "bg-success/10 text-success",
  pending: "bg-warning/10 text-warning",
  late: "bg-accent/10 text-accent",
};

const statusLabels: Record<string, string> = {
  paid: "Payé",
  pending: "En attente",
  late: "Impayé",
};

interface Payment {
  id: number;
  student_name: string;
  amount: number;
  status: string;
  payment_date: string;
  recorded_by: number;
  created_at: string;
}

interface PaymentsResponse {
  data: Payment[];
  total: number;
  per_page: number;
  current_page: number;
}

const mockPayments = [
  { id: 1, student: "Fatima Zahra", amount: "2,000 DH", date: "2026-03-01", status: "paid" },
  { id: 2, student: "Youssef Alami", amount: "2,500 DH", date: "2026-02-28", status: "pending" },
  { id: 3, student: "Ahmed Mansouri", amount: "1,800 DH", date: "2026-02-20", status: "late" },
];

export default function SecretaryPayments() {
  const queryClient = useQueryClient();

  const { data } = useQuery({
    queryKey: ["secretary-payments"],
    queryFn: () => apiGet<PaymentsResponse>("/secretary/payments"),
  });

  const validatePaymentMutation = useMutation({
    mutationFn: (paymentId: number) => apiPut(`/secretary/payments/${paymentId}`, {
      status: 'paid',
    }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["secretary-payments"] });
      toast.success("Paiement validé avec succès");
    },
    onError: (error: any) => {
      toast.error(error.message || "Erreur lors de la validation du paiement");
    },
  });

  const payments = data
    ? data.data.map((payment) => ({
        id: payment.id,
        student: payment.student_name,
        amount: `${payment.amount.toLocaleString('fr-FR')} DH`,
        date: payment.payment_date,
        status: payment.status,
      }))
    : mockPayments;

  const handleValidatePayment = (payment: Payment) => {
    if (payment.status === 'paid') {
      toast.info("Ce paiement est déjà validé");
      return;
    }
    validatePaymentMutation.mutate(payment.id);
  };

  return (
    <DashboardLayout role="secretary">
      <div className="space-y-6">
        <DashboardHeader
          title="Paiements"
          subtitle="Suivi et validation des paiements"
        />

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <SimpleTable
            columns={[
              { key: "student", label: "Étudiant", className: "font-medium text-foreground" },
              { key: "amount", label: "Montant" },
              { key: "date", label: "Date", className: "text-muted-foreground" },
              {
                key: "status",
                label: "Statut",
                render: (row) => (
                  <span className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${statusStyles[row.status]}`}>
                    {statusLabels[row.status] || row.status}
                  </span>
                ),
              },
              {
                key: "action",
                label: "Action",
                render: (row) => (
                  <button
                    onClick={() => handleValidatePayment(row as Payment)}
                    disabled={validatePaymentMutation.isPending || row.status === 'paid'}
                    className="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 text-primary text-xs font-semibold hover:bg-primary/20 disabled:opacity-50 disabled:cursor-not-allowed"
                  >
                    {row.status === 'paid' ? (
                      <>
                        <CheckCircle className="w-3 h-3" /> Validé
                      </>
                    ) : (
                      <>
                        <CreditCard className="w-3 h-3" /> Valider
                      </>
                    )}
                  </button>
                ),
              },
            ]}
            rows={payments}
          />
        </div>
      </div>
    </DashboardLayout>
  );
}
