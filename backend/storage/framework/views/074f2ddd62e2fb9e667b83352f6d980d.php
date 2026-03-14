<?php $__env->startSection('title', 'Student - Assignments'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:900px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Mes devoirs</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Liste de tous vos devoirs</div>
    </div>
    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;">
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;min-width:600px;">
                <thead>
                    <tr style="background:#f1f5f9;color:#2563eb;font-size:1.08rem;">
                        <th style="padding:.9rem .7rem;text-align:left;border-top-left-radius:.7rem;">Titre</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Description</th>
                        <th style="padding:.9rem .7rem;text-align:left;border-top-right-radius:.7rem;">Date limite</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="border-bottom:1px solid #e2e8f0;transition:background .15s;">
                        <td style="padding:.8rem .7rem;"><?php echo e($assignment->title); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e($assignment->description); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e($assignment->deadline?->format('Y-m-d')); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\RND\Documents\zelvit\jefal\schoolWebsite-main\backend\resources\views/dashboard/student/assignments/index.blade.php ENDPATH**/ ?>