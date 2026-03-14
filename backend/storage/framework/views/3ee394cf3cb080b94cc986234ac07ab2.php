
<?php $__env->startSection('title', 'Professor Dashboard'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:1400px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <div>
            <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Tableau de bord professeur</h1>
            <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Bienvenue, <?php echo e($professor->name); ?></div>
        </div>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(220px,1fr));gap:1.5rem;margin-bottom:2.2rem;">
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['classes']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Classes assignÃ©es</small>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['assignments']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Devoirs</small>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['pdf_courses']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">Supports PDF</small>
        </div>
        <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;align-items:center;transition:box-shadow .2s;">
            <strong style="font-size:2.2rem;color:#2563eb;"><?php echo e($stats['schedules']); ?></strong>
            <small style="margin-top:.5rem;color:#64748b;font-size:1.1rem;">CrÃ©neaux</small>
        </div>
    </div>
    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;margin-bottom:2.2rem;">
        <h3 style="margin:0 0 1.2rem 0;font-size:1.25rem;color:#2563eb;font-weight:700;">Classes assignÃ©es</h3>
        <ul style="margin:0;padding-left:1.2rem;">
            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li style="margin-bottom:.4rem;color:#334155;font-size:1.08rem;"><?php echo e($class->name); ?> <?php if($class->room): ?> - <?php echo e($class->room->name); ?> <?php endif; ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;margin-bottom:2.2rem;">
        <h3 style="margin:0 0 1.2rem 0;font-size:1.25rem;color:#2563eb;font-weight:700;">Disponibilités (horaires de travail)</h3>
        <?php if($workingHours->isEmpty()): ?>
            <div style="color:#64748b;">Aucun créneau défini.</div>
        <?php else: ?>
            <table style="width:100%;border-collapse:separate;border-spacing:0 .6rem;font-size:1.02rem;">
                <thead>
                    <tr style="color:#64748b;text-transform:uppercase;font-size:.78rem;letter-spacing:.04em;">
                        <th style="text-align:left;padding:.4rem .6rem;">Jour</th>
                        <th style="text-align:left;padding:.4rem .6rem;">Début</th>
                        <th style="text-align:left;padding:.4rem .6rem;">Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $workingHours; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $wh): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr style="background:#f8fafc;border-radius:.6rem;">
                            <td style="padding:.6rem .6rem;"><?php echo e($wh->day_of_week); ?></td>
                            <td style="padding:.6rem .6rem;"><?php echo e($wh->starts_at); ?></td>
                            <td style="padding:.6rem .6rem;"><?php echo e($wh->ends_at); ?></td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div><div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;">
        <h3 style="margin:0 0 1.2rem 0;font-size:1.25rem;color:#2563eb;font-weight:700;">Emploi du temps</h3>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;min-width:600px;">
                <thead>
                    <tr style="background:#f1f5f9;color:#2563eb;font-size:1.08rem;">
                        <th style="padding:.9rem .7rem;text-align:left;border-top-left-radius:.7rem;">Classe</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Jour</th>
                        <th style="padding:.9rem .7rem;text-align:left;">DÃ©but</th>
                        <th style="padding:.9rem .7rem;text-align:left;border-top-right-radius:.7rem;">Fin</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="border-bottom:1px solid #e2e8f0;transition:background .15s;">
                        <td style="padding:.8rem .7rem;"><?php echo e($schedule->schoolClass?->name ?? '-'); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e($schedule->day_of_week); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e($schedule->starts_at); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e($schedule->ends_at); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/dashboard/professor/index.blade.php ENDPATH**/ ?>