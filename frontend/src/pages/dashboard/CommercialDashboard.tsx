import { useMemo, useState } from "react";
import { useMutation, useQuery, useQueryClient } from "@tanstack/react-query";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import { apiGet, apiPatch } from "@/lib/api";
import { toast } from "sonner";

type LeadStatus = "new" | "contacted" | "confirmed" | "not_interested";

interface Lead {
  id: number;
  name: string;
  email: string;
  phone: string | null;
  desired_program: string;
  message?: string | null;
  status: LeadStatus;
  created_at: string;
}

interface LeadsResponse {
  data: Lead[];
  total: number;
}

const statusLabels: Record<LeadStatus, string> = {
  new: "Nouveau",
  contacted: "Contacté",
  confirmed: "Confirmé",
  not_interested: "Pas intéressé",
};

const statusStyles: Record<LeadStatus, string> = {
  new: "bg-primary/10 text-primary",
  contacted: "bg-warning/10 text-warning",
  confirmed: "bg-success/10 text-success",
  not_interested: "bg-muted text-muted-foreground",
};

export default function CommercialDashboard() {
  const [statusFilter, setStatusFilter] = useState<string>("");
  const [dateFrom, setDateFrom] = useState<string>("");
  const [dateTo, setDateTo] = useState<string>("");
  const queryClient = useQueryClient();

  const queryString = useMemo(() => {
    const params = new URLSearchParams();
    if (statusFilter) params.set("status", statusFilter);
    if (dateFrom) params.set("date_from", dateFrom);
    if (dateTo) params.set("date_to", dateTo);
    const qs = params.toString();
    return qs ? `?${qs}` : "";
  }, [statusFilter, dateFrom, dateTo]);

  const { data, isLoading } = useQuery({
    queryKey: ["commercial-leads", statusFilter, dateFrom, dateTo],
    queryFn: () => apiGet<LeadsResponse>(`/commercial/leads${queryString}`),
  });

  const updateStatusMutation = useMutation({
    mutationFn: ({ id, status }: { id: number; status: LeadStatus }) =>
      apiPatch(`/commercial/leads/${id}`, { status }),
    onSuccess: () => {
      queryClient.invalidateQueries({ queryKey: ["commercial-leads"] });
      toast.success("Statut mis à jour");
    },
    onError: (error: any) => {
      toast.error(error?.message || "Erreur lors de la mise à jour");
    },
  });

  const leads = data?.data ?? [];

  return (
    <DashboardLayout role="commercial">
      <div className="space-y-6">
        <DashboardHeader
          title="Leads commerciaux"
          subtitle={`${data?.total ?? 0} lead(s) assigné(s)`}
        />

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <div className="px-6 py-4 border-b border-border flex flex-col lg:flex-row gap-3">
            <div className="flex items-center gap-2">
              <label className="text-sm text-muted-foreground">Statut</label>
              <select
                value={statusFilter}
                onChange={(e) => setStatusFilter(e.target.value)}
                className="rounded-lg border border-input bg-background px-3 py-2 text-sm"
              >
                <option value="">Tous</option>
                <option value="new">Nouveau</option>
                <option value="contacted">Contacté</option>
                <option value="confirmed">Confirmé</option>
                <option value="not_interested">Pas intéressé</option>
              </select>
            </div>
            <div className="flex items-center gap-2">
              <label className="text-sm text-muted-foreground">Du</label>
              <input
                type="date"
                value={dateFrom}
                onChange={(e) => setDateFrom(e.target.value)}
                className="rounded-lg border border-input bg-background px-3 py-2 text-sm"
              />
              <label className="text-sm text-muted-foreground">Au</label>
              <input
                type="date"
                value={dateTo}
                onChange={(e) => setDateTo(e.target.value)}
                className="rounded-lg border border-input bg-background px-3 py-2 text-sm"
              />
            </div>
          </div>

          <div className="overflow-x-auto">
            <table className="w-full text-sm">
              <thead>
                <tr className="border-b border-border bg-secondary/50">
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Nom</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Email</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Téléphone</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Programme</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Date</th>
                  <th className="text-left px-6 py-3 font-medium text-muted-foreground">Statut</th>
                </tr>
              </thead>
              <tbody>
                {isLoading && (
                  <tr>
                    <td className="px-6 py-4 text-muted-foreground" colSpan={6}>
                      Chargement...
                    </td>
                  </tr>
                )}
                {!isLoading && leads.length === 0 && (
                  <tr>
                    <td className="px-6 py-4 text-muted-foreground" colSpan={6}>
                      Aucun lead assigné pour le moment.
                    </td>
                  </tr>
                )}
                {leads.map((lead) => (
                  <tr key={lead.id} className="border-b border-border last:border-0">
                    <td className="px-6 py-3 font-medium text-foreground">{lead.name}</td>
                    <td className="px-6 py-3 text-muted-foreground">{lead.email}</td>
                    <td className="px-6 py-3 text-muted-foreground">{lead.phone || "—"}</td>
                    <td className="px-6 py-3 text-muted-foreground">{lead.desired_program}</td>
                    <td className="px-6 py-3 text-muted-foreground">
                      {lead.created_at?.slice(0, 10)}
                    </td>
                    <td className="px-6 py-3">
                      <select
                        value={lead.status}
                        onChange={(e) =>
                          updateStatusMutation.mutate({
                            id: lead.id,
                            status: e.target.value as LeadStatus,
                          })
                        }
                        className={`rounded-lg border border-input bg-background px-3 py-2 text-xs font-medium ${statusStyles[lead.status]}`}
                      >
                        {Object.entries(statusLabels).map(([key, label]) => (
                          <option key={key} value={key}>
                            {label}
                          </option>
                        ))}
                      </select>
                    </td>
                  </tr>
                ))}
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
