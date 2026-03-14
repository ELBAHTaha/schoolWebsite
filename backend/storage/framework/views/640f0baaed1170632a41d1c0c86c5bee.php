
<?php $__env->startSection('title', 'Professor - Schedule'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:900px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Mon emploi du temps</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Vue dÃ©taillÃ©e de vos crÃ©neaux</div>
    </div>
    <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:1.5rem 1.2rem;margin-bottom:1.6rem;">
        <h3 style="margin:0 0 1rem 0;font-size:1.1rem;color:#2563eb;font-weight:700;">Disponibilités (horaires de travail)</h3>
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
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:separate;border-spacing:0;min-width:600px;">
                <thead>
                    <tr style="background:#f1f5f9;color:#2563eb;font-size:1.08rem;">
                        <th style="padding:.9rem .7rem;text-align:left;border-top-left-radius:.7rem;">Classe</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Jour</th>
                        <th style="padding:.9rem .7rem;text-align:left;">DÃ©but</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Fin</th>
                        <th style="padding:.9rem .7rem;text-align:left;border-top-right-radius:.7rem;">Salle</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $schedules; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr style="border-bottom:1px solid #e2e8f0;transition:background .15s;">
                        <td style="padding:.8rem .7rem;"><?php echo e($schedule->schoolClass?->name ?? '-'); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e($schedule->day_of_week); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e($schedule->starts_at); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e($schedule->ends_at); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e($schedule->location ?? $schedule->schoolClass?->room?->name ?? '-'); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr><td colspan="5" style="padding:.8rem .7rem;text-align:center;color:#64748b;">Aucun crÃ©neau assignÃ©.</td></tr>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div style="margin-top:1.5rem;">
            <?php echo e($schedules->links()); ?>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/dashboard/professor/schedules/index.blade.php ENDPATH**/ ?>