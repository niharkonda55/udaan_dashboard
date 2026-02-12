

<?php $__env->startSection('title', '404 Not Found'); ?>

<?php $__env->startSection('content'); ?>
<div class="text-center py-5">
    <h1 class="display-3">404</h1>
    <h2 class="mb-4">Page Not Found</h2>
    <p class="text-muted mb-4">Sorry, the page you requested could not be found or may no longer exist.</p>

    <?php if(Auth::check()): ?>
        <?php
            $role = Auth::user()->role;
            $route = $role === 'admin'
                ? route('admin.dashboard')
                : ($role === 'editor'
                    ? route('editor.dashboard')
                    : ($role === 'cameraman' ? route('cameraman.dashboard') : route('dashboard')));
        ?>
        <a href="<?php echo e($route); ?>" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Back to Dashboard
        </a>
    <?php else: ?>
        <a href="<?php echo e(route('dashboard')); ?>" class="btn btn-primary">
            <i class="bi bi-arrow-left"></i> Back to Home
        </a>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\nihar\Desktop\udaan_events\resources\views/errors/404.blade.php ENDPATH**/ ?>