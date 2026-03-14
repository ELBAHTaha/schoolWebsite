<?php $__env->startSection('title', 'Professor - Assignment Details'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<h1><?php echo e($assignment->title); ?></h1>

<div class="card" style="max-width:800px;">
    <p><strong>Classe:</strong> <?php echo e($assignment->schoolClass?->name ?? '-'); ?></p>
    <p><strong>Date limite:</strong> <?php echo e(($assignment->due_date ?? $assignment->deadline)?->format('Y-m-d')); ?></p>
    <?php if($assignment->document_path): ?>
        <p><strong>Document:</strong> <a href="<?php echo e(asset('storage/'.$assignment->document_path)); ?>" target="_blank" rel="noopener">Télécharger</a></p>
    <?php endif; ?>
    <p><strong>Description:</strong></p>
    <p><?php echo e($assignment->description ?? '-'); ?></p>
    <div style="display:flex;gap:.6rem;">
        <a class="btn" href="<?php echo e(route('professor.assignments.edit', $assignment)); ?>">Modifier</a>
        <a class="btn" href="<?php echo e(route('professor.assignments.index')); ?>">Retour</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/dashboard/professor/assignments/show.blade.php ENDPATH**/ ?>