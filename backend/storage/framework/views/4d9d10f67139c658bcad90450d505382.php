<?php $__env->startSection('title', 'Secretary Dashboard'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:1400px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Tableau de bord secrétaire</h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Vue d'ensemble des paiements et accès rapides</div>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;margin-bottom:2.2rem;">
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['total_payments']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Paiements enregistrés</small>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['collected_this_month']); ?> MAD</strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Montant collecté ce mois</small>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['pending_payments']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Paiements à suivre</small>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['paid_this_month']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Paiements ce mois</small>
        </div>
    </div>
    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;">
        <h3 style="margin:0 0 1.2rem 0;font-size:1.25rem;color:#2563eb;font-weight:700;">Accès rapides</h3>
        <div style="display:flex;gap:1rem;flex-wrap:wrap;">
            <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="<?php echo e(route('secretary.students.create')); ?>">Nouvel étudiant</a>
            <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="<?php echo e(route('secretary.payments.create')); ?>">Nouveau paiement</a>
            <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="<?php echo e(route('secretary.payments.index')); ?>">Voir tous les paiements</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\RND\Documents\zelvit\jefal\schoolWebsite-main\backend\resources\views/dashboard/secretary/index.blade.php ENDPATH**/ ?>