@extends('layouts.app')

@section('title', 'Trash')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-trash"></i> Trash</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0"><i class="bi bi-list-ul"></i> Trashed Projects</h5>
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
                            <th>Deleted</th>
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
                                </td>
                                <td>
                                    {!! $project->editor ? $project->editor->name : '<span class="text-muted">Not assigned</span>' !!}
                                </td>
                                <td>{{ $project->deleted_at->format('M d, Y H:i') }}</td>
                                <td>
                                    <form method="POST" action="{{ route('admin.projects.restore', $project->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="bi bi-arrow-counterclockwise"></i> Restore
                                        </button>
                                    </form>
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
                <p class="text-muted mt-3">No projects are currently in Trash.</p>
            </div>
        @endif
    </div>
</div>
@endsection

