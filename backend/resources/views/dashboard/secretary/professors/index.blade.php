@extends('layouts.dashboard')
@section('title', 'Secretary - Professors')
@section('dashboard-content')
<div style="max-width:1100px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Gestion des Professeurs</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Ajouter un professeur et ses horaires</div>
    </div>

    <form method="POST" action="{{ route('secretary.professors.store') }}" style="padding:2rem 1.2rem;display:flex;flex-direction:column;gap:1.2rem;margin-bottom:2rem">
        @csrf
        <label style="font-weight:600;color:#2563eb;">Nom</label>
        <input name="name" value="{{ old('name') }}" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Email</label>
        <input name="email" type="email" value="{{ old('email') }}" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Mot de passe</label>
        <input name="password" type="password" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Téléphone</label>
        <input name="phone" value="{{ old('phone') }}" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <div style="border:1px dashed #cbd5f5;border-radius:10px;padding:12px;">
            <div style="font-weight:600;color:#2563eb;margin-bottom:.6rem;">Horaires de travail</div>
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

        <div style="display:flex;gap:1rem;margin-top:1.2rem;">
            <button class="btn" type="submit" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;">Enregistrer</button>
        </div>
    </form>

    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(280px,1fr));gap:1.5rem;">
        @forelse($professors as $professor)
            @php
                $hoursJson = $professor->workingHours
                    ->map(function ($h) {
                        return [
                            'day' => $h->day_of_week,
                            'starts_at' => substr($h->starts_at, 0, 5),
                            'ends_at' => substr($h->ends_at, 0, 5),
                        ];
                    })
                    ->values();
            @endphp
            <div
                data-professor-id="{{ $professor->id }}"
                data-hours='@json($hoursJson)'
                style="padding:1.6rem 1.2rem;display:flex;flex-direction:column;gap:1rem"
            >
                <div>
                    <div style="font-size:1.12rem;font-weight:700;color:#2563eb;">{{ $professor->name }}</div>
                    <div style="color:#64748b;font-size:.98rem;">{{ $professor->email }}</div>
                </div>
                <div style="color:#475569;font-size:.95rem;">
                    <strong>Horaires:</strong>
                    <ul style="margin:.4rem 0 0 1rem;">
                        @forelse($professor->workingHours as $slot)
                            <li>{{ $slot->day_of_week }}: {{ $slot->starts_at }} - {{ $slot->ends_at }}</li>
                        @empty
                            <li>Aucun créneau</li>
                        @endforelse
                    </ul>
                </div>
                <div style="display:flex;gap:.8rem;flex-wrap:wrap;">
                    <button class="btn edit-hours-btn" type="button" style="background:#2563eb;color:white;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;border:none;">Modifier horaires</button>
                    <form method="POST" action="{{ route('secretary.professors.destroy', $professor) }}" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button class="btn" style="background:#ef4444;color:white;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;border:none;" type="submit" onclick="return confirm('Supprimer ce professeur ?')">Supprimer</button>
                    </form>
                </div>
            </div>
        @empty
            <div style="color:#64748b;font-size:1.1rem;">Aucun professeur trouvé.</div>
        @endforelse
    </div>
    <div style="margin-top:2.2rem;">
        {{ $professors->links() }}
    </div>
</div>

<!-- Edit Hours Modal -->
<div id="editHoursModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(15,23,42,.35);backdrop-filter:blur(6px);justify-content:center;align-items:center;z-index:999;">
    <form method="POST" id="editHoursForm" style="background:white;padding:28px;border-radius:16px;width:520px;box-shadow:0 20px 60px rgba(0,0,0,.2);position:relative;">
        @csrf
        @method('PUT')
        <button type="button" id="closeEditHours" style="position:absolute;right:18px;top:15px;font-size:22px;border:none;background:none;cursor:pointer;color:#64748b;">×</button>
        <div style="font-size:20px;font-weight:700;margin-bottom:16px;color:#0f172a;">Modifier horaires</div>
        <div id="editHoursContainer" style="display:flex;flex-direction:column;gap:.6rem;"></div>
        <button type="button" id="addEditHour" class="btn" style="margin-top:.6rem;background:#e2e8f0;color:#1e293b;">+ Ajouter un créneau</button>
        <div style="display:flex;gap:1rem;margin-top:1.2rem;">
            <button class="btn" type="submit" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;">Enregistrer</button>
        </div>
    </form>
</div>

<script>
const addWorkingHourBtn = document.getElementById("addWorkingHour")
const workingContainer = document.getElementById("workingHoursContainer")
let workingIndex = 1

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

const editModal = document.getElementById("editHoursModal")
const editForm = document.getElementById("editHoursForm")
const editContainer = document.getElementById("editHoursContainer")
const closeEditBtn = document.getElementById("closeEditHours")
const addEditHourBtn = document.getElementById("addEditHour")

function renderEditRow(idx, data) {
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
    editContainer.appendChild(row)
}

document.querySelectorAll('.edit-hours-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        const card = btn.closest('div[data-professor-id]')
        const id = card?.dataset.professorId
        const hours = card?.dataset.hours ? JSON.parse(card.dataset.hours) : []
        editContainer.innerHTML = ''
        hours.forEach((h, idx) => renderEditRow(idx, h))
        if (hours.length === 0) {
            renderEditRow(0, {})
        }
        editForm.action = `/dashboard/secretary/professors/${id}`
        editModal.style.display = 'flex'
    })
})

addEditHourBtn.onclick = () => {
    const idx = editContainer.children.length
    renderEditRow(idx, {})
}

closeEditBtn.onclick = () => { editModal.style.display = 'none' }
editModal.onclick = (e) => { if (e.target === editModal) { editModal.style.display = 'none' } }
</script>
@endsection

