import { useState } from "react";
import { Link, useLocation } from "react-router-dom";
import { Menu, X } from "lucide-react";

const navLinks = [
  { label: "Accueil", href: "/" },
  { label: "À propos", href: "/about" },
  { label: "Nos programmes", href: "/programs" },
  { label: "Contact", href: "/contact" },
];

export default function PublicNavbar() {
  const [open, setOpen] = useState(false);
  const location = useLocation();

  return (
    <>
      {/* Announcement Bar */}
      <div className="bg-primary text-primary-foreground text-center text-sm py-2.5 px-4 font-medium">
        📚 Inscriptions ouvertes — Nouvelle rentrée 2026 ! Réservez votre place dès maintenant
      </div>

      {/* Main Navbar */}
      <header className="sticky top-0 z-50 bg-card/98 backdrop-blur-lg border-b border-border">
        <div className="container mx-auto flex items-center justify-between h-18 px-4 lg:px-8 py-3">
          {/* Logo */}
          <Link to="/" className="flex items-center gap-3">
             <div className="hidden sm:block leading-tight">
              <span className="block text-sm font-heading font-bold text-primary">Academy American International</span>
              <span className="block text-xs text-muted-foreground font-medium">JEFAL Privé — Settat</span>
            </div>
          </Link>

          {/* Desktop Nav */}
          <nav className="hidden lg:flex items-center gap-1">
            {navLinks.map((link) => (
              <Link
                key={link.href}
                to={link.href}
                className={`px-4 py-2 rounded-full text-sm font-medium transition-colors ${
                  location.pathname === link.href
                    ? "text-primary font-semibold"
                    : "text-foreground/70 hover:text-primary"
                }`}
              >
                {link.label}
              </Link>
            ))}
          </nav>

          {/* CTA */}
          <div className="hidden lg:flex items-center gap-3">
            <Link
              to="/login"
              className="px-5 py-2.5 text-sm font-medium rounded-full text-foreground hover:text-primary transition-colors"
            >
              Connexion
            </Link>
            <Link
              to="/pre-registration"
              className="px-6 py-2.5 text-sm font-semibold rounded-full bg-accent text-accent-foreground hover:opacity-90 transition-opacity shadow-sm"
            >
              Contactez-nous
            </Link>
          </div>

          {/* Mobile toggle */}
          <button
            onClick={() => setOpen(!open)}
            className="lg:hidden p-2 rounded-lg hover:bg-secondary"
          >
            {open ? <X className="w-5 h-5" /> : <Menu className="w-5 h-5" />}
          </button>
        </div>

        {/* Mobile menu */}
        {open && (
          <div className="lg:hidden border-t border-border bg-card px-4 pb-4 animate-fade-in">
            <nav className="flex flex-col gap-1 py-3">
              {navLinks.map((link) => (
                <Link
                  key={link.href}
                  to={link.href}
                  onClick={() => setOpen(false)}
                  className={`px-4 py-3 rounded-xl text-sm font-medium ${
                    location.pathname === link.href
                      ? "bg-primary/5 text-primary font-semibold"
                      : "text-foreground hover:bg-secondary"
                  }`}
                >
                  {link.label}
                </Link>
              ))}
            </nav>
            <div className="flex flex-col gap-2 pt-2 border-t border-border">
              <div className="px-4 py-2">
                <ThemeToggle />
              </div>
              <Link
                to="/pre-registration"
                onClick={() => setOpen(false)}
                className="px-4 py-3 text-sm font-semibold rounded-xl bg-accent text-accent-foreground text-center"
              >
                Contactez-nous
              </Link>
              <Link
                to="/login"
                onClick={() => setOpen(false)}
                className="px-4 py-3 text-sm font-medium rounded-xl border border-border text-foreground text-center"
              >
                Connexion
              </Link>
            </div>
          </div>
        )}
      </header>
    </>
  );
}
