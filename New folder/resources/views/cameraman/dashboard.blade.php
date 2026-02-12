@extends('layouts.app')

@section('title', 'Cameraman Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-camera-video"></i> My Projects</h2>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Assigned Projects</h5>
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
                            <th>Editor</th>
                            <th>Deadline</th>
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
                                    {!! $project->editor ? $project->editor->name : '<span class="text-muted">Not assigned</span>' !!}
                                </td>
                                <td>
                                    @if($project->cameraman_deadline)
                                        <span class="{{ $project->isCameramanDeadlineOverdue() ? 'deadline-overdue' : 'text-muted' }}">
                                            {{ $project->cameraman_deadline->format('M d, Y') }}
                                        </span>
                                    @else
                                        <span class="text-muted">Not set</span>
                                    @endif
                                </td>
                                <td>{{ $project->created_at->format('M d, Y') }}</td>
                                <td>
                                    <a href="{{ route('cameraman.projects.show', $project) }}" class="btn btn-sm btn-outline-primary">
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
                <p class="text-muted mt-3">No projects assigned to you yet.</p>
            </div>
        @endif
    </div>
</div>
@endsection

