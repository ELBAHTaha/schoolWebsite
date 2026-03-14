@extends('layouts.dashboard')
@section('title', 'Secretary - Edit Payment')
@section('dashboard-content')
<div style="max-width:700px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Modifier le paiement</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Formulaire de modification</div>
    </div>
    <form method="POST" action="{{ route('secretary.payments.update', $payment) }}" style="padding:2rem 1.2rem;display:grid;gap:1.2rem">
        @csrf
        @method('PUT')
        <div style="display:grid;gap:.7rem;grid-template-columns:1fr 1fr;">
            <div>
                <label style="font-weight:600;color:#2563eb;">Étudiant</label>
                <select name="student_id" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(old('student_id', $payment->student_id) == $student->id)>{{ $student->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-weight:600;color:#2563eb;">Montant</label>
                <input name="amount" type="number" min="0" step="0.01" value="{{ old('amount', $payment->amount) }}" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            </div>
            <div>
                <label style="font-weight:600;color:#2563eb;">Mois</label>
                <input name="month" type="number" min="1" max="12" value="{{ old('month', $payment->month) }}" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            </div>
            <div>
                <label style="font-weight:600;color:#2563eb;">Année</label>
                <input name="year" type="number" min="2020" max="2100" value="{{ old('year', $payment->year) }}" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            </div>
            <div>
                <label style="font-weight:600;color:#2563eb;">Statut</label>
                <select name="status" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
                    @foreach(['paid', 'pending', 'late'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $payment->status === 'unpaid' ? 'pending' : $payment->status) === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label style="font-weight:600;color:#2563eb;">Méthode</label>
                <select name="payment_method" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
                    <option value="cash" @selected(old('payment_method', $payment->payment_method) === 'cash')>cash</option>
                    <option value="cmi" @selected(old('payment_method', $payment->payment_method) === 'cmi')>cmi</option>
                </select>
            </div>
            <div style="grid-column:1/3;">
                <label style="font-weight:600;color:#2563eb;">Transaction ID (optionnel)</label>
                <input name="transaction_id" value="{{ old('transaction_id', $payment->transaction_id) }}" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            </div>
        </div>
        <div style="display:flex;gap:1rem;margin-top:1.2rem;">
            <button class="btn" type="submit" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;">Mettre à jour</button>
            <a class="btn" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="{{ route('secretary.payments.show', $payment) }}">Retour</a>
        </div>
    </form>
</div>
@endsection

