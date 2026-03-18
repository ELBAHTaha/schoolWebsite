@extends('layouts.dashboard')
@section('title', 'Admin - Users')
@section('dashboard-content')
<style>
.modal-bg{
    display:none;
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:100%;
    background:rgba(15,23,42,.35);
    backdrop-filter:blur(6px);
    justify-content:center;
    align-items:center;
    z-index:999;
}
.modal-card{
    background:white;
    padding:28px;
    border-radius:16px;
    width:420px;
    box-shadow:0 20px 60px rgba(0,0,0,.2);
    position:relative;
}
.modal-title{
    font-size:20px;
    font-weight:700;
    margin-bottom:16px;
    color:#0f172a;
}
.modal-input{
    width:100%;
    padding:10px 14px;
    border-radius:8px;
    border:1px solid #e2e8f0;
    margin-bottom:12px;
    font-size:14px;
}
.modal-input:focus{border-color:#2563eb;outline:none;}
.modal-close{
    position:absolute;
    right:18px;
    top:15px;
    font-size:22px;
    border:none;
    background:none;
    cursor:pointer;
    color:#64748b;
}
.modal-close:hover{color:#ef4444;}
.create-btn{
    background:linear-gradient(135deg,#2563eb,#0ea5e9);
    color:white;
    border:none;
    padding:12px 22px;
    border-radius:10px;
    font-weight:600;
    cursor:pointer;
    transition:all .25s;
    box-shadow:0 4px 14px rgba(37,99,235,.3);
}
.create-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 20px rgba(37,99,235,.4);
}
.btn{
    background:#dc2626;
    color:#fff;
    border:none;
    padding:6px 12px;
    border-radius:8px;
    font-size:13px;
    font-weight:600;
    cursor:pointer;
}
</style>
<div style="max-width:1400px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Gestion des Utilisateurs</h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">GÃ©rez et suivez tous les utilisateurs de l'Ã©tablissement</div>
        </div>
        <button id="showCreateFormBtn" class="create-btn" style="margin-left:auto;">+ CrÃ©er un utilisateur</button>
    </div>
    <div style="display:grid;grid-template-columns:repeat(1,1fr);gap:1.2rem;margin-bottom:2.2rem;">
        <div style="padding:1.3rem 1.2rem;display:flex;align-items:center;gap:.8rem">
            <span style="background:#e0e7ff;color:#2563eb;padding:.5rem;border-radius:.7rem;display:flex;align-items:center;justify-content:center;">
                <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><circle cx="12" cy="8" r="4" stroke="#2563eb" stroke-width="2" fill="#fff"/><path d="M4 20c0-2.21 3.58-4 8-4s8 1.79 8 4" stroke="#2563eb" stroke-width="2" fill="none"/></svg>
            </span>
            <div>
                <div style="font-size:1.5rem;font-weight:700;">{{ $stats['total'] ?? $users->count() }}</div>
                <div style="color:#64748b;font-size:.98rem;">Total utilisateurs</div>
            </div>
        </div>
    </div>
    <div style="display:flex;gap:1rem;margin-bottom:2.2rem;flex-wrap:wrap;">
        <input type="text" id="searchInput" placeholder="Rechercher un utilisateur..." style="flex:1 1 300px;min-width:220px;padding:.9rem 1.2rem;border-radius:.7rem;border:1px solid #e5e7eb;font-size:1.08rem;background:#fff;">
        <select id="roleFilter" style="padding:.9rem 1.2rem;border-radius:.7rem;border:1px solid #e5e7eb;font-size:1.08rem;background:#fff;">
            <option value="">Tous les rôles</option>
            <option value="admin">admin</option>
            <option value="directeur">directeur</option>
            <option value="secretary">secretary</option>
            <option value="professor">professor</option>
            <option value="student">student</option>
            <option value="visitor">visitor</option>
            <option value="commercial">commercial</option>
        </select>
    </div>
    <div style="display:flex;justify-content:center;margin-top:1.5rem;">
        <table style="width:100%;max-width:1100px;border-collapse:separate;border-spacing:0 0.7rem;font-size:1.08rem;overflow:hidden">
            <thead>
                <tr style="background:linear-gradient(90deg,#2563eb 60%,#0ea5e9 100%);color:#fff;">
                    <th style="padding:1rem 1.2rem;border-top-left-radius:1rem;text-align:left;font-weight:700;">ID</th>
                    <th style="padding:1rem 1.2rem;text-align:left;font-weight:700;">Nom</th>
                    <th style="padding:1rem 1.2rem;text-align:left;font-weight:700;">Email</th>
                    <th style="padding:1rem 1.2rem;text-align:left;font-weight:700;">RÃ´le</th>
                    <th style="padding:1rem 1.2rem;border-top-right-radius:1rem;text-align:left;font-weight:700;">Actions</th>
                </tr>
            </thead>
            <tbody id="usersTableBody">
            @foreach($users as $user)
                @php
                    $hoursJson = $user->role === 'professor'
                        ? $user->workingHours
                            ->map(function ($h) {
                                return [
                                    'day' => $h->day_of_week,
                                    'starts_at' => substr($h->starts_at, 0, 5),
                                    'ends_at' => substr($h->ends_at, 0, 5),
                                ];
                            })
                            ->values()
                        : collect();
                @endphp
                <tr
                    data-role="{{ strtolower($user->role) }}"
                    data-user-id="{{ $user->id }}"
                    data-hours='@json($hoursJson)'
                    style="background:#f8fafc;color:#1e293b;box-shadow:0 1px 4px 0 rgba(16,24,40,0.04);border-radius:.7rem;transition:background .2s;"
                >
                    <td style="padding:1rem 1.2rem;border-radius:.7rem 0 0 .7rem;">{{ $user->id }}</td>
                    <td style="padding:1rem 1.2rem;">{{ $user->name }}</td>
                    <td style="padding:1rem 1.2rem;">{{ $user->email }}</td>
                    <td style="padding:1rem 1.2rem;">{{ $user->role }}</td>
                    <td style="padding:1rem 1.2rem;border-radius:0 .7rem .7rem 0;">
                        @if($user->role === 'professor')
                        <button class="btn edit-hours-btn" type="button" style="background:#2563eb;margin-right:.5rem;">Horaires</button>
                        @endif
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" style="display:inline;">
                            @csrf @method('DELETE')
                            <button class="btn" style="background:#dc2626;" type="submit">Supprimer</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <div style="display:flex;justify-content:center;margin-top:1.2rem;">{{ $users->links() }}</div>
</div>
<!-- --- Modal Create User --- -->
<div id="userModal" class="modal-bg">
    <form method="POST" action="{{ route('admin.users.store') }}" class="modal-card">
        @csrf
        <button type="button" id="closeUserModal" class="modal-close">×</button>
        <div class="modal-title">Créer un utilisateur</div>
        <input name="name" placeholder="Nom" required class="modal-input">
        <input name="email" type="email" placeholder="Email" required class="modal-input">
        <input name="password" type="password" placeholder="Mot de passe" required class="modal-input">
        <select name="role" id="roleSelect" required class="modal-input">
            <option value="">Rôle</option>
            <option value="admin">admin</option>
            <option value="directeur">directeur</option>
            <option value="secretary">secretary</option>
            <option value="professor">professor</option>
            <option value="student">student</option>
            <option value="visitor">visitor</option>
            <option value="commercial">commercial</option>
        </select>
        <input name="phone" placeholder="Téléphone (optionnel)" class="modal-input">
        <div id="workingHoursSection" style="display:none;border:1px dashed #cbd5f5;border-radius:10px;padding:12px;">
            <div style="font-weight:600;color:#2563eb;margin-bottom:.6rem;">Horaires de travail (professeur)</div>
            <div id="workingHoursContainer" style="display:flex;flex-direction:column;gap:.6rem;">
                <div class="wh-row" style="display:grid;grid-template-columns:1.2fr 1fr 1fr;gap:.6rem;">
                    <select name="working_hours[0][day]" class="modal-input" style="margin:0;">
                        <option value="">Jour</option>
                        <option value="Monday">Monday</option>
                        <option value="Tuesday">Tuesday</option>
                        <option value="Wednesday">Wednesday</option>
                        <option value="Thursday">Thursday</option>
                        <option value="Friday">Friday</option>
                        <option value="Saturday">Saturday</option>
                        <option value="Sunday">Sunday</option>
                    </select>
                    <input name="working_hours[0][starts_at]" type="time" class="modal-input" style="margin:0;">
                    <input name="working_hours[0][ends_at]" type="time" class="modal-input" style="margin:0;">
                </div>
            </div>
            <button type="button" id="addWorkingHour" class="btn" style="margin-top:.6rem;background:#e2e8f0;color:#1e293b;">+ Ajouter un créneau</button>
        </div>
        <button class="create-btn" style="width:100%">Créer</button>
    </form>
</div>

<!-- Edit Professor Hours Modal -->
<div id="adminEditHoursModal" class="modal-bg">
    <form method="POST" id="adminEditHoursForm" class="modal-card">
        @csrf
        @method('PUT')
        <button type="button" id="adminCloseEditHours" class="modal-close">×</button>
        <div class="modal-title">Modifier horaires professeur</div>
        <div id="adminEditHoursContainer" style="display:flex;flex-direction:column;gap:.6rem;"></div>
        <button type="button" id="adminAddEditHour" class="btn" style="margin-top:.6rem;background:#e2e8f0;color:#1e293b;">+ Ajouter un créneau</button>
        <button class="create-btn" style="width:100%;margin-top:.8rem;">Enregistrer</button>
    </form>
</div>

<script>
const userModal = document.getElementById("userModal")
const showCreateBtn = document.getElementById("showCreateFormBtn")
const closeUserModalBtn = document.getElementById("closeUserModal")
const roleSelect = document.getElementById("roleSelect")
const workingSection = document.getElementById("workingHoursSection")
const workingContainer = document.getElementById("workingHoursContainer")
const addWorkingHourBtn = document.getElementById("addWorkingHour")
let workingIndex = 1

showCreateBtn.onclick = () => { userModal.style.display = "flex" }
closeUserModalBtn.onclick = () => { userModal.style.display = "none" }
userModal.onclick = (e) => { if (e.target === userModal) { userModal.style.display = "none" } }

function toggleWorkingHours() {
    const isProfessor = roleSelect.value === 'professor'
    workingSection.style.display = isProfessor ? 'block' : 'none'
}
roleSelect.addEventListener('change', toggleWorkingHours)
toggleWorkingHours()

addWorkingHourBtn.onclick = () => {
    const row = document.createElement('div')
    row.className = 'wh-row'
    row.style.display = 'grid'
    row.style.gridTemplateColumns = '1.2fr 1fr 1fr'
    row.style.gap = '.6rem'
    row.innerHTML = `
        <select name="working_hours[${workingIndex}][day]" class="modal-input" style="margin:0;">
            <option value="">Jour</option>
            <option value="Monday">Monday</option>
            <option value="Tuesday">Tuesday</option>
            <option value="Wednesday">Wednesday</option>
            <option value="Thursday">Thursday</option>
            <option value="Friday">Friday</option>
            <option value="Saturday">Saturday</option>
            <option value="Sunday">Sunday</option>
        </select>
        <input name="working_hours[${workingIndex}][starts_at]" type="time" class="modal-input" style="margin:0;">
        <input name="working_hours[${workingIndex}][ends_at]" type="time" class="modal-input" style="margin:0;">
    `
    workingContainer.appendChild(row)
    workingIndex += 1
}

const searchInput = document.getElementById('searchInput')
const roleFilter = document.getElementById('roleFilter')
const userRows = document.querySelectorAll('#usersTableBody tr')

function filterUsers() {
    const searchVal = searchInput.value.toLowerCase()
    const roleVal = roleFilter.value.toLowerCase()

    userRows.forEach(row => {
        const text = row.textContent.toLowerCase()
        const role = row.dataset.role || ''
        const matchesSearch = text.includes(searchVal)
        const matchesRole = roleVal === '' || role === roleVal
        row.style.display = matchesSearch && matchesRole ? '' : 'none'
    })
}

searchInput.addEventListener('input', filterUsers)
roleFilter.addEventListener('change', filterUsers)

const adminEditModal = document.getElementById("adminEditHoursModal")
const adminEditForm = document.getElementById("adminEditHoursForm")
const adminEditContainer = document.getElementById("adminEditHoursContainer")
const adminCloseEdit = document.getElementById("adminCloseEditHours")
const adminAddEdit = document.getElementById("adminAddEditHour")

function renderAdminEditRow(idx, data) {
    const row = document.createElement('div')
    row.style.display = 'grid'
    row.style.gridTemplateColumns = '1.2fr 1fr 1fr'
    row.style.gap = '.6rem'
    row.innerHTML = `
        <select name="working_hours[${idx}][day]" class="modal-input" style="margin:0;">
            <option value="">Jour</option>
            <option value="Monday" ${data?.day === 'Monday' ? 'selected' : ''}>Monday</option>
            <option value="Tuesday" ${data?.day === 'Tuesday' ? 'selected' : ''}>Tuesday</option>
            <option value="Wednesday" ${data?.day === 'Wednesday' ? 'selected' : ''}>Wednesday</option>
            <option value="Thursday" ${data?.day === 'Thursday' ? 'selected' : ''}>Thursday</option>
            <option value="Friday" ${data?.day === 'Friday' ? 'selected' : ''}>Friday</option>
            <option value="Saturday" ${data?.day === 'Saturday' ? 'selected' : ''}>Saturday</option>
            <option value="Sunday" ${data?.day === 'Sunday' ? 'selected' : ''}>Sunday</option>
        </select>
        <input name="working_hours[${idx}][starts_at]" type="time" class="modal-input" style="margin:0;" value="${data?.starts_at ?? ''}">
        <input name="working_hours[${idx}][ends_at]" type="time" class="modal-input" style="margin:0;" value="${data?.ends_at ?? ''}">
    `
    adminEditContainer.appendChild(row)
}

document.querySelectorAll('.edit-hours-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const row = btn.closest('tr')
        const id = row?.dataset.userId
        const hours = row?.dataset.hours ? JSON.parse(row.dataset.hours) : []
        adminEditContainer.innerHTML = ''
        hours.forEach((h, idx) => renderAdminEditRow(idx, h))
        if (hours.length === 0) {
            renderAdminEditRow(0, {})
        }
        adminEditForm.action = `/dashboard/admin/users/${id}`
        adminEditModal.style.display = 'flex'
    })
})

adminAddEdit.onclick = () => {
    const idx = adminEditContainer.children.length
    renderAdminEditRow(idx, {})
}

adminCloseEdit.onclick = () => { adminEditModal.style.display = 'none' }
adminEditModal.onclick = (e) => { if (e.target === adminEditModal) { adminEditModal.style.display = 'none' } }
</script>

@endsection











