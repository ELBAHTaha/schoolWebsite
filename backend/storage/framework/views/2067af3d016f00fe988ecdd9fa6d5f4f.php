<?php $__env->startSection('title', 'Accueil'); ?>
<?php $__env->startSection('content'); ?>
<header style="background:#0f766e;color:#fff;padding:1rem 0;">
    <div class="container" style="display:flex;justify-content:space-between;align-items:center;">
        <strong>JEFAL Prive</strong>
        <a href="<?php echo e(route('login')); ?>" class="btn" style="background:#fff;color:#0f766e;">Connexion</a>
    </div>
</header>
<main class="container" style="padding:2rem 0;">
    <div class="card">
        <h1>Institut de langues JEFAL Prive</h1>
        <p>Plateforme de gestion scolaire: cours, paiements, annonces et ressources PDF.</p>
        <p style="display:flex;gap:.5rem;flex-wrap:wrap;">
            <a class="btn" href="<?php echo e(route('visitor.stats')); ?>">Public statistics</a>
            <a class="btn" href="<?php echo e(route('visitor.contact')); ?>">Contact</a>
            <a class="btn" href="<?php echo e(route('visitor.create-account')); ?>">Create visitor account</a>
            <a class="btn" href="<?php echo e(route('visitor.pre-registration')); ?>">Pre-registration</a>
        </p>
    </div>
</main>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/public/home.blade.php ENDPATH**/ ?>