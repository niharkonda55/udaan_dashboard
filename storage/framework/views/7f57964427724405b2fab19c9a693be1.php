

<?php $__env->startSection('title', 'Admin Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-speedometer2"></i> Admin Dashboard</h2>
</div>

<!-- Overview Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Total Projects</h6>
                        <h2 class="mb-0 text-accent-primary"><?php echo e($totalProjects); ?></h2>
                    </div>
                    <i class="bi bi-folder fs-1 icon-accent-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Active Projects</h6>
                        <h2 class="mb-0 text-accent-secondary"><?php echo e($activeProjects); ?></h2>
                    </div>
                    <i class="bi bi-play-circle fs-1 icon-accent-secondary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Completed Projects</h6>
                        <h2 class="mb-0 text-accent-primary"><?php echo e($completedProjects); ?></h2>
                    </div>
                    <i class="bi bi-check-circle fs-1 icon-accent-primary"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6 class="card-subtitle mb-2 text-muted">Idle Users</h6>
                        <h2 class="mb-0 text-accent-secondary"><?php echo e($idleCameramen->count() + $idleEditors->count()); ?></h2>
                    </div>
                    <i class="bi bi-person-circle fs-1 icon-accent-secondary"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Completed Projects Summary
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-check-circle"></i> Completed Projects Summary</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center">
                            <h3 class="text-accent-primary"><?php echo e($completedProjects); ?></h3>
                            <p class="text-muted mb-0">Total Completed</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <a href="<?php echo e(route('admin.projects.completed')); ?>" class="btn btn-outline-primary">
                                <i class="bi bi-list-ul"></i> View All Completed
                            </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <small class="text-muted">Projects marked as completed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> -->

<!-- Idle Users Details -->
<?php if($idleCameramen->count() > 0 || $idleEditors->count() > 0): ?>
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-camera-video"></i> Idle Cameramen (<?php echo e($idleCameramen->count()); ?>)</h6>
            </div>
            <div class="card-body">
                <?php if($idleCameramen->count() > 0): ?>
                    <ul class="list-unstyled mb-0">
                        <?php $__currentLoopData = $idleCameramen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cameraman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-2">
                                <i class="bi bi-person-circle"></i> <?php echo e($cameraman->name); ?>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">No idle cameramen</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-scissors"></i> Idle Editors (<?php echo e($idleEditors->count()); ?>)</h6>
            </div>
            <div class="card-body">
                <?php if($idleEditors->count() > 0): ?>
                    <ul class="list-unstyled mb-0">
                        <?php $__currentLoopData = $idleEditors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li class="mb-2">
                                <i class="bi bi-person-circle"></i> <?php echo e($editor->name); ?>

                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                <?php else: ?>
                    <p class="text-muted mb-0">No idle editors</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Actions -->
<div class="mb-3">
    <a href="<?php echo e(route('admin.projects.create')); ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create New Project
    </a>
</div>

<!-- Projects Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Projects</h5>
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
                            <th>Created</th>
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

                                    <?php if($project->cameraman_deadline): ?>
                                        <br><small class="<?php echo e($project->isCameramanDeadlineOverdue() ? 'deadline-overdue' : 'text-muted'); ?>">
                                            <i class="bi bi-calendar"></i> <?php echo e($project->cameraman_deadline->format('M d, Y')); ?>

                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo $project->editor ? $project->editor->name : '<span class="text-muted">Not assigned</span>'; ?>

                                    <?php if($project->editor_deadline): ?>
                                        <br><small class="<?php echo e($project->isEditorDeadlineOverdue() ? 'deadline-overdue' : 'text-muted'); ?>">
                                            <i class="bi bi-calendar"></i> <?php echo e($project->editor_deadline->format('M d, Y')); ?>

                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo e($project->created_at->format('M d, Y')); ?>

                                    <?php if($project->final_deadline): ?>
                                        <br><small class="<?php echo e($project->isFinalDeadlineOverdue() ? 'deadline-overdue' : 'text-muted'); ?>">
                                            <i class="bi bi-flag"></i> Final: <?php echo e($project->final_deadline->format('M d, Y')); ?>

                                        </small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?php echo e(route('admin.projects.show', $project)); ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
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
                <p class="text-muted mt-3">No projects found. Create your first project!</p>
                <a href="<?php echo e(route('admin.projects.create')); ?>" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create Project
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\nihar\Desktop\udaan_events\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>