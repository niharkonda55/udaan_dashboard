@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
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
                        <h2 class="mb-0 text-accent-primary">{{ $totalProjects }}</h2>
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
                        <h2 class="mb-0 text-accent-secondary">{{ $activeProjects }}</h2>
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
                        <h2 class="mb-0 text-accent-primary">{{ $completedProjects }}</h2>
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
                        <h2 class="mb-0 text-accent-secondary">{{ $idleCameramen->count() + $idleEditors->count() }}</h2>
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
                            <h3 class="text-accent-primary">{{ $completedProjects }}</h3>
                            <p class="text-muted mb-0">Total Completed</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="text-center">
                            <a href="{{ route('admin.projects.completed') }}" class="btn btn-outline-primary">
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
@if($idleCameramen->count() > 0 || $idleEditors->count() > 0)
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-camera-video"></i> Idle Cameramen ({{ $idleCameramen->count() }})</h6>
            </div>
            <div class="card-body">
                @if($idleCameramen->count() > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($idleCameramen as $cameraman)
                            <li class="mb-2">
                                <i class="bi bi-person-circle"></i> {{ $cameraman->name }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No idle cameramen</p>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-scissors"></i> Idle Editors ({{ $idleEditors->count() }})</h6>
            </div>
            <div class="card-body">
                @if($idleEditors->count() > 0)
                    <ul class="list-unstyled mb-0">
                        @foreach($idleEditors as $editor)
                            <li class="mb-2">
                                <i class="bi bi-person-circle"></i> {{ $editor->name }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted mb-0">No idle editors</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Actions -->
<div class="mb-3">
    <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Create New Project
    </a>
</div>

<!-- Projects Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Projects</h5>
    </div>
    <div class="card-body">
        @if($projects->count() > 0)
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
                        @foreach($projects as $project)
                            <tr>
                                <td>
                                    <strong>{{ $project->name }}</strong>
                                    @if($project->description)
                                        <br><small class="text-muted">{{ \Illuminate\Support\Str::limit($project->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="{{ $project->priority_badge_class }}">{{ ucfirst($project->priority) }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary status-badge">{{ $project->status_display }}</span>
                                </td>
                                <td>
                                    {!! $project->cameraman ? $project->cameraman->name : '<span class="text-muted">Not assigned</span>' !!}
                                    @if($project->cameraman_deadline)
                                        <br><small class="{{ $project->isCameramanDeadlineOverdue() ? 'deadline-overdue' : 'text-muted' }}">
                                            <i class="bi bi-calendar"></i> {{ $project->cameraman_deadline->format('M d, Y') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    {!! $project->editor ? $project->editor->name : '<span class="text-muted">Not assigned</span>' !!}
                                    @if($project->editor_deadline)
                                        <br><small class="{{ $project->isEditorDeadlineOverdue() ? 'deadline-overdue' : 'text-muted' }}">
                                            <i class="bi bi-calendar"></i> {{ $project->editor_deadline->format('M d, Y') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    {{ $project->created_at->format('M d, Y') }}
                                    @if($project->final_deadline)
                                        <br><small class="{{ $project->isFinalDeadlineOverdue() ? 'deadline-overdue' : 'text-muted' }}">
                                            <i class="bi bi-flag"></i> Final: {{ $project->final_deadline->format('M d, Y') }}
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.projects.show', $project) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $projects->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted"></i>
                <p class="text-muted mt-3">No projects found. Create your first project!</p>
                <a href="{{ route('admin.projects.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle"></i> Create Project
                </a>
            </div>
        @endif
    </div>
</div>
@endsection

