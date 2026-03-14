<?php $__env->startSection('title', 'Professor - Profile'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<h1>Mon profil</h1>

<div class="card" style="max-width:700px;">
    <p><strong>Nom:</strong> <?php echo e($professor->name); ?></p>
    <p><strong>Email:</strong> <?php echo e($professor->email); ?></p>
    <p><strong>Telephone:</strong> <?php echo e($professor->phone ?? '-'); ?></p>
    <a class="btn" href="<?php echo e(route('professor.profile.edit')); ?>">Modifier</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\RND\Documents\zelvit\jefal\schoolWebsite-main\backend\resources\views/dashboard/professor/profile/show.blade.php ENDPATH**/ ?>