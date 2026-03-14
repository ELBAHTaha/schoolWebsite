<?php $__env->startSection('title', 'Student - Profile'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:500px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Mon profil</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Modifier mes informations personnelles</div>
    </div>
    <form method="POST" action="<?php echo e(route('student.profile.update')); ?>" style="padding:2rem 1.2rem;display:flex;flex-direction:column;gap:1.2rem">
        <?php echo csrf_field(); ?> <?php echo method_field('PUT'); ?>
        <label style="font-weight:600;color:#2563eb;">Nom</label>
        <input name="name" value="<?php echo e($student->name); ?>" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
        <label style="font-weight:600;color:#2563eb;">Téléphone</label>
        <input name="phone" value="<?php echo e($student->phone); ?>" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
        <button class="btn" type="submit" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;">Mettre à jour</button>
    </form>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\dell\Desktop\schoolWebsite-main\backend\resources\views/dashboard/student/profile/edit.blade.php ENDPATH**/ ?>