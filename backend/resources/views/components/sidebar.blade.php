@php
    $role = auth()->user()?->role;
    $items = [
        'admin' => [
            ['label' => 'Tableau de bord', 'route' => 'admin.dashboard'],
            ['label' => 'Utilisateurs', 'route' => 'admin.users.index'],
            ['label' => 'Classes', 'route' => 'admin.classes.index'],
            ['label' => 'Salles', 'route' => 'admin.rooms.index'],
            ['label' => 'Paiements', 'route' => 'admin.payments.index'],
            ['label' => 'Annonces', 'route' => 'admin.announcements.index'],
        ],
        'directeur' => [
            ['label' => 'Tableau de bord', 'route' => 'admin.dashboard'],
            ['label' => 'Utilisateurs', 'route' => 'admin.users.index'],
            ['label' => 'Classes', 'route' => 'admin.classes.index'],
            ['label' => 'Salles', 'route' => 'admin.rooms.index'],
            ['label' => 'Paiements', 'route' => 'admin.payments.index'],
            ['label' => 'Annonces', 'route' => 'admin.announcements.index'],
        ],
        'secretary' => [
            ['label' => 'Tableau de bord', 'route' => 'secretary.dashboard'],
            ['label' => 'Etudiants', 'route' => 'secretary.students.index'],
            ['label' => 'Professeurs', 'route' => 'secretary.professors.index'],
            ['label' => 'Paiements', 'route' => 'secretary.payments.index'],
            ['label' => 'Annonces', 'route' => 'secretary.announcements.index'],
        ],
        'professor' => [
            ['label' => 'Tableau de bord', 'route' => 'professor.dashboard'],
            ['label' => 'Profil', 'route' => 'professor.profile.show'],
            ['label' => 'Emploi du temps', 'route' => 'professor.schedules.index'],
            ['label' => 'PDF Cours', 'route' => 'professor.materials.index'],
            ['label' => 'Assignments', 'route' => 'professor.assignments.index'],
            ['label' => 'Annonces', 'route' => 'professor.announcements.index'],
        ],
        'student' => [
            ['label' => 'Tableau de bord', 'route' => 'student.dashboard'],
            ['label' => 'Emploi du temps', 'route' => 'student.schedule.index'],
            ['label' => 'Assignments', 'route' => 'student.assignments.index'],
            ['label' => 'Contenu de cours', 'route' => 'student.course-content.index'],
            ['label' => 'Profil', 'route' => 'student.profile.edit'],
        ],
        'commercial' => [
            ['label' => 'Tableau de bord', 'route' => 'home'],
        ],
    ][$role] ?? [];
@endphp

<aside style="width:230px!important;min-width:230px!important;max-width:230px!important;min-height:100vh;background:#181f2c;color:#fff;padding:2rem 1rem 1rem 1.2rem;box-shadow:2px 0 16px 0 rgba(30,41,59,0.08);display:flex;flex-direction:column;align-items:flex-start;border-radius:0;">
    <div style="margin-bottom:2.2rem;width:100%;display:flex;align-items:center;gap:.7rem;">
        <img src="/logo.png" alt="Logo EduManage" style="width:32px;height:32px;object-fit:contain;background:#fff;padding:.1rem;box-shadow:0 1px 4px 0 rgba(37,99,235,0.08);border-radius:0;" onerror="this.style.display='none';this.insertAdjacentHTML('afterend', '<span style=&quot;color:#fff;font-weight:700;font-size:1.1rem;&quot;>Logo</span>')">
        <div>
            <h2 style="margin:0;font-size:1.1rem;font-weight:700;letter-spacing:-1px;color:#fff;">Jefal privé</h2>
            <small style="color:#c7d2fe;font-size:.98rem;">{{ ucfirst($role ?? 'guest') }}</small>
        </div>
    </div>
    <nav style="width:100%;flex:1;display:flex;flex-direction:column;gap:.1rem;">
        @foreach($items as $item)
            @php
                $icons = [
                    'Tableau de bord' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" rx="2" fill="#fff" stroke="#2563eb" stroke-width="2"/><rect x="14" y="3" width="7" height="7" rx="2" fill="#fff" stroke="#2563eb" stroke-width="2"/><rect x="14" y="14" width="7" height="7" rx="2" fill="#fff" stroke="#2563eb" stroke-width="2"/><rect x="3" y="14" width="7" height="7" rx="2" fill="#fff" stroke="#2563eb" stroke-width="2"/></svg>',
                    'Utilisateurs' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" stroke="#2563eb" stroke-width="2" fill="none"/></svg>',
                    'Classes' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><rect x="4" y="7" width="16" height="10" rx="2" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M8 7V5a4 4 0 0 1 8 0v2" stroke="#2563eb" stroke-width="2" fill="none"/></svg>',
                    'Salles' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><rect x="3" y="11" width="18" height="10" rx="2" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M7 11V7a5 5 0 0 1 10 0v4" stroke="#2563eb" stroke-width="2" fill="none"/></svg>',
                    'Paiements' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="10" rx="2" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M2 10h20" stroke="#2563eb" stroke-width="2" fill="none"/><circle cx="8" cy="14" r="1.5" fill="#2563eb"/></svg>',
                    'Etudiants' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" stroke="#2563eb" stroke-width="2" fill="none"/></svg>',
                    'Profil' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" stroke="#2563eb" stroke-width="2" fill="none"/></svg>',
                    'Modifier profil' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><path d="M15.232 5.232a3 3 0 0 1 4.243 4.243l-9.193 9.193a2 2 0 0 1-.878.515l-4.243 1.06 1.06-4.243a2 2 0 0 1 .515-.878l9.193-9.193z" stroke="#2563eb" stroke-width="2" fill="#fff"/></svg>',
                    'Emploi du temps' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><rect x="3" y="5" width="18" height="16" rx="2" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M16 3v4M8 3v4M3 9h18" stroke="#2563eb" stroke-width="2" fill="none"/></svg>',
                    'PDF Cours' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M8 2v4M16 2v4" stroke="#2563eb" stroke-width="2" fill="none"/></svg>',
                    'Contenu de cours' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><rect x="4" y="3" width="16" height="18" rx="2" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M8 7h8M8 11h8M8 15h6" stroke="#2563eb" stroke-width="2" fill="none"/></svg>',
                    'Assignments' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M8 2v4M16 2v4M4 10h16" stroke="#2563eb" stroke-width="2" fill="none"/></svg>',
                    'Annonces' => '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="2" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M12 8v4l3 3" stroke="#2563eb" stroke-width="2" fill="none"/></svg>',
                ];
                $icon = $icons[$item['label']] ?? '<svg width="20" height="20" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="12" r="9" stroke="#2563eb" stroke-width="2" fill="#fff"/></svg>';
                $isActive = url()->current() === route($item['route']);
            @endphp
            <a href="{{ route($item['route']) }}"
                style="display:flex;align-items:center;gap:.6rem;color:#fff;text-decoration:none;padding:.65rem 1rem .65rem .7rem;border-radius:0;margin-bottom:.1rem;font-weight:500;transition:background .18s, color .18s;font-size:1.01rem;background:{{ $isActive ? '#2563eb' : 'transparent' }};box-shadow:none;border:none;"
                onmouseover="this.style.background='#2563eb';this.style.color='#fff';"
                onmouseout="if({{ $isActive ? 'true' : 'false' }}){this.style.background='#2563eb';this.style.color='#fff';}else{this.style.background='transparent';this.style.color='#fff';}">
                <span style="display:inline-block;width:1.2em;text-align:center;opacity:.95;">{!! $icon !!}</span> {{ $item['label'] }}
            </a>
        @endforeach
    </nav>
    <form method="POST" action="{{ route('logout') }}" style="margin-top:1.5rem;width:100%;">
        @csrf
        <button type="submit" style="width:100%;background:linear-gradient(90deg,#2563eb 60%,#0ea5e9 100%);border:0;color:#fff;padding:.7rem 0;border-radius:0;cursor:pointer;font-weight:600;font-size:1.01rem;box-shadow:none;transition:background .18s;">Déconnexion</button>
    </form>
</aside>
