

<?php $__env->startSection('title', 'Trash'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-trash"></i> Trash</h2>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Trashed Projects</h5>
    </div>
    <div class="card-body">
        <?php if($projects->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Cameraman</th>
                            <th>Editor</th>
                            <th>Deleted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($project->name); ?></strong>
                                    <?php if($project->description): ?>
                                        <br><small class="text-muted"><?php echo e(\Illuminate\Support\Str::limit($project->description, 50)); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="<?php echo e($project->priority_badge_class); ?>"><?php echo e(ucfirst($project->priority)); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary status-badge"><?php echo e($project->status_display); ?></span>
                                </td>
                                <td>
                                    <?php echo $project->cameraman ? $project->cameraman->name : '<span class="text-muted">Not assigned</span>'; ?>

                                </td>
                                <td>
                                    <?php echo $project->editor ? $project->editor->name : '<span class="text-muted">Not assigned</span>'; ?>

                                </td>
                                <td><?php echo e($project->deleted_at->format('M d, Y H:i')); ?></td>
                                <td>
                                    <form method="POST" action="<?php echo e(route('admin.projects.restore', $project->id)); ?>" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                <?php echo e($projects->links()); ?>

            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-3">No projects are currently in Trash.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\nihar\Desktop\udaan_events\resources\views/admin/projects/trash.blade.php ENDPATH**/ ?>