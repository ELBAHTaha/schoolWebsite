<?php $__env->startSection('title', 'Professor - PDF Courses'); ?>
<?php $__env->startSection('dashboard-content'); ?>
<div style="max-width:1200px;margin:0 auto;padding:2.5rem 2rem 2rem 2rem;background:#f8fafc;min-height:100vh;">
    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:2.2rem;flex-wrap:wrap;gap:1rem;">
        <h1 style="font-size:2.2rem;font-weight:800;margin:0;color:#1e293b;letter-spacing:-1px;">Supports de cours (PDF)</h1>
        <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.7rem 1.5rem;border-radius:.7rem;font-size:1.08rem;box-shadow:0 2px 8px 0 rgba(37,99,235,0.10);border:none;" href="<?php echo e(route('professor.materials.create')); ?>">Ajouter un PDF</a>
    </div>
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(320px,1fr));gap:1.5rem;">
        <?php $__empty_1 = true; $__currentLoopData = $materials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $material): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="background:#fff;border-radius:1rem;box-shadow:0 2px 16px 0 rgba(30,41,59,0.08);padding:2rem 1.2rem;display:flex;flex-direction:column;gap:1.1rem;justify-content:space-between;">
                <div>
                    <div style="font-size:1.18rem;font-weight:700;color:#2563eb;margin-bottom:.3rem;"><?php echo e($material->title); ?></div>
                    <div style="color:#64748b;font-size:1.05rem;">Classe : <span style="color:#334155;"><?php echo e($material->schoolClass?->name ?? '-'); ?></span></div>
                </div>
                <div style="display:flex;gap:1rem;flex-wrap:wrap;">
                    <a class="btn" style="background:#f1f5f9;color:#2563eb;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;" href="<?php echo e(route('professor.materials.show', $material)); ?>">Voir</a>
                    <a class="btn" style="background:linear-gradient(135deg,#2563eb,#0ea5e9);color:white;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;" href="<?php echo e(route('professor.materials.edit', $material)); ?>">Modifier</a>
                    <form method="POST" action="<?php echo e(route('professor.materials.destroy', $material)); ?>" style="display:inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button class="btn" style="background:#ef4444;color:white;font-weight:600;padding:.5rem 1.1rem;border-radius:.6rem;font-size:.98rem;border:none;" type="submit" onclick="return confirm('Supprimer ce support ?')">Supprimer</button>
                    </form>
                </div>
            </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <div style="color:#64748b;font-size:1.1rem;">Aucun support PDF trouvé.</div>
        <?php endif; ?>
    </div>
    <div style="margin-top:2.2rem;">
        <?php echo e($materials->links()); ?>

    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.dashboard', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\RND\Documents\zelvit\jefal\schoolWebsite-main\backend\resources\views/dashboard/professor/materials/index.blade.php ENDPATH**/ ?>