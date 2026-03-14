<?php $__env->startSection('title', 'Secretary - Payments'); ?>

<?php $__env->startSection('dashboard-content'); ?>


<div style="max-width:1400px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Gestion des paiements</h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Liste et gestion des paiements enregistrés</div>
        </div>
        <a href="<?php echo e(route('secretary.payments.create')); ?>" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;transition:background .2s;">+ Enregistrer un paiement</a>
    </div>
    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;min-width:900px;">
                <thead>
                    <tr style="background:#f1f5f9;color:#2563eb;font-size:1.08rem;">
                        <th style="padding:.9rem .7rem;text-align:left;border-top-left-radius:.7rem;">Étudiant</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Montant</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Mois / Année</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Statut</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Méthode</th>
                        <th style="padding:.9rem .7rem;text-align:left;border-top-right-radius:.7rem;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="border-bottom:1px solid #e2e8f0;transition:background .15s;">
                        <td style="padding:.8rem .7rem;"><?php echo e($payment->student?->name); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e(number_format((float) $payment->amount, 2)); ?> MAD</td>
                        <td style="padding:.8rem .7rem;"><?php echo e($payment->month); ?>/<?php echo e($payment->year); ?></td>
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
                        <td style="padding:.8rem .7rem;">
                            <a href="<?php echo e(route('secretary.payments.show', $payment)); ?>" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;margin-right:.5rem;">Voir</a>
                            <a href="<?php echo e(route('secretary.payments.edit', $payment)); ?>" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;margin-right:.5rem;">Modifier</a>
                            <form method="POST" action="<?php echo e(route('secretary.payments.destroy', $payment)); ?>" style="display:inline">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" style="background:#ef4444;color:white;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;border:none;" onclick="return confirm('Supprimer ce paiement ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
        <div style="margin-top:1.5rem;">
            <?php echo e($payments->links()); ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/dashboard/secretary/payments/index.blade.php ENDPATH**/ ?>