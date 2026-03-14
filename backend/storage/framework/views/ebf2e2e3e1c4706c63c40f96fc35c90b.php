<?php $__env->startSection('title', 'Student Dashboard'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:1200px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Tableau de bord étudiant</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Vue d'ensemble de votre scolarité</div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1.5rem;margin-bottom:2.2rem;">
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['assignments']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Devoirs</small>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['pdfs']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Supports PDF</small>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['schedules']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Créneaux</small>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;">
            <strong style="font-size:2.2rem;color:#ef4444;"><?php echo e($stats['unpaid_payments']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Paiements impayés</small>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['announcements']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Annonces</small>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:1.5rem;">
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:1.5rem 1.2rem;">
            <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">Devoirs</h3>
            <ul style="margin:0;padding-left:1.2rem;">
                <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li style="margin-bottom:.4rem;color:#334155;font-size:1.05rem;"><?php echo e($item->title); ?> <span style="color:#64748b;">- <?php echo e($item->deadline?->format('Y-m-d')); ?></span></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:1.5rem 1.2rem;">
            <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">Supports PDF</h3>
            <ul style="margin:0;padding-left:1.2rem;">
                <?php $__currentLoopData = $pdfs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li style="margin-bottom:.4rem;color:#334155;font-size:1.05rem;"><?php echo e($item->title); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:1.5rem 1.2rem;">
            <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">Emploi du temps</h3>
            <ul style="margin:0;padding-left:1.2rem;">
                <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li style="margin-bottom:.4rem;color:#334155;font-size:1.05rem;"><?php echo e($item->day_of_week); ?> <span style="color:#64748b;"><?php echo e($item->starts_at); ?>-<?php echo e($item->ends_at); ?></span></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:1.5rem 1.2rem;">
            <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">Paiements</h3>
            <ul style="margin:0;padding-left:1.2rem;">
                <?php $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li style="margin-bottom:.4rem;color:#334155;font-size:1.05rem;"><?php echo e($item->month); ?>/<?php echo e($item->year); ?> <span style="color:#64748b;">- <?php echo e($item->status); ?> - <?php echo e($item->amount); ?></span></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:1.5rem 1.2rem;">
            <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">Annonces</h3>
            <ul style="margin:0;padding-left:1.2rem;">
                <?php $__currentLoopData = $announcements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li style="margin-bottom:.4rem;color:#334155;font-size:1.05rem;"><?php echo e($item->title); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\RND\Documents\zelvit\jefal\schoolWebsite-main\backend\resources\views/dashboard/student/index.blade.php ENDPATH**/ ?>