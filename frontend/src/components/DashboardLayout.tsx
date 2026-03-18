import { ReactNode, useEffect, useState } from "react";
import { Link, useLocation, useNavigate } from "react-router-dom";
import {
  LayoutDashboard, Users, BookOpen, DoorOpen, CreditCard, Megaphone,
  Settings, Menu, X, Bell, ChevronDown, LogOut,
  FileText, Calendar, Download, ClipboardList
} from "lucide-react";
import { useAuth } from "@/contexts/AuthContext";

interface NavItem {
  label: string;
  href: string;
  icon: React.ElementType;
}

const buildNav = (role: string): NavItem[] => {
  const base = `/dashboard/${role}`;

  switch (role) {
    case "secretary":
      return [
        { label: "Tableau de bord", href: base, icon: LayoutDashboard },
        { label: "Étudiants", href: `${base}/students`, icon: Users },
        { label: "Classes", href: `${base}/classes`, icon: BookOpen },
        { label: "Paiements", href: `${base}/payments`, icon: CreditCard },
        { label: "Annonces", href: `${base}/announcements`, icon: Megaphone },
      ];
    case "professor":
      return [
        { label: "Tableau de bord", href: base, icon: LayoutDashboard },
        { label: "Mes classes", href: `${base}/my-classes`, icon: BookOpen },
        { label: "Documents", href: `${base}/documents`, icon: FileText },
        { label: "Devoirs", href: `${base}/homework`, icon: ClipboardList },
        { label: "Emploi du temps", href: `${base}/timetable`, icon: Calendar },
        { label: "Annonces", href: `${base}/announcements`, icon: Megaphone },
      ];
    case "student":
      return [
        { label: "Tableau de bord", href: base, icon: LayoutDashboard },
        { label: "Mes cours", href: `${base}/my-courses`, icon: BookOpen },
        { label: "Documents", href: `${base}/documents`, icon: Download },
        { label: "Devoirs", href: `${base}/homework`, icon: ClipboardList },
        { label: "Emploi du temps", href: `${base}/timetable`, icon: Calendar },
        { label: "Paiements", href: `${base}/payment-status`, icon: CreditCard },
        { label: "Annonces", href: `${base}/announcements`, icon: Megaphone },
      ];
    case "admin":
    case "directeur":
      return [
        { label: "Tableau de bord", href: base, icon: LayoutDashboard },
        { label: "Utilisateurs", href: `${base}/users`, icon: Users },
        { label: "Classes", href: `${base}/classes`, icon: BookOpen },
        { label: "Salles", href: `${base}/rooms`, icon: DoorOpen },
        { label: "Paiements", href: `${base}/payments`, icon: CreditCard },
        { label: "Annonces", href: `${base}/announcements`, icon: Megaphone },
        { label: "Paramètres", href: `${base}/settings`, icon: Settings },
      ];
    case "commercial":
      return [
        { label: "Tableau de bord", href: base, icon: LayoutDashboard },
        { label: "Leads", href: `${base}/leads`, icon: Users },
      ];
    default:
      return [
        { label: "Tableau de bord", href: base, icon: LayoutDashboard },
        { label: "Utilisateurs", href: `${base}/users`, icon: Users },
        { label: "Classes", href: `${base}/classes`, icon: BookOpen },
        { label: "Salles", href: `${base}/rooms`, icon: DoorOpen },
        { label: "Paiements", href: `${base}/payments`, icon: CreditCard },
        { label: "Annonces", href: `${base}/announcements`, icon: Megaphone },
        { label: "Paramètres", href: `${base}/settings`, icon: Settings },
      ];
  }
};

const roleLabels: Record<string, string> = {
  admin: "Administrateur",
  directeur: "Directeur",
  secretary: "Secrétaire",
  professor: "Professeur",
  student: "Étudiant",
  commercial: "Commercial",
};

interface Props {
  children: ReactNode;
  role?: string;
}

export default function DashboardLayout({ children, role = "admin" }: Props) {
  const [sidebarOpen, setSidebarOpen] = useState(false);
  const [profileOpen, setProfileOpen] = useState(false);
  const location = useLocation();
  const navigate = useNavigate();
  const { user, logout } = useAuth();
  const navItems = buildNav(role);
  const basePath = `/dashboard/${role}`;

  useEffect(() => {
    const syncSidebar = () => {
      if (window.innerWidth >= 1024) {
        setSidebarOpen(true);
      } else {
        setSidebarOpen(false);
      }
    };

    syncSidebar();
    window.addEventListener("resize", syncSidebar);
    return () => window.removeEventListener("resize", syncSidebar);
  }, []);

  const handleLogout = async () => {
    await logout();
    navigate("/login");
  };

  return (
    <div className="min-h-screen flex bg-background relative overflow-hidden">
      {/* Sidebar */}
      <aside
        className={`fixed inset-y-0 left-0 z-40 flex flex-col bg-card/95 backdrop-blur-md text-foreground border-r border-border transition-transform duration-300 w-64 overflow-hidden ${
          sidebarOpen ? "translate-x-0" : "-translate-x-full"
        } lg:translate-x-0`}
      >
        {/* Logo */}
        <div className="flex items-center gap-3 px-4 h-16 border-b border-border flex-shrink-0">
          <img src="/logo.png" alt="JEFAL" className="h-9 w-auto flex-shrink-0" />
          {sidebarOpen && (
            <span className="text-sm font-heading font-semibold truncate">JEFAL Privé</span>
          )}
        </div>

        {/* Nav */}
        <nav className="flex-1 py-4 overflow-y-auto">
          <div className="space-y-1 px-2">
            {navItems.map((item) => {
              const Icon = item.icon;
              const isActive = location.pathname === item.href;
              return (
                <Link
                  key={item.href}
                  to={item.href}
                  className={`flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm transition-colors ${
                    isActive
                      ? "bg-primary text-primary-foreground"
                      : "text-foreground/60 hover:text-foreground hover:bg-secondary"
                  }`}
                >
                  <Icon className="w-5 h-5 flex-shrink-0" />
                  {sidebarOpen && <span>{item.label}</span>}
                </Link>
              );
            })}
          </div>
        </nav>

        {/* Bottom */}
        <div className="p-3 border-t border-border">
          <Link
            to="/"
            className="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-foreground/60 hover:text-foreground hover:bg-secondary transition-colors"
          >
            <LogOut className="w-5 h-5 flex-shrink-0" />
            {sidebarOpen && <span>Déconnexion</span>}
          </Link>
        </div>
      </aside>

      {sidebarOpen && (
        <button
          type="button"
          onClick={() => setSidebarOpen(false)}
          className="fixed inset-0 z-30 bg-black/40 lg:hidden"
          aria-label="Fermer le menu"
        />
      )}

      {/* Main */}
      <div className="flex-1 flex flex-col transition-all duration-300 lg:ml-64">
        {/* Top bar */}
        <header className="sticky top-0 z-30 bg-card/90 backdrop-blur-md border-b border-border h-16 flex items-center justify-between px-4 lg:px-6">
          <div className="flex items-center gap-3">
            <button
              onClick={() => setSidebarOpen(!sidebarOpen)}
              className="p-2 rounded-lg hover:bg-secondary transition-colors"
            >
              {sidebarOpen ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
            </button>
            <div>
              <span className="text-xs text-muted-foreground">{roleLabels[role]}</span>
              <h2 className="text-sm font-semibold text-foreground leading-tight">
                Bienvenue, {user?.name || "Utilisateur"}
              </h2>
            </div>
          </div>

          <div className="flex items-center gap-2">
            <button className="relative p-2 rounded-lg hover:bg-secondary transition-colors">
              <Bell className="w-5 h-5 text-muted-foreground" />
              <span className="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-accent" />
            </button>

            <div className="relative">
              <button
                onClick={() => setProfileOpen(!profileOpen)}
                className="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-secondary transition-colors"
              >
                <div className="w-8 h-8 rounded-full bg-primary flex items-center justify-center">
                  <span className="text-xs font-bold text-primary-foreground">U</span>
                </div>
                <ChevronDown className="w-4 h-4 text-muted-foreground" />
              </button>
              {profileOpen && (
                <div className="absolute right-0 top-full mt-1 w-48 bg-card rounded-lg shadow-card-hover border border-border py-1 z-50">
                  <Link
                    to={`${basePath}/settings`}
                    className="flex items-center gap-2 px-4 py-2 text-sm hover:bg-secondary"
                    onClick={() => setProfileOpen(false)}
                  >
                    <Settings className="w-4 h-4" /> Paramètres
                  </Link>
                  <button
                    onClick={() => {
                      handleLogout();
                      setProfileOpen(false);
                    }}
                    className="flex items-center gap-2 px-4 py-2 text-sm text-accent hover:bg-secondary w-full text-left"
                  >
                    <LogOut className="w-4 h-4" /> Déconnexion
                  </button>
                </div>
              )}
            </div>
          </div>
        </header>

        {/* Content */}
        <main className="flex-1 p-4 lg:p-6 relative">
          <div className="mx-auto w-full max-w-6xl">
            {children}
          </div>
        </main>
      </div>
    </div>
  );
}
