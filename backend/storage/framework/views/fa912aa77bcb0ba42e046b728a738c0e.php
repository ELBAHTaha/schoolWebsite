<?php $__env->startSection('title', 'Secretary - Create Student'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:720px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="margin-bottom:2.2rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Créer un étudiant</h1>
        <div style="color:#64748b;font-size:1.1rem;margin-top:.2rem;">Formulaire d'ajout</div>
    </div>
    <form method="POST" action="<?php echo e(route('secretary.students.store')); ?>" style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;gap:1.2rem;">
        <?php echo csrf_field(); ?>
        <label style="font-weight:600;color:#2563eb;">Nom</label>
        <input name="name" value="<?php echo e(old('name')); ?>" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Email</label>
        <input name="email" type="email" value="<?php echo e(old('email')); ?>" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Mot de passe</label>
        <input name="password" type="password" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Téléphone</label>
        <input name="phone" value="<?php echo e(old('phone')); ?>" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Classe</label>
        <select name="class_id" style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            <option value="">Aucune</option>
            <?php $__currentLoopData = $classes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($class->id); ?>" <?php if(old('class_id') == $class->id): echo 'selected'; endif; ?>><?php echo e($class->name); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>

        <label style="font-weight:600;color:#2563eb;">Solde du compte</label>
        <input name="account_balance" type="number" min="0" step="0.01" value="<?php echo e(old('account_balance', '0')); ?>" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">

        <label style="font-weight:600;color:#2563eb;">Statut paiement</label>
        <select name="payment_status" required style="width:100%;padding:.7rem;border-radius:.6rem;border:1px solid #e2e8f0;">
            <option value="pending" <?php if(old('payment_status') === 'pending'): echo 'selected'; endif; ?>>pending</option>
            <option value="paid" <?php if(old('payment_status') === 'paid'): echo 'selected'; endif; ?>>paid</option>
            <option value="late" <?php if(old('payment_status') === 'late'): echo 'selected'; endif; ?>>late</option>
        </select>

        <div style="display:flex;gap:1rem;margin-top:1.2rem;">
            <button class="btn" type="submit" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;">Enregistrer</button>
            <a class="btn" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="<?php echo e(route('secretary.students.index')); ?>">Annuler</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\RND\Documents\zelvit\jefal\schoolWebsite-main\backend\resources\views/dashboard/secretary/students/create.blade.php ENDPATH**/ ?>