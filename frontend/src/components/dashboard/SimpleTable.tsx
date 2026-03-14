import { ReactNode } from "react";

interface Column<T> {
  key: string;
  label: string;
  className?: string;
  render?: (row: T) => ReactNode;
}

interface SimpleTableProps<T> {
  columns: Array<Column<T>>;
  rows: T[];
  emptyLabel?: string;
}

export default function SimpleTable<T extends Record<string, unknown>>({
  columns,
  rows,
  emptyLabel = "Aucune donnée disponible.",
}: SimpleTableProps<T>) {
  return (
    <div className="overflow-x-auto">
      <table className="w-full text-sm">
        <thead>
          <tr className="border-b border-border bg-secondary/50">
            {columns.map((column) => (
              <th
                key={column.key}
                className={`text-left px-6 py-3 font-medium text-muted-foreground ${column.className ?? ""}`}
              >
                {column.label}
              </th>
            ))}
          </tr>
        </thead>
        <tbody>
          {rows.length === 0 && (
            <tr>
              <td colSpan={columns.length} className="px-6 py-6 text-muted-foreground">
                {emptyLabel}
              </td>
            </tr>
          )}
          {rows.map((row, index) => (
            <tr key={index} className="border-b border-border last:border-0 hover:bg-secondary/30 transition-colors">
              {columns.map((column) => (
                <td key={column.key} className={`px-6 py-3 ${column.className ?? ""}`}>
                  {column.render ? column.render(row) : String(row[column.key] ?? "")}
                </td>
              ))}
            </tr>
          ))}
        </tbody>
      </table>
    </div>
  );
}
