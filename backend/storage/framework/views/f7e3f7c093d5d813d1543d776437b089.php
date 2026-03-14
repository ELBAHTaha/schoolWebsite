<?php $__env->startSection('title', 'Connexion'); ?>
<?php $__env->startSection('content'); ?>
<div style="min-height:100vh;display:grid;place-items:center;padding:1rem;">
    <form method="POST" action="<?php echo e(route('login.attempt')); ?>" class="card" style="width:min(420px,100%);">
        <?php echo csrf_field(); ?>
        <h1 style="margin-top:0;">Connexion</h1>

        <label for="email">Email</label>
        <input id="email" name="email" type="email" value="<?php echo e(old('email')); ?>" required style="width:100%;padding:.6rem;margin:.4rem 0 1rem;border:1px solid #d1d5db;border-radius:.4rem;">

        <label for="password">Mot de passe</label>
        <input id="password" name="password" type="password" required style="width:100%;padding:.6rem;margin:.4rem 0 1rem;border:1px solid #d1d5db;border-radius:.4rem;">

        <label style="display:flex;align-items:center;gap:.5rem;margin-bottom:1rem;">
            <input type="checkbox" name="remember"> Se souvenir de moi
        </label>

        <?php if($errors->any()): ?>
            <div style="color:#b91c1c;margin-bottom:1rem;"><?php echo e($errors->first()); ?></div>
        <?php endif; ?>

        <button class="btn" type="submit" style="width:100%;">Se connecter</button>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/auth/login.blade.php ENDPATH**/ ?>