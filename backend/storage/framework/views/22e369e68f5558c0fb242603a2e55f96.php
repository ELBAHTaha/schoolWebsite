<?php $__env->startSection('title', 'Secretary - Payment Details'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:700px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Paiement #<?php echo e($payment->id); ?></h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Détails du paiement</div>
        </div>
        <div style="display:flex;gap:1rem;">
            <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="<?php echo e(route('secretary.payments.edit', $payment)); ?>">Modifier</a>
            <a class="btn" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="<?php echo e(route('secretary.payments.index')); ?>">Retour</a>
        </div>
    </div>
    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;">
            <div><strong>Étudiant:</strong> <span style="color:#334155;"><?php echo e($payment->student?->name ?? '-'); ?></span></div>
            <div><strong>Montant:</strong> <span style="color:#22c55e;font-weight:600;"><?php echo e(number_format((float) $payment->amount, 2)); ?></span></div>
            <div><strong>Mois/Année:</strong> <span style="color:#334155;"><?php echo e($payment->month); ?>/<?php echo e($payment->year); ?></span></div>
            <div><strong>Statut:</strong> 
                <?php if($payment->status === 'paid'): ?>
                    <span style="background:#22c55e22;color:#16a34a;padding:.35em 1em;border-radius:.6em;font-weight:600;font-size:.98rem;">Payé</span>
                <?php elseif($payment->status === 'pending' || $payment->status === 'unpaid'): ?>
                    <span style="background:#facc1522;color:#b45309;padding:.35em 1em;border-radius:.6em;font-weight:600;font-size:.98rem;">En attente</span>
                <?php else: ?>
                    <span style="background:#ef444422;color:#b91c1c;padding:.35em 1em;border-radius:.6em;font-weight:600;font-size:.98rem;">Impayé</span>
                <?php endif; ?>
            </div>
            <div><strong>Méthode:</strong> <span style="color:#334155;"><?php echo e($payment->payment_method); ?></span></div>
            <div><strong>Transaction ID:</strong> <span style="color:#334155;"><?php echo e($payment->transaction_id ?? '-'); ?></span></div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\RND\Documents\zelvit\jefal\schoolWebsite-main\backend\resources\views/dashboard/secretary/payments/show.blade.php ENDPATH**/ ?>