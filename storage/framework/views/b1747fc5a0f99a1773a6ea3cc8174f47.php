

<?php $__env->startSection('title', 'Create New Project'); ?>

<?php $__env->startSection('content'); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-plus-circle"></i> Create New Project</h2>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?php echo e(route('admin.projects.store')); ?>">
            <?php echo csrf_field(); ?>

            <div class="mb-3">
                <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="<?php echo e(old('name')); ?>" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3"><?php echo e(old('description')); ?></textarea>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                <select class="form-select" id="priority" name="priority" required>
                    <option value="low" <?php echo e(old('priority') == 'low' ? 'selected' : ''); ?>>Low</option>
                    <option value="medium" <?php echo e(old('priority') == 'medium' ? 'selected' : ''); ?>>Medium</option>
                    <option value="high" <?php echo e(old('priority') == 'high' ? 'selected' : ''); ?>>High</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="cameraman_id" class="form-label">Cameraman <span class="text-danger">*</span></label>
                <select class="form-select" id="cameraman_id" name="cameraman_id" required>
                    <option value="">Select Cameraman</option>
                    <?php $__currentLoopData = $idleCameramen; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cameraman): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($cameraman->id); ?>" <?php echo e(old('cameraman_id') == $cameraman->id ? 'selected' : ''); ?>>
                            <?php echo e($cameraman->name); ?> (Idle)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <small class="form-text text-muted">Only idle cameramen are shown</small>
            </div>

            <div class="mb-3">
                <label for="editor_id" class="form-label">Editor<span class="text-danger">*</span></label>
                <select class="form-select" id="editor_id" name="editor_id" required>
                    <option value="">Select Editor</option>
                    <?php $__currentLoopData = $idleEditors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $editor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($editor->id); ?>" <?php echo e(old('editor_id') == $editor->id ? 'selected' : ''); ?>>
                            <?php echo e($editor->name); ?> (Idle)
                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <small class="form-text text-muted">Only idle editors are shown</small>
            </div>

            <hr class="my-4">
            <h5 class="mb-3">Deadlines (Optional)</h5>

            <div class="mb-3">
                <label for="final_deadline" class="form-label">Final Deadline</label>
                <input type="date" class="form-control" id="final_deadline" name="final_deadline" value="<?php echo e(old('final_deadline')); ?>">
                <small class="form-text text-muted">Overall project deadline</small>
            </div>

            <div class="mb-3">
                <label for="cameraman_deadline" class="form-label">Cameraman Deadline</label>
                <input type="date" class="form-control" id="cameraman_deadline" name="cameraman_deadline" value="<?php echo e(old('cameraman_deadline')); ?>">
                <small class="form-text text-muted">Deadline for cameraman to complete shooting</small>
            </div>

            <div class="mb-3">
                <label for="editor_deadline" class="form-label">Editor Deadline</label>
                <input type="date" class="form-control" id="editor_deadline" name="editor_deadline" value="<?php echo e(old('editor_deadline')); ?>">
                <small class="form-text text-muted">Deadline for editor to complete editing</small>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Create Project
                </button>
            </div>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\nihar\Desktop\udaan_events\resources\views/admin/projects/create.blade.php ENDPATH**/ ?>