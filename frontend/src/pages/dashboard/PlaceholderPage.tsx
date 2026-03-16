import { Link } from "react-router-dom";
import DashboardLayout from "@/components/DashboardLayout";
import DashboardHeader from "@/components/DashboardHeader";

interface PlaceholderPageProps {
  role: "admin" | "directeur" | "student" | "professor" | "secretary" | "commercial";
  title: string;
  subtitle?: string;
}

export default function PlaceholderPage({ role, title, subtitle }: PlaceholderPageProps) {
  const basePath = `/dashboard/${role}`;

  return (
    <DashboardLayout role={role}>
      <div className="space-y-6">
        <DashboardHeader title={title} subtitle={subtitle} />

        <div className="bg-card rounded-2xl p-6 shadow-card">
          <p className="text-sm text-muted-foreground">
            Cette section est prête à être branchée sur les données. Nous l’activons dès que
            les contenus sont disponibles.
          </p>
          <div className="mt-4 flex flex-wrap gap-3">
            <Link
              to={basePath}
              className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full bg-primary text-primary-foreground text-sm font-semibold hover:opacity-90 transition-opacity"
            >
              Retour au tableau de bord
            </Link>
            <Link
              to="/"
              className="inline-flex items-center gap-2 px-5 py-2.5 rounded-full border border-border text-foreground text-sm font-semibold hover:border-primary hover:text-primary transition-colors"
            >
              Aller à l’accueil
            </Link>
          </div>
        </div>
      </div>
    </DashboardLayout>
  );
}
