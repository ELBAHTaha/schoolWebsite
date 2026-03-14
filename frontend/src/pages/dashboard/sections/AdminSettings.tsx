import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";

export default function AdminSettings() {
  return (
    <DashboardLayout role="admin">
      <div className="space-y-6">
        <DashboardHeader
          title="Paramètres administrateur"
          subtitle="Gérez les informations et préférences globales"
        />

        <div className="grid lg:grid-cols-2 gap-6">
          <div className="bg-card rounded-2xl p-6 shadow-card space-y-4">
            <h3 className="font-heading font-semibold">Profil</h3>
            <div className="grid gap-3">
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Nom complet" defaultValue="Admin JEFAL" />
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Email" defaultValue="admin@jefal.com" />
              <input className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Téléphone" defaultValue="+212 6 00 00 00 00" />
            </div>
            <button className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity">
              Enregistrer
            </button>
          </div>

          <div className="bg-card rounded-2xl p-6 shadow-card space-y-4">
            <h3 className="font-heading font-semibold">Sécurité</h3>
            <div className="grid gap-3">
              <input type="password" className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Ancien mot de passe" />
              <input type="password" className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Nouveau mot de passe" />
              <input type="password" className="w-full rounded-lg border border-input bg-background px-4 py-2 text-sm" placeholder="Confirmer le nouveau mot de passe" />
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
