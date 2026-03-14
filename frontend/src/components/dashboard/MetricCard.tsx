interface MetricCardProps {
  label: string;
  value: string;
  icon: React.ElementType;
  toneClass: string;
  hint?: string;
}

export default function MetricCard({ label, value, icon: Icon, toneClass, hint }: MetricCardProps) {
  return (
    <div className="bg-card rounded-2xl p-5 shadow-card">
      <div className={`w-10 h-10 rounded-lg flex items-center justify-center mb-3 ${toneClass}`}>
        <Icon className="w-5 h-5" />
      </div>
      <div className="text-xl font-bold text-foreground">{value}</div>
      <div className="text-xs text-muted-foreground">{label}</div>
      {hint && <div className="text-[11px] text-muted-foreground mt-1">{hint}</div>}
    </div>
  );
}
