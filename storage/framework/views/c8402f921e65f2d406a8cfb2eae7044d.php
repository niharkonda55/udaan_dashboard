

<?php $__env->startSection('title', $project->name); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-folder"></i> <?php echo e($project->name); ?></h2>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="row">
    <!-- Project Details -->
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-info-circle"></i> Project Details</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Name:</th>
                        <td><?php echo e($project->name); ?></td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td><?php echo e($project->description ?: 'N/A'); ?></td>
                    </tr>
                    <tr>
                        <th>Priority:</th>
                        <td><span class="<?php echo e($project->priority_badge_class); ?>"><?php echo e(ucfirst($project->priority)); ?></span></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><span class="badge bg-secondary status-badge"><?php echo e($project->status_display); ?></span></td>
                    </tr>
                    <tr>
                        <th>Cameraman:</th>
                        <td>
                            <?php if($project->cameraman): ?>
                                <?php echo e($project->cameraman->name); ?>

                            <?php else: ?>
                                <span class="text-muted">Not assigned</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Editor:</th>
                        <td>
                            <?php if($project->editor): ?>
                                <?php echo e($project->editor->name); ?>

                            <?php else: ?>
                                <span class="text-muted">Not assigned</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Final Deadline:</th>
                        <td>
                            <?php if($project->final_deadline): ?>
                                <span class="<?php echo e($project->isFinalDeadlineOverdue() ? 'deadline-overdue' : ''); ?>">
                                    <i class="bi bi-calendar"></i> <?php echo e($project->final_deadline->format('M d, Y')); ?>

                                    <?php if($project->isFinalDeadlineOverdue()): ?>
                                        <span class="badge bg-warning ms-2">Overdue</span>
                                    <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">Not set</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Cameraman Deadline:</th>
                        <td>
                            <?php if($project->cameraman_deadline): ?>
                                <span class="<?php echo e($project->isCameramanDeadlineOverdue() ? 'deadline-overdue' : ''); ?>">
                                    <i class="bi bi-calendar"></i> <?php echo e($project->cameraman_deadline->format('M d, Y')); ?>

                                    <?php if($project->isCameramanDeadlineOverdue()): ?>
                                        <span class="badge bg-warning ms-2">Overdue</span>
                                    <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">Not set</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Editor Deadline:</th>
                        <td>
                            <?php if($project->editor_deadline): ?>
                                <span class="<?php echo e($project->isEditorDeadlineOverdue() ? 'deadline-overdue' : ''); ?>">
                                    <i class="bi bi-calendar"></i> <?php echo e($project->editor_deadline->format('M d, Y')); ?>

                                    <?php if($project->isEditorDeadlineOverdue()): ?>
                                        <span class="badge bg-warning ms-2">Overdue</span>
                                    <?php endif; ?>
                                </span>
                            <?php else: ?>
                                <span class="text-muted">Not set</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td><?php echo e($project->created_at->format('M d, Y H:i')); ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Activity Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history"></i> Activity Timeline</h5>
            </div>
            <div class="card-body">
                <?php if($project->activityLogs->count() > 0): ?>
                    <div class="timeline">
                        <?php $__currentLoopData = $project->activityLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong><?php echo e($log->user->name); ?></strong>
                                        <span class="text-muted">(<?php echo e(ucfirst($log->user->role)); ?>)</span>
                                        <br>
                                        <small class="text-muted"><?php echo e($log->description); ?></small>
                                    </div>
                                    <small class="text-muted"><?php echo e($log->created_at->format('M d, Y H:i')); ?></small>
                                </div>
                                <?php if($log->old_status && $log->new_status): ?>
                                    <div class="mt-2">
                                        <span class="badge bg-secondary"><?php echo e(ucfirst(str_replace('_', ' ', $log->old_status))); ?></span>
                                        <i class="bi bi-arrow-right mx-2"></i>
                                        <span class="badge bg-primary"><?php echo e(ucfirst(str_replace('_', ' ', $log->new_status))); ?></span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-muted">No activity logged yet.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Actions Sidebar -->
    <div class="col-md-4">
        <!-- Assignment -->
        <!-- <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-people"></i> Assignment</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.projects.updateAssignment', $project)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="cameraman_id" class="form-label">Cameraman</label>
                        <select class="form-select" id="cameraman_id" name="cameraman_id">
                            <option value="">None</option>
                            <?php $__currentLoopData = $cameramen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cameraman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cameraman->id); ?>" <?php echo e($project->cameraman_id == $cameraman->id ? 'selected' : ''); ?>>
                                    <?php echo e($cameraman->name); ?> <?php echo e($cameraman->isIdle() ? '(Idle)' : ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editor_id" class="form-label">Editor</label>
                        <select class="form-select" id="editor_id" name="editor_id">
                            <option value="">None</option>
                            <?php $__currentLoopData = $editors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($editor->id); ?>" <?php echo e($project->editor_id == $editor->id ? 'selected' : ''); ?>>
                                    <?php echo e($editor->name); ?> <?php echo e($editor->isIdle() ? '(Idle)' : ''); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Update Assignment
                    </button>
                </form>
            </div>
        </div> -->
        <form method="POST" action="<?php echo e(route('admin.projects.updateAssignment', $project)); ?>">
            <?php echo csrf_field(); ?>

            <input type="hidden" name="update_type" id="update_type">

            <!-- Cameraman -->
            <div class="mb-3">
                <label class="form-label">Cameraman</label>
                <select class="form-select" name="cameraman_id">
                    <option value="">None</option>
                    <?php $__currentLoopData = $cameramen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cameraman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cameraman->id); ?>" <?php echo e($project->cameraman_id == $cameraman->id ? 'selected' : ''); ?>>
                            <?php echo e($cameraman->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <button type="submit"
                        class="btn btn-warning w-100 mt-2"
                        onclick="document.getElementById('update_type').value='cameraman'"
                        <?php echo e($project->shooting_started_at ? 'disabled' : ''); ?>>
                    Update Cameraman
                </button>
            </div>

            <!-- Editor -->
            <div class="mb-3">
                <label class="form-label">Editor</label>
                <select class="form-select" name="editor_id">
                    <option value="">None</option>
                    <?php $__currentLoopData = $editors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($editor->id); ?>" <?php echo e($project->editor_id == $editor->id ? 'selected' : ''); ?>>
                            <?php echo e($editor->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>

                <button type="submit"
                        class="btn btn-primary w-100 mt-2"
                        onclick="document.getElementById('update_type').value='editor'">
                    Update Editor
                </button>
            </div>
        </form>


        <!-- Project Actions -->
        <?php if($project->status === 'review'): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-check-circle"></i> Review Actions</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('admin.projects.approve', $project)); ?>" class="mb-2">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle"></i> Approve Project
                        </button>
                    </form>
                    <form method="POST" action="<?php echo e(route('admin.projects.rework', $project)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-arrow-counterclockwise"></i> Send for Rework
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Update Deadlines (Admin Only) -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar"></i> Update Deadlines</h5>
            </div>
            <div class="card-body">
                <?php if($project->status === 'completed'): ?>
                    <p class="text-muted mb-0">Deadlines cannot be updated for completed projects.</p>
                <?php else: ?>
                    <form method="POST" action="<?php echo e(route('admin.projects.deadlines.update', $project)); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="mb-3">
                            <label for="final_deadline" class="form-label">Final Deadline</label>
                            <input
                                type="date"
                                class="form-control"
                                id="final_deadline"
                                name="final_deadline"
                                value="<?php echo e(old('final_deadline', optional($project->final_deadline)->format('Y-m-d'))); ?>"
                            >
                        </div>
                        <div class="mb-3">
                            <label for="cameraman_deadline" class="form-label">Cameraman Deadline</label>
                            <input
                                type="date"
                                class="form-control"
                                id="cameraman_deadline"
                                name="cameraman_deadline"
                                value="<?php echo e(old('cameraman_deadline', optional($project->cameraman_deadline)->format('Y-m-d'))); ?>"
                            >
                        </div>
                        <div class="mb-3">
                            <label for="editor_deadline" class="form-label">Editor Deadline</label>
                            <input
                                type="date"
                                class="form-control"
                                id="editor_deadline"
                                name="editor_deadline"
                                value="<?php echo e(old('editor_deadline', optional($project->editor_deadline)->format('Y-m-d'))); ?>"
                            >
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Save Deadlines
                        </button>
                        <small class="text-muted d-block mt-2">
                            Final deadline must be today or later. Cameraman/Editor deadlines must be on or before final deadline. Editor deadline must be after or equal to cameraman deadline.
                        </small>
                    </form>
                <?php endif; ?>
            </div>
        </div>

        <?php if($project->status === 'approved'): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-check-all"></i> Complete Project</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="<?php echo e(route('admin.projects.complete', $project)); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-all"></i> Mark as Completed
                        </button>
                    </form>
                </div>
            </div>
        <?php endif; ?>

        <!-- Manual Status Change (Admin Only) -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Change Status</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.projects.changeStatus', $project)); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="created" <?php echo e($project->status === 'created' ? 'selected' : ''); ?>>Created</option>
                            <option value="assigned" <?php echo e($project->status === 'assigned' ? 'selected' : ''); ?>>Assigned</option>
                            <option value="shooting" <?php echo e($project->status === 'shooting' ? 'selected' : ''); ?>>Shooting</option>
                            <option value="raw_uploaded" <?php echo e($project->status === 'raw_uploaded' ? 'selected' : ''); ?>>Raw Uploaded</option>
                            <option value="editing" <?php echo e($project->status === 'editing' ? 'selected' : ''); ?>>Editing</option>
                            <option value="review" <?php echo e($project->status === 'review' ? 'selected' : ''); ?>>Review</option>
                            <option value="approved" <?php echo e($project->status === 'approved' ? 'selected' : ''); ?>>Approved</option>
                            <option value="rework" <?php echo e($project->status === 'rework' ? 'selected' : ''); ?>>Rework</option>
                            <option value="completed" <?php echo e($project->status === 'completed' ? 'selected' : ''); ?>>Completed</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-secondary w-100">
                        <i class="bi bi-arrow-repeat"></i> Update Status
                    </button>
                </form>
            </div>
        </div>

        <!-- Trash Project -->
        <div class="card mb-4">
            <div class="card-header" style="background-color: var(--accent-secondary); color: #000000;">
                <h5 class="mb-0"><i class="bi bi-trash"></i> Danger Zone</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="<?php echo e(route('admin.projects.trash', $project)); ?>" onsubmit="return confirm('Are you sure you want to move this project to trash?');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Move to Trash
                    </button>
                </form>
                <small class="text-muted">Projects in trash can be restored later.</small>
            </div>
        </div>

        <!-- Raw Media Info -->
        <?php if($project->raw_media_method): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-camera-video"></i> Raw Media</h5>
                </div>
                <div class="card-body">
                    <p><strong>Method:</strong> <?php echo e(ucfirst($project->raw_media_method)); ?></p>
                    <?php if($project->raw_media_link): ?>
                        <p><strong>Link:</strong> <a href="<?php echo e($project->raw_media_link); ?>" target="_blank"><?php echo e($project->raw_media_link); ?></a></p>
                    <?php endif; ?>
                    <?php if($project->cameraman_notes): ?>
                        <p><strong>Notes:</strong> <?php echo e($project->cameraman_notes); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Final Delivery Info -->
        <?php if($project->final_delivery_method): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-check"></i> Final Delivery</h5>
                </div>
                <div class="card-body">
                    <p><strong>Method:</strong> <?php echo e(ucfirst($project->final_delivery_method)); ?></p>
                    <?php if($project->final_delivery_link): ?>
                        <p><strong>Link:</strong> <a href="<?php echo e($project->final_delivery_link); ?>" target="_blank"><?php echo e($project->final_delivery_link); ?></a></p>
                    <?php endif; ?>
                    <?php if($project->editor_notes): ?>
                        <p><strong>Notes:</strong> <?php echo e($project->editor_notes); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\nihar\Desktop\udaan_events\resources\views/admin/projects/show.blade.php ENDPATH**/ ?>