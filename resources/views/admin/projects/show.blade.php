@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-folder"></i> {{ $project->name }}</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
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
                        <td>{{ $project->name }}</td>
                    </tr>
                    <tr>
                        <th>Description:</th>
                        <td>{{ $project->description ?: 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Priority:</th>
                        <td><span class="{{ $project->priority_badge_class }}">{{ ucfirst($project->priority) }}</span></td>
                    </tr>
                    <tr>
                        <th>Status:</th>
                        <td><span class="badge bg-secondary status-badge">{{ $project->status_display }}</span></td>
                    </tr>
                    <tr>
                        <th>Cameraman:</th>
                        <td>
                            @if($project->cameraman)
                                {{ $project->cameraman->name }}
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Editor:</th>
                        <td>
                            @if($project->editor)
                                {{ $project->editor->name }}
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Final Deadline:</th>
                        <td>
                            @if($project->final_deadline)
                                <span class="{{ $project->isFinalDeadlineOverdue() ? 'deadline-overdue' : '' }}">
                                    <i class="bi bi-calendar"></i> {{ $project->final_deadline->format('M d, Y') }}
                                    @if($project->isFinalDeadlineOverdue())
                                        <span class="badge bg-warning ms-2">Overdue</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Cameraman Deadline:</th>
                        <td>
                            @if($project->cameraman_deadline)
                                <span class="{{ $project->isCameramanDeadlineOverdue() ? 'deadline-overdue' : '' }}">
                                    <i class="bi bi-calendar"></i> {{ $project->cameraman_deadline->format('M d, Y') }}
                                    @if($project->isCameramanDeadlineOverdue())
                                        <span class="badge bg-warning ms-2">Overdue</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Editor Deadline:</th>
                        <td>
                            @if($project->editor_deadline)
                                <span class="{{ $project->isEditorDeadlineOverdue() ? 'deadline-overdue' : '' }}">
                                    <i class="bi bi-calendar"></i> {{ $project->editor_deadline->format('M d, Y') }}
                                    @if($project->isEditorDeadlineOverdue())
                                        <span class="badge bg-warning ms-2">Overdue</span>
                                    @endif
                                </span>
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Created:</th>
                        <td>{{ $project->created_at->format('M d, Y H:i') }}</td>
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
                @if($project->activityLogs->count() > 0)
                    <div class="timeline">
                        @foreach($project->activityLogs as $log)
                            <div class="mb-3 pb-3 border-bottom">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <strong>{{ $log->user->name }}</strong>
                                        <span class="text-muted">({{ ucfirst($log->user->role) }})</span>
                                        <br>
                                        <small class="text-muted">{{ $log->description }}</small>
                                    </div>
                                    <small class="text-muted">{{ $log->created_at->format('M d, Y H:i') }}</small>
                                </div>
                                @if($log->old_status && $log->new_status)
                                    <div class="mt-2">
                                        <span class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $log->old_status)) }}</span>
                                        <i class="bi bi-arrow-right mx-2"></i>
                                        <span class="badge bg-primary">{{ ucfirst(str_replace('_', ' ', $log->new_status)) }}</span>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted">No activity logged yet.</p>
                @endif
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
                <form method="POST" action="{{ route('admin.projects.updateAssignment', $project) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="cameraman_id" class="form-label">Cameraman</label>
                        <select class="form-select" id="cameraman_id" name="cameraman_id">
                            <option value="">None</option>
                            @foreach($cameramen as $cameraman)
                                <option value="{{ $cameraman->id }}" {{ $project->cameraman_id == $cameraman->id ? 'selected' : '' }}>
                                    {{ $cameraman->name }} {{ $cameraman->isIdle() ? '(Idle)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="editor_id" class="form-label">Editor</label>
                        <select class="form-select" id="editor_id" name="editor_id">
                            <option value="">None</option>
                            @foreach($editors as $editor)
                                <option value="{{ $editor->id }}" {{ $project->editor_id == $editor->id ? 'selected' : '' }}>
                                    {{ $editor->name }} {{ $editor->isIdle() ? '(Idle)' : '' }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Update Assignment
                    </button>
                </form>
            </div>
        </div> -->
        <form method="POST" action="{{ route('admin.projects.updateAssignment', $project) }}">
            @csrf

            <input type="hidden" name="update_type" id="update_type">

            <!-- Cameraman -->
            <div class="mb-3">
                <label class="form-label">Cameraman</label>
                <select class="form-select" name="cameraman_id">
                    <option value="">None</option>
                    @foreach($cameramen as $cameraman)
                        <option value="{{ $cameraman->id }}" {{ $project->cameraman_id == $cameraman->id ? 'selected' : '' }}>
                            {{ $cameraman->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="btn btn-warning w-100 mt-2"
                        onclick="document.getElementById('update_type').value='cameraman'"
                        {{ $project->shooting_started_at ? 'disabled' : '' }}>
                    Update Cameraman
                </button>
            </div>

            <!-- Editor -->
            <div class="mb-3">
                <label class="form-label">Editor</label>
                <select class="form-select" name="editor_id">
                    <option value="">None</option>
                    @foreach($editors as $editor)
                        <option value="{{ $editor->id }}" {{ $project->editor_id == $editor->id ? 'selected' : '' }}>
                            {{ $editor->name }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="btn btn-primary w-100 mt-2"
                        onclick="document.getElementById('update_type').value='editor'">
                    Update Editor
                </button>
            </div>
        </form>


        <!-- Project Actions -->
        @if($project->status === 'review')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-check-circle"></i> Review Actions</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.projects.approve', $project) }}" class="mb-2">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle"></i> Approve Project
                        </button>
                    </form>
                    <form method="POST" action="{{ route('admin.projects.rework', $project) }}">
                        @csrf
                        <button type="submit" class="btn btn-warning w-100">
                            <i class="bi bi-arrow-counterclockwise"></i> Send for Rework
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Update Deadlines (Admin Only) -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-calendar"></i> Update Deadlines</h5>
            </div>
            <div class="card-body">
                @if($project->status === 'completed')
                    <p class="text-muted mb-0">Deadlines cannot be updated for completed projects.</p>
                @else
                    <form method="POST" action="{{ route('admin.projects.deadlines.update', $project) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="final_deadline" class="form-label">Final Deadline</label>
                            <input
                                type="date"
                                class="form-control"
                                id="final_deadline"
                                name="final_deadline"
                                value="{{ old('final_deadline', optional($project->final_deadline)->format('Y-m-d')) }}"
                            >
                        </div>
                        <div class="mb-3">
                            <label for="cameraman_deadline" class="form-label">Cameraman Deadline</label>
                            <input
                                type="date"
                                class="form-control"
                                id="cameraman_deadline"
                                name="cameraman_deadline"
                                value="{{ old('cameraman_deadline', optional($project->cameraman_deadline)->format('Y-m-d')) }}"
                            >
                        </div>
                        <div class="mb-3">
                            <label for="editor_deadline" class="form-label">Editor Deadline</label>
                            <input
                                type="date"
                                class="form-control"
                                id="editor_deadline"
                                name="editor_deadline"
                                value="{{ old('editor_deadline', optional($project->editor_deadline)->format('Y-m-d')) }}"
                            >
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Save Deadlines
                        </button>
                        <small class="text-muted d-block mt-2">
                            Final deadline must be today or later. Cameraman/Editor deadlines must be on or before final deadline. Editor deadline must be after or equal to cameraman deadline.
                        </small>
                    </form>
                @endif
            </div>
        </div>

        @if($project->status === 'approved')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-check-all"></i> Complete Project</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.projects.complete', $project) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-all"></i> Mark as Completed
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Manual Status Change (Admin Only) -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-arrow-repeat"></i> Change Status</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.projects.changeStatus', $project) }}">
                    @csrf
                    <div class="mb-3">
                        <label for="status" class="form-label">New Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="created" {{ $project->status === 'created' ? 'selected' : '' }}>Created</option>
                            <option value="assigned" {{ $project->status === 'assigned' ? 'selected' : '' }}>Assigned</option>
                            <option value="shooting" {{ $project->status === 'shooting' ? 'selected' : '' }}>Shooting</option>
                            <option value="raw_uploaded" {{ $project->status === 'raw_uploaded' ? 'selected' : '' }}>Raw Uploaded</option>
                            <option value="editing" {{ $project->status === 'editing' ? 'selected' : '' }}>Editing</option>
                            <option value="review" {{ $project->status === 'review' ? 'selected' : '' }}>Review</option>
                            <option value="approved" {{ $project->status === 'approved' ? 'selected' : '' }}>Approved</option>
                            <option value="rework" {{ $project->status === 'rework' ? 'selected' : '' }}>Rework</option>
                            <option value="completed" {{ $project->status === 'completed' ? 'selected' : '' }}>Completed</option>
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
                <form method="POST" action="{{ route('admin.projects.trash', $project) }}" onsubmit="return confirm('Are you sure you want to move this project to trash?');">
                    @csrf
                    <button type="submit" class="btn btn-danger w-100">
                        <i class="bi bi-trash"></i> Move to Trash
                    </button>
                </form>
                <small class="text-muted">Projects in trash can be restored later.</small>
            </div>
        </div>

        <!-- Raw Media Info -->
        @if($project->raw_media_method)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-camera-video"></i> Raw Media</h5>
                </div>
                <div class="card-body">
                    <p><strong>Method:</strong> {{ ucfirst($project->raw_media_method) }}</p>
                    @if($project->raw_media_link)
                        <p><strong>Link:</strong> <a href="{{ $project->raw_media_link }}" target="_blank">{{ $project->raw_media_link }}</a></p>
                    @endif
                    @if($project->cameraman_notes)
                        <p><strong>Notes:</strong> {{ $project->cameraman_notes }}</p>
                    @endif
                </div>
            </div>
        @endif

        <!-- Final Delivery Info -->
        @if($project->final_delivery_method)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-file-earmark-check"></i> Final Delivery</h5>
                </div>
                <div class="card-body">
                    <p><strong>Method:</strong> {{ ucfirst($project->final_delivery_method) }}</p>
                    @if($project->final_delivery_link)
                        <p><strong>Link:</strong> <a href="{{ $project->final_delivery_link }}" target="_blank">{{ $project->final_delivery_link }}</a></p>
                    @endif
                    @if($project->editor_notes)
                        <p><strong>Notes:</strong> {{ $project->editor_notes }}</p>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

