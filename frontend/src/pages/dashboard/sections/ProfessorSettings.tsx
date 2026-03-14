import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";

export default function ProfessorSettings() {
  return (
    <DashboardLayout role="professor">
      <div className="space-y-6">
        <DashboardHeader
          title="Paramètres"
          subtitle="Gérez vos informations de professeur"
        />

        <div className="grid lg:grid-cols-2 gap-6">
          <div className="bg-card rounded-2xl p-6 shadow-card space-y-4">
            <h3 className="font-heading font-semibold">Profil</h3>
            <div className="grid gap-3">
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Nom complet" defaultValue="Prof. JEFAL" />
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Email" defaultValue="prof@jefal.com" />
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Spécialité" defaultValue="Anglais" />
            </div>
            <button className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity">
              Enregistrer
            </button>
          </div>

          <div className="bg-card rounded-2xl p-6 shadow-card space-y-4">
            <h3 className="font-heading font-semibold">Disponibilités</h3>
            <div className="grid gap-3">
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Lundi" defaultValue="10h00 - 16h00" />
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Mardi" defaultValue="14h00 - 18h00" />
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Jeudi" defaultValue="09h00 - 12h00" />
            </div>
            <button className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-border text-foreground text-sm font-semibold hover:border-primary hover:text-primary transition-colors">
              Mettre à jour
            </button>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
