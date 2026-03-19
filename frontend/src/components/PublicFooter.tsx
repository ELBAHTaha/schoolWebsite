import { Link } from "react-router-dom";
import { Phone, Mail, MapPin, Globe, Facebook, Instagram, Youtube } from "lucide-react";

export default function PublicFooter() {
  return (
    <footer className="bg-white text-blue-700">
      <div className="container mx-auto px-4 lg:px-8 py-16">
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-10">
          {/* Brand */}
          <div>
            <img src="/logo.png" alt="JEFAL Privé" className="h-20 w-auto mb-2 mx-auto" />
            <p className="text-sm opacity-70 leading-relaxed mt-2">
              Academy American International JEFAL Privé ” Votre partenaire pour l'excellence linguistique à Settat, Maroc.
            </p>
            <div className="flex gap-3 mt-5">
              <a href="#" className="w-9 h-9 rounded-full bg-primary-foreground/10 hover:bg-accent flex items-center justify-center transition-colors">
                <Facebook className="w-4 h-4" />
              </a>
              <a href="#" className="w-9 h-9 rounded-full bg-primary-foreground/10 hover:bg-accent flex items-center justify-center transition-colors">
                <Instagram className="w-4 h-4" />
              </a>
              <a href="#" className="w-9 h-9 rounded-full bg-primary-foreground/10 hover:bg-accent flex items-center justify-center transition-colors">
                <Youtube className="w-4 h-4" />
              </a>
            </div>
          </div>

          {/* Links */}
          <div>
            <h4 className="font-heading font-semibold text-lg mb-5 text-blue-700">Navigation</h4>
            <ul className="space-y-3 text-sm opacity-100">
              <li><Link to="/" className="hover:opacity-100 text-blue-700 hover:text-blue-900 transition-all">Accueil</Link></li>
              <li><Link to="/about" className="hover:opacity-100 text-blue-700 hover:text-blue-900 transition-all">À Propos</Link></li>
              <li><Link to="/programs" className="hover:opacity-100 text-blue-700 hover:text-blue-900 transition-all">Programmes</Link></li>
              <li><Link to="/contact" className="hover:opacity-100 text-blue-700 hover:text-blue-900 transition-all">Contact</Link></li>
              <li><Link to="/pre-registration" className="hover:opacity-100 text-blue-700 hover:text-blue-900 transition-all">Pré-inscription</Link></li>
            </ul>
          </div>

          {/* Programs */}
          <div>
            <h4 className="font-heading font-semibold text-lg mb-5 text-blue-700">Préparations</h4>
            <ul className="space-y-3 text-sm opacity-100 text-blue-700">
              <li>DELF / DALF</li>
              <li>TOEFL</li>
              <li>IELTS</li>
              <li>TEF / TCF</li>
              <li>Examens d'immigration</li>
            </ul>
          </div>

          {/* Contact */}
          <div>
            <h4 className="font-heading font-semibold text-lg mb-5 text-blue-700">Contact</h4>
            <ul className="space-y-3 text-sm opacity-100 text-blue-700">
              <li className="flex items-start gap-3 text-blue-700">
                <Phone className="w-4 h-4 mt-0.5 flex-shrink-0" />
                <span>06 64 42 66 02</span>
              </li>
              <li className="flex items-start gap-3">
                <Mail className="w-4 h-4 mt-0.5 flex-shrink-0" />
                <span>academyjefalcentre@gmail.com</span>
              </li>
              <li className="flex items-start gap-3">
                <MapPin className="w-4 h-4 mt-0.5 flex-shrink-0" />
                <span>Rue Mohammed V, Immeuble Al Qods, App N6, Settat</span>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div className="border-t border-blue-700/10">
        <div className="container mx-auto px-4 lg:px-8 py-5 flex flex-col md:flex-row items-center justify-between text-xs text-blue-700 opacity-100">
          <div className="flex items-center gap-3">
            <span>© 2026 Academy American International JEFAL Privé. Tous droits réservés.</span>
            <span className="mr-2 text-sm">Powered by</span>
            <a href="https://www.zelvit.com" target="_blank" rel="noopener noreferrer" className="bg-blue-700 text-white px-3 py-1 rounded hover:bg-blue-900 transition-all text-sm font-semibold">
              Zevit
            </a>
          </div>
          <span className="mt-2 md:mt-0">Settat, Maroc 🇲🇦</span>
        </div>
      </div>
    </footer>
  );
}


