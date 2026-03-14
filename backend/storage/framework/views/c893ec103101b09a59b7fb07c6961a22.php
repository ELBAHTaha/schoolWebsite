<?php $__env->startSection('title', 'Student - Course Content'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:1100px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Contenu de cours</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">PDFs et supports partagés par les professeurs</div>
    </div>

    <div style="padding:2rem 1.2rem;margin-bottom:2rem">
        <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">Supports (Materials)</h3>
        <?php if($materials->isEmpty()): ?>
            <div style="color:#64748b;">Aucun support disponible.</div>
        <?php else: ?>
            <table style="width:100%;border-collapse:separate;border-spacing:0;min-width:600px;">
                <thead>
                    <tr style="background:#f1f5f9;color:#2563eb;font-size:1.02rem;">
                        <th style="padding:.9rem .7rem;text-align:left;border-top-left-radius:.7rem;">Titre</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Classe</th>
                        <th style="padding:.9rem .7rem;text-align:left;border-top-right-radius:.7rem;">Télécharger</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="border-bottom:1px solid #e2e8f0;">
                        <td style="padding:.8rem .7rem;"><?php echo e($material->title); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e($material->schoolClass?->name ?? '-'); ?></td>
                        <td style="padding:.8rem .7rem;">
                            <a href="<?php echo e(asset('storage/'.$material->file_path)); ?>" target="_blank" rel="noopener">Télécharger</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div style="padding:2rem 1.2rem">
        <h3 style="margin:0 0 1rem 0;font-size:1.18rem;color:#2563eb;font-weight:700;">PDFs de cours</h3>
        <?php if($pdfs->isEmpty()): ?>
            <div style="color:#64748b;">Aucun PDF disponible.</div>
        <?php else: ?>
            <table style="width:100%;border-collapse:separate;border-spacing:0;min-width:600px;">
                <thead>
                    <tr style="background:#f1f5f9;color:#2563eb;font-size:1.02rem;">
                        <th style="padding:.9rem .7rem;text-align:left;border-top-left-radius:.7rem;">Titre</th>
                        <th style="padding:.9rem .7rem;text-align:left;">Classe</th>
                        <th style="padding:.9rem .7rem;text-align:left;border-top-right-radius:.7rem;">Télécharger</th>
                    </tr>
                </thead>
                <tbody>
                <?php $__currentLoopData = $pdfs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pdf): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr style="border-bottom:1px solid #e2e8f0;">
                        <td style="padding:.8rem .7rem;"><?php echo e($pdf->title); ?></td>
                        <td style="padding:.8rem .7rem;"><?php echo e($pdf->schoolClass?->name ?? '-'); ?></td>
                        <td style="padding:.8rem .7rem;">
                            <a href="<?php echo e(route('pdfs.download', $pdf)); ?>">Télécharger</a>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/dashboard/student/course_content/index.blade.php ENDPATH**/ ?>