<?php $__env->startSection('title', 'Secretary - Edit Payment'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:700px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Modifier le paiement</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Formulaire de modification</div>
    </div>
    <form method="POST" action="<?php echo e(route('secretary.payments.update', $payment)); ?>" style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:grid;gap:1.2rem;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>
        <div style="display:grid;gap:.7rem;grid-template-columns:1fr 1fr;">
            <div>
                <label style="font-weight:600;color:#2563eb;">Étudiant</label>
                <select name="student_id" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
                    <?php $__currentLoopData = $students; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($student->id); ?>" <?php if(old('student_id', $payment->student_id) == $student->id): echo 'selected'; endif; ?>><?php echo e($student->name); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label style="font-weight:600;color:#2563eb;">Montant</label>
                <input name="amount" type="number" min="0" step="0.01" value="<?php echo e(old('amount', $payment->amount)); ?>" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            </div>
            <div>
                <label style="font-weight:600;color:#2563eb;">Mois</label>
                <input name="month" type="number" min="1" max="12" value="<?php echo e(old('month', $payment->month)); ?>" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            </div>
            <div>
                <label style="font-weight:600;color:#2563eb;">Année</label>
                <input name="year" type="number" min="2020" max="2100" value="<?php echo e(old('year', $payment->year)); ?>" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            </div>
            <div>
                <label style="font-weight:600;color:#2563eb;">Statut</label>
                <select name="status" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
                    <?php $__currentLoopData = ['paid', 'pending', 'late']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $status): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($status); ?>" <?php if(old('status', $payment->status === 'unpaid' ? 'pending' : $payment->status) === $status): echo 'selected'; endif; ?>><?php echo e($status); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div>
                <label style="font-weight:600;color:#2563eb;">Méthode</label>
                <select name="payment_method" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
                    <option value="cash" <?php if(old('payment_method', $payment->payment_method) === 'cash'): echo 'selected'; endif; ?>>cash</option>
                    <option value="cmi" <?php if(old('payment_method', $payment->payment_method) === 'cmi'): echo 'selected'; endif; ?>>cmi</option>
                </select>
            </div>
            <div style="grid-column:1/3;">
                <label style="font-weight:600;color:#2563eb;">Transaction ID (optionnel)</label>
                <input name="transaction_id" value="<?php echo e(old('transaction_id', $payment->transaction_id)); ?>" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            </div>
        </div>
        <div style="display:flex;gap:1rem;margin-top:1.2rem;">
            <button class="btn" type="submit" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;">Mettre à jour</button>
            <a class="btn" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="<?php echo e(route('secretary.payments.show', $payment)); ?>">Retour</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/dashboard/secretary/payments/edit.blade.php ENDPATH**/ ?>