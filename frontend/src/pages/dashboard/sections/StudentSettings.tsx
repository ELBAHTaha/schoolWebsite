import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";

export default function StudentSettings() {
  return (
    <DashboardLayout role="student">
      <div className="space-y-6">
        <DashboardHeader
          title="Paramètres"
          subtitle="Gérez vos préférences et informations personnelles"
        />

        <div className="grid lg:grid-cols-2 gap-6">
          <div className="bg-card rounded-2xl p-6 shadow-card space-y-4">
            <h3 className="font-heading font-semibold">Informations personnelles</h3>
            <div className="grid gap-3">
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Nom complet" defaultValue="Étudiant JEFAL" />
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Email" defaultValue="student@jefal.com" />
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Téléphone" defaultValue="+212 6 11 11 11 11" />
            </div>
            <button className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity">
              Enregistrer
            </button>
          </div>

          <div className="bg-card rounded-2xl p-6 shadow-card space-y-4">
            <h3 className="font-heading font-semibold">Préférences</h3>
            <div className="grid gap-3">
              <label className="flex items-center gap-3 text-sm text-muted-foreground">
                <input type="checkbox" className="rounded border-input" defaultChecked />
                Recevoir les annonces par email
              </label>
              <label className="flex items-center gap-3 text-sm text-muted-foreground">
                <input type="checkbox" className="rounded border-input" />
                Activer les notifications SMS
              </label>
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
