<?php $__env->startSection('content'); ?>
<div style="display:flex;min-height:100vh;">
    <?php echo $__env->make('components.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <div style="flex:1;display:flex;flex-direction:column;">
        <main style="width:100%;min-height:100vh;padding:0;margin:0;background:none;">
            <?php if(session('status')): ?>
                <div class="card" style="margin-bottom:1rem;border-left:4px solid #16a34a;"><?php echo e(session('status')); ?></div>
            <?php endif; ?>
            <?php if(session('error')): ?>
                <div class="card" style="margin-bottom:1rem;border-left:4px solid #dc2626;"><?php echo e(session('error')); ?></div>
            <?php endif; ?>
            <?php if($errors->any()): ?>
                <div class="card" style="margin-bottom:1rem;border-left:4px solid #dc2626;">
                    <strong>Validation errors:</strong>
                    <ul style="margin:.5rem 0 0 1rem;">
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>
            <?php echo $__env->yieldContent('dashboard-content'); ?>
        </main>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\RND\Documents\zelvit\jefal\schoolWebsite-main\backend\resources\views/layouts/dashboard.blade.php ENDPATH**/ ?>