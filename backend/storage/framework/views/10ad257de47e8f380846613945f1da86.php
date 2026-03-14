<?php $__env->startSection('title', 'Secretary - Student Details'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:900px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;"><?php echo e($student->name); ?></h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Détails de l'étudiant</div>
        </div>
        <div style="display:flex;gap:1rem;">
            <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="<?php echo e(route('secretary.students.edit', $student)); ?>">Modifier</a>
            <a class="btn" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="<?php echo e(route('secretary.students.index')); ?>">Liste</a>
        </div>
    </div>
    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;margin-bottom:2.2rem;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.2rem;">
            <div><strong>Email:</strong> <span style="color:#334155;"><?php echo e($student->email); ?></span></div>
            <div><strong>Téléphone:</strong> <span style="color:#334155;"><?php echo e($student->phone ?? '-'); ?></span></div>
            <div><strong>Classe:</strong> <span style="color:#334155;"><?php echo e($student->schoolClass?->name ?? '-'); ?></span></div>
            <div><strong>Statut paiement:</strong> <span style="color:#334155;"><?php echo e($student->payment_status); ?></span></div>
            <div><strong>Total payé:</strong> <span style="color:#22c55e;font-weight:600;"><?php echo e(number_format((float) $totalPaid, 2)); ?></span></div>
            <div><strong>Solde restant:</strong> <span style="color:#ef4444;font-weight:600;"><?php echo e(number_format((float) $remainingBalance, 2)); ?></span></div>
        </div>
    </div>
    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;">
        <h3 style="margin:0 0 1.2rem 0;font-size:1.25rem;color:#2563eb;font-weight:700;">Historique des paiements</h3>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;min-width:600px;">
                <thead>
                    <tr style="background:#f1f5f9;color:#2563eb;font-size:1.08rem;">
                        <th style="padding:.9rem .7rem;text-align:left;border-top-left-radius:.7rem;">Mois/Année</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Montant</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Statut</th>
                        <th style="padding:.9rem .7rem;text-align:left;border-top-right-radius:.7rem;">Méthode</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $student->payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr style="border-bottom:1px solid #e2e8f0;transition:background .15s;">
                        <td style="padding:.8rem .7rem;"><?php echo e($payment->month); ?>/<?php echo e($payment->year); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e(number_format((float) $payment->amount, 2)); ?></td>
                        <td style="padding:.8rem .7rem;">
                            <?php if($payment->status === 'paid'): ?>
                                <span style="background:#22c55e22;color:#16a34a;padding:.35em 1em;border-radius:.6em;font-weight:600;font-size:.98rem;">Payé</span>
                            <?php elseif($payment->status === 'pending'): ?>
                                <span style="background:#facc1522;color:#b45309;padding:.35em 1em;border-radius:.6em;font-weight:600;font-size:.98rem;">En attente</span>
                            <?php else: ?>
                                <span style="background:#ef444422;color:#b91c1c;padding:.35em 1em;border-radius:.6em;font-weight:600;font-size:.98rem;">Impayé</span>
                            <?php endif; ?>
                        </td>
                        <td style="padding:.8rem .7rem;"><?php echo e($payment->payment_method); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="4" style="padding:.8rem .7rem;text-align:center;color:#64748b;">Aucun paiement enregistré.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/dashboard/secretary/students/show.blade.php ENDPATH**/ ?>