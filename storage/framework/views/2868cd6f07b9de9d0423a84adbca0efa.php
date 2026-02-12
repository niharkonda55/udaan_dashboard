<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title', 'Udaan Events Dashboard'); ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <style>
        :root {
            --bg-primary: #FFFFFF;
            --bg-secondary: #FBF5F0;
            --accent-primary: #724B77;
            --accent-secondary: #F6A02D;
            --text-dark: #1F2937;
            --text-muted: #6B7280;
        }
        
        body {
            background-color: var(--bg-primary);
            color: var(--text-dark);
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background-color: var(--bg-secondary);
            padding: 20px 0;
            border-right: 1px solid #E5E7EB;
        }
        
        .sidebar .nav-link {
            color: var(--text-muted);
            padding: 12px 20px;
            transition: all 0.2s;
            border-radius: 0;
        }
        
        .sidebar .nav-link:hover {
            color: var(--accent-primary);
            background-color: rgba(124, 58, 237, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: var(--accent-primary);
            background-color: rgba(124, 58, 237, 0.15);
            border-left: 3px solid var(--accent-primary);
        }
        
        .main-content {
            padding: 20px;
            background-color: var(--bg-primary);
        }
        
        .card {
            background-color: var(--bg-secondary);
            border: 1px solid #E5E7EB;
            border-radius: 8px;
        }
        
        .btn-primary {
            background-color: var(--accent-primary);
            border-color: var(--accent-primary);
        }
        
        .btn-primary:hover {
            opacity: 0.9;
        }
        
        .btn-secondary {
            background-color: var(--accent-secondary);
            border-color: var(--accent-secondary);
        }
        
        .btn-secondary:hover {
            opacity: 0.9;
        }

        .btn-outline-primary {
            color: var(--accent-primary);
            border-color: var(--accent-primary);
        }

        .btn-outline-primary:hover {
            color: #FFFFFF;
            background-color: var(--accent-primary);
            border-color: var(--accent-primary);
        }

        .btn-success,
        .btn-warning {
            background-color: var(--accent-secondary);
            border-color: var(--accent-secondary);
            color: #000000;
        }

        .btn-success:hover,
        .btn-warning:hover {
            opacity: 0.9;
        }
        
        .navbar-dark {
            background-color: var(--accent-primary) !important;
        }
        
        .status-badge {
            font-size: 0.85rem;
            padding: 0.35em 0.65em;
        }
        
        .badge.bg-primary {
            background-color: var(--accent-primary) !important;
        }
        
        .badge.bg-success {
            background-color: var(--accent-secondary) !important;
        }
        
        .badge.bg-info {
            background-color: var(--accent-secondary) !important;
        }

        .badge.bg-danger {
            background-color: var(--accent-secondary) !important;
        }
        
        .badge.bg-warning {
            background-color: var(--accent-secondary) !important;
            color: #000000;
        }
        
        .deadline-overdue {
            color: var(--accent-secondary);
            font-weight: 600;
        }
        
        .deadline-warning {
            color: var(--accent-secondary);
            font-weight: 500;
        }
        
        .text-accent-primary {
            color: var(--accent-primary) !important;
        }
        
        .text-accent-secondary {
            color: var(--accent-secondary) !important;
        }
        
        .icon-accent-primary {
            color: var(--accent-primary);
            opacity: 0.7;
        }
        
        .icon-accent-secondary {
            color: var(--accent-secondary);
            opacity: 0.7;
        }
        
        .btn-danger {
            background-color: var(--accent-secondary);
            border-color: var(--accent-secondary);
            color: #000000;
        }
        
        .btn-danger:hover {
            opacity: 0.9;
            background-color: var(--accent-secondary);
            border-color: var(--accent-secondary);
            color: #000000;
        }
        
        .alert-warning {
            background-color: rgba(246, 160, 45, 0.1);
            border-color: var(--accent-secondary);
            color: #000000;
        }
    </style>
    
    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">
                <i class="bi bi-camera-reels"></i> Udaan Events
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-text text-light me-3">
                            <i class="bi bi-person-circle"></i> <?php echo e(Auth::user()->name); ?> 
                            <span class="badge bg-secondary"><?php echo e(ucfirst(Auth::user()->role)); ?></span>
                        </span>
                    </li>
                    <li class="nav-item">
                        <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="btn btn-outline-light btn-sm">
                                <i class="bi bi-box-arrow-right"></i> Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-2 sidebar d-none d-md-block">
                <nav class="nav flex-column">
                    <?php if(Auth::user()->isAdmin()): ?>
                        <a class="nav-link <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('admin.dashboard')); ?>">
                            <i class="bi bi-speedometer2"></i> Dashboard
                        </a>
                        <a class="nav-link <?php echo e(request()->routeIs('admin.projects.create') ? 'active' : ''); ?>" href="<?php echo e(route('admin.projects.create')); ?>">
                            <i class="bi bi-plus-circle"></i> New Project
                        </a>
                        <a class="nav-link <?php echo e(request()->routeIs('admin.projects.completed') ? 'active' : ''); ?>" href="<?php echo e(route('admin.projects.completed')); ?>">
                            <i class="bi bi-check-circle"></i> Completed Projects
                        </a>
                        <a class="nav-link <?php echo e(request()->routeIs('admin.projects.trash.index') ? 'active' : ''); ?>" href="<?php echo e(route('admin.projects.trash.index')); ?>">
                            <i class="bi bi-trash"></i> Trash
                        </a>
                    <?php elseif(Auth::user()->isCameraman()): ?>
                        <a class="nav-link <?php echo e(request()->routeIs('cameraman.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('cameraman.dashboard')); ?>">
                            <i class="bi bi-speedometer2"></i> My Projects
                        </a>
                        <a class="nav-link <?php echo e(request()->routeIs('cameraman.projects.completed') ? 'active' : ''); ?>" href="<?php echo e(route('cameraman.projects.completed')); ?>">
                            <i class="bi bi-check-circle"></i> Completed Projects
                        </a>
                    <?php elseif(Auth::user()->isEditor()): ?>
                        <a class="nav-link <?php echo e(request()->routeIs('editor.dashboard') ? 'active' : ''); ?>" href="<?php echo e(route('editor.dashboard')); ?>">
                            <i class="bi bi-speedometer2"></i> My Projects
                        </a>
                        <a class="nav-link <?php echo e(request()->routeIs('editor.projects.completed') ? 'active' : ''); ?>" href="<?php echo e(route('editor.projects.completed')); ?>">
                            <i class="bi bi-check-circle"></i> Completed Projects
                        </a>
                    <?php endif; ?>
                </nav>
            </div>

            <!-- Main Content -->
            <main class="col-md-10 main-content">
                <?php if(session('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle"></i> <?php echo e(session('success')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle"></i> <?php echo e(session('error')); ?>

                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php if($errors->any()): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <?php echo $__env->yieldContent('content'); ?>
            </main>
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>

<?php /**PATH C:\Users\nihar\Desktop\udaan_events\resources\views/layouts/app.blade.php ENDPATH**/ ?>