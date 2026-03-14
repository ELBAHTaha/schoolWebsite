<?php $__env->startSection('title', 'Professor - Material Details'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<h1><?php echo e($material->title); ?></h1>

<div class="card" style="max-width:760px;">
    <p><strong>Classe:</strong> <?php echo e($material->schoolClass?->name ?? '-'); ?></p>
    <p><strong>Fichier:</strong> <a href="<?php echo e(asset('storage/'.$material->file_path)); ?>" target="_blank">Ouvrir le PDF</a></p>
    <div style="display:flex;gap:.6rem;">
        <a class="btn" href="<?php echo e(route('professor.materials.edit', $material)); ?>">Modifier</a>
        <a class="btn" href="<?php echo e(route('professor.materials.index')); ?>">Retour</a>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/dashboard/professor/materials/show.blade.php ENDPATH**/ ?>