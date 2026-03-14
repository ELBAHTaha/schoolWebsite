import { Plus } from "lucide-react";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";
import SimpleTable from "@/components/dashboard/SimpleTable";

const rooms = [
  { name: "Salle A", capacity: 24, equipment: "Projecteur, Tableau", status: "Disponible" },
  { name: "Salle B", capacity: 18, equipment: "Tableau", status: "Occupée" },
  { name: "Salle C", capacity: 20, equipment: "Projecteur", status: "Disponible" },
  { name: "Salle D", capacity: 12, equipment: "Tableau", status: "Maintenance" },
];

const statusStyles: Record<string, string> = {
  Disponible: "bg-success/10 text-success",
  Occupée: "bg-warning/10 text-warning",
  Maintenance: "bg-accent/10 text-accent",
};

export default function AdminRooms() {
  return (
    <DashboardLayout role="admin">
      <div className="space-y-6">
        <DashboardHeader
          title="Gestion des salles"
          subtitle="Suivi des salles et disponibilités"
          action={
            <button className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity">
              <Plus className="w-4 h-4" /> Ajouter une salle
            </button>
          }
        />

        <div className="bg-card rounded-2xl shadow-card overflow-hidden">
          <SimpleTable
            columns={[
              { key: "name", label: "Salle", className: "font-medium text-foreground" },
              { key: "capacity", label: "Capacité" },
              { key: "equipment", label: "Équipement", className: "text-muted-foreground" },
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
