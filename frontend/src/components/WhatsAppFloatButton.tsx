import { useState } from "react";
import { useAuth } from "@/contexts/AuthContext";

type WhatsAppContact = {
  name: string;
  number: string;
};

const DEFAULT_MESSAGE = "Bonjour, je souhaite des informations sur vos programmes.";

const contacts: WhatsAppContact[] = [
  { name: "Commercial 1", number: "212600000000" },
  { name: "Commercial 2", number: "212600000001" },
];

export default function WhatsAppFloatButton() {
  const [open, setOpen] = useState(false);
  const { user } = useAuth();

  const links = contacts.map((contact) => ({
    ...contact,
    href: `https://wa.me/${contact.number}?text=${encodeURIComponent(DEFAULT_MESSAGE)}`,
  }));

  if (user) {
    return null;
  }

  return (
    <div className="fixed bottom-5 right-5 z-[60] flex flex-col items-end gap-3">
      <div
        className={`origin-bottom-right rounded-2xl border border-border bg-card shadow-lg transition-all duration-200 ${
          open ? "scale-100 opacity-100 translate-y-0" : "pointer-events-none scale-95 opacity-0 translate-y-2"
        }`}
      >
        <div className="px-4 pt-4 pb-2">
          <div className="text-sm font-semibold text-foreground">Contact WhatsApp</div>
          <div className="text-xs text-muted-foreground">Choisissez un contact</div>
        </div>
        <div className="px-2 pb-3">
          {links.map((contact) => (
            <a
              key={contact.number}
              href={contact.href}
              target="_blank"
              rel="noreferrer"
              className="flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 text-sm font-medium text-foreground hover:bg-secondary transition-colors"
            >
              <span className="flex flex-col">
                <span>{contact.name}</span>
                <span className="text-xs text-muted-foreground">{contact.number}</span>
              </span>
              <span className="text-xs text-primary font-semibold">Ouvrir</span>
            </a>
          ))}
        </div>
      </div>

      <button
        type="button"
        onClick={() => setOpen((prev) => !prev)}
        className="group flex items-center justify-center w-14 h-14 rounded-full bg-[#25D366] text-white shadow-lg hover:shadow-xl transition-shadow"
        aria-label="Ouvrir WhatsApp"
      >
        <svg
          viewBox="0 0 32 32"
          className="w-7 h-7 fill-current"
          aria-hidden="true"
        >
          <path d="M16 .064C7.168.064 0 7.232 0 16.064c0 2.832.736 5.6 2.144 8.032L0 32l8.128-2.112c2.368 1.28 5.04 1.952 7.872 1.952 8.832 0 16-7.168 16-16S24.832.064 16 .064zm0 29.44c-2.56 0-4.992-.672-7.136-1.92l-.512-.288-4.816 1.248 1.28-4.704-.32-.512c-1.28-2.176-1.952-4.672-1.952-7.232 0-7.808 6.368-14.176 14.176-14.176S30.176 8.256 30.176 16.064 23.808 29.504 16 29.504zm8.224-10.912c-.448-.224-2.688-1.344-3.104-1.504-.416-.16-.704-.224-1.024.224-.32.448-1.184 1.504-1.472 1.792-.256.288-.512.32-.96.096-.448-.224-1.888-.672-3.584-2.176-1.312-1.184-2.176-2.624-2.432-3.072-.256-.448-.032-.672.192-.896.192-.192.448-.512.672-.768.224-.256.288-.448.448-.736.16-.32.096-.576-.032-.8-.16-.224-1.024-2.464-1.408-3.36-.352-.864-.736-.736-1.024-.736h-.864c-.288 0-.736.096-1.12.512-.384.416-1.472 1.44-1.472 3.52 0 2.08 1.504 4.096 1.728 4.384.224.288 2.944 4.512 7.136 6.336 1.024.448 1.824.704 2.432.896 1.024.32 1.952.288 2.688.192.832-.128 2.688-1.088 3.072-2.144.384-1.056.384-1.952.256-2.144-.128-.192-.416-.32-.864-.544z" />
        </svg>
      </button>
    </div>
  );
}
