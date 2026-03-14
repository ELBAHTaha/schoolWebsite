<?php $__env->startSection('title', 'Professor - Profile'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<h1>Modifier mon profil</h1>
<form method="POST" action="<?php echo e(route('professor.profile.update')); ?>" class="card">
    <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
    <label>Name</label>
    <input name="name" value="<?php echo e($professor->name); ?>" required>
    <label>Phone</label>
    <input name="phone" value="<?php echo e($professor->phone); ?>">
    <label>Nouveau mot de passe (optionnel)</label>
    <input name="password" type="password">
    <label>Confirmation du mot de passe</label>
    <input name="password_confirmation" type="password">
    <div style="display:flex;gap:.6rem;">
        <button class="btn" type="submit">Mettre a jour</button>
        <a class="btn" href="<?php echo e(route('professor.profile.show')); ?>">Retour</a>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\RND\Documents\zelvit\jefal\schoolWebsite-main\backend\resources\views/dashboard/professor/profile/edit.blade.php ENDPATH**/ ?>