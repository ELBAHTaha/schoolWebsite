import { ReactNode } from "react";

interface DashboardHeaderProps {
  title: string;
  subtitle?: string;
  action?: ReactNode;
}

export default function DashboardHeader({ title, subtitle, action }: DashboardHeaderProps) {
  return (
    <div className="bg-card border border-border rounded-2xl p-6 shadow-card flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
      <div>
        <h1 className="text-2xl font-heading font-bold text-gradient-navy">{title}</h1>
        {subtitle && <p className="text-sm text-muted-foreground mt-1">{subtitle}</p>}
      </div>
      {action && <div className="shrink-0">{action}</div>}
    </div>
  );
}
