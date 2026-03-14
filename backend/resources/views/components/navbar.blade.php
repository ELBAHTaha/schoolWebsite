<header style="height:72px;background:rgba(255,255,255,0.98);backdrop-filter:blur(2px);box-shadow:0 2px 16px 0 rgba(30,41,59,0.07);display:flex;align-items:center;justify-content:space-between;padding:0 2.2rem 0 2rem;border-bottom:1.5px solid #e0e7ef;">
    <div style="display:flex;flex-direction:column;gap:.1rem;">
        <span style="font-size:1.18rem;font-weight:700;color:#2563eb;letter-spacing:-.5px;">Tableau de bord</span>
        <span style="color:#64748b;font-size:.98rem;">Bienvenue, <span style="font-weight:600;color:#1e293b;">{{ auth()->user()?->name }}</span></span>
    </div>
    <div style="font-size:1.05rem;color:#64748b;font-weight:500;letter-spacing:.5px;">{{ now()->format('d/m/Y H:i') }}</div>
</header>
