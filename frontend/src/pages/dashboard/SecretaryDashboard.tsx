import { Users, CreditCard, FileText, ClipboardList } from "lucide-react";
import { useQuery } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { apiGet } from "@/lib/api";

interface SecretaryDashboardResponse {
  stats: {
    students: number;
    payments_today: number;
    receipts: number;
    requests: number;
  };
}

interface Student {
  id: number;
  name: string;
  email: string;
  class_name: string | null;
  class_names?: string[];
  payment_status: string;
}

interface StudentsResponse {
  data: Student[];
}

export default function SecretaryDashboard() {
  const { data } = useQuery({
    queryKey: ["secretary-dashboard"],
    queryFn: () => apiGet<SecretaryDashboardResponse>("/dashboard/secretary"),
  });

  const { data: studentsData } = useQuery({
    queryKey: ["secretary-students-preview"],
    queryFn: () => apiGet<StudentsResponse>("/secretary/students?limit=200"),
  });

  const stats = data?.stats
    ? [
        { label: "Étudiants actifs", value: String(data.stats.students), icon: Users, color: "bg-primary/10 text-primary" },
        { label: "Paiements du jour", value: String(data.stats.payments_today), icon: CreditCard, color: "bg-success/10 text-success" },
      ]
    : [
        { label: "Étudiants actifs", value: "512", icon: Users, color: "bg-primary/10 text-primary" },
        { label: "Paiements du jour", value: "8", icon: CreditCard, color: "bg-success/10 text-success" },
      ];

  const students = (studentsData?.data || []).slice(0, 8).map((student) => ({
    id: student.id,
    name: student.name,
    email: student.email,
    className: student.class_names?.length
      ? student.class_names.join(", ")
      : student.class_name || "—",
    paymentStatus: student.payment_status === "paid" ? "Payé" : "Non payé",
  }));

  return (
    <DashboardLayout role="secretary">
      <div className="space-y-6">
        <DashboardHeader
          title="Espace secrétariat"
          subtitle="Suivi des étudiants, paiements et documents"
        />

        <div className="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
          {stats.map((s) => (
            <div key={s.label} className="bg-card rounded-2xl p-5 shadow-card">
              <div className={`w-10 h-10 rounded-lg flex items-center justify-center mb-3 ${s.color}`}>
                <s.icon className="w-5 h-5" />
              </div>
              <div className="text-xl font-bold text-foreground">{s.value}</div>
              <div className="text-xs text-muted-foreground">{s.label}</div>
            </div>
          ))}
        </div>

        <div className="bg-card rounded-2xl shadow-card p-6">
          <div className="flex items-center justify-between mb-4">
            <h3 className="font-heading font-semibold">Étudiants</h3>
            <span className="text-xs text-muted-foreground">Aperçu</span>
          </div>
          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border text-muted-foreground">
                  <th className="text-left py-2">Nom</th>
                  <th className="text-left py-2">Email</th>
                  <th className="text-left py-2">Cours</th>
                  <th className="text-left py-2">Statut paiement</th>
                </tr>
              </thead>
              <tbody>
                {students.length ? (
                  students.map((student) => (
                    <tr key={student.id} className="border-b border-border last:border-0">
                      <td className="py-3 font-medium text-foreground">{student.name}</td>
                      <td className="py-3 text-muted-foreground">{student.email}</td>
                      <td className="py-3 text-muted-foreground">{student.className}</td>
                      <td className="py-3">
                        <span
                          className={`inline-flex px-2.5 py-1 rounded-full text-xs font-medium ${
                            student.paymentStatus === "Payé"
                              ? "bg-success/10 text-success"
                              : "bg-warning/10 text-warning"
                          }`}
                        >
                          {student.paymentStatus}
                        </span>
                      </td>
                    </tr>
                  ))
                ) : (
                  <tr>
                    <td colSpan={4} className="py-6 text-center text-muted-foreground">
                      Aucun étudiant trouvé.
                    </td>
                  </tr>
                )}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
