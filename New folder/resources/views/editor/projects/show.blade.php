@extends('layouts.app')

@section('title', $project->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-scissors"></i> {{ $project->name }}</h2>
    <a href="{{ route('editor.dashboard') }}" class="btn btn-secondary">
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
                        <th>Editor Deadline:</th>
                        <td>
                            @if($project->editor_deadline)
                                <span class="{{ $project->isEditorDeadlineOverdue() ? 'deadline-overdue' : '' }}">
                                    {{ $project->editor_deadline->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Final Deadline:</th>
                        <td>
                            @if($project->final_deadline)
                                <span class="{{ $project->isFinalDeadlineOverdue() ? 'deadline-overdue' : '' }}">
                                    {{ $project->final_deadline->format('M d, Y') }}
                                </span>
                            @else
                                <span class="text-muted">Not set</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Cameraman:</th>
                        <td>
                            {{ $project->cameraman ? $project->cameraman->name : '<span class="text-muted">Not assigned</span>' }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Raw Media Info from Cameraman -->
        @if($project->raw_media_method)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-camera-video"></i> Raw Media Information</h5>
                </div>
                <div class="card-body">
                    <p><strong>Transfer Method:</strong> {{ ucfirst($project->raw_media_method) }}</p>
                    @if($project->raw_media_link)
                        <p><strong>Link:</strong> <a href="{{ $project->raw_media_link }}" target="_blank">{{ $project->raw_media_link }}</a></p>
                    @endif
                    @if($project->cameraman_notes)
                        <p><strong>Cameraman Notes:</strong> {{ $project->cameraman_notes }}</p>
                    @endif
                </div>
            </div>
        @endif

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
        <!-- Editing Actions -->
        @if($project->status === 'raw_uploaded' || $project->status === 'rework')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-play-circle"></i> 
                        {{ $project->status === 'rework' ? 'Rework - Start Editing' : 'Editing' }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($project->status === 'rework')
                        <div class="alert alert-warning mb-3">
                            <i class="bi bi-exclamation-triangle"></i> This project has been sent back for rework. Please review and resubmit.
                        </div>
                    @endif
                    <form method="POST" action="{{ route('editor.projects.editing.start', $project) }}">
                        @csrf
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-play-fill"></i> Mark Editing Started
                        </button>
                    </form>
                </div>
            </div>
        @endif

        @if($project->status === 'editing')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-check-circle"></i> Editing</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('editor.projects.review', $project) }}">
                        @csrf
                        <button type="submit" class="btn btn-success w-100">
                            <i class="bi bi-check-circle"></i> Mark Ready for Review
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Final Delivery -->
        @if($project->status === 'editing' || $project->status === 'review' || $project->status === 'approved' || $project->status === 'rework')
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-upload"></i> Final Delivery Information</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('editor.projects.finalDelivery', $project) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="final_delivery_method" class="form-label">Delivery Method <span class="text-danger">*</span></label>
                            <select class="form-select" id="final_delivery_method" name="final_delivery_method" required>
                                <option value="">Select Method</option>
                                <option value="physical" {{ $project->final_delivery_method === 'physical' ? 'selected' : '' }}>Physical Drive</option>
                                <option value="online" {{ $project->final_delivery_method === 'online' ? 'selected' : '' }}>Online Link</option>
                            </select>
                        </div>
                        <div class="mb-3" id="final_delivery_link_group" style="display: none;">
                            <label for="final_delivery_link" class="form-label">Online Link</label>
                            <input type="url" class="form-control" id="final_delivery_link" name="final_delivery_link" 
                                   value="{{ $project->final_delivery_link }}" placeholder="https://...">
                        </div>
                        <div class="mb-3">
                            <label for="editor_notes" class="form-label">Notes</label>
                            <textarea class="form-control" id="editor_notes" name="editor_notes" rows="3">{{ $project->editor_notes }}</textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-save"></i> Update Information
                        </button>
                    </form>
                </div>
            </div>
        @endif

        <!-- Current Final Delivery Info -->
        @if($project->final_delivery_method)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-info-circle"></i> Current Final Delivery Info</h5>
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

@push('scripts')
<script>
    document.getElementById('final_delivery_method').addEventListener('change', function() {
        const linkGroup = document.getElementById('final_delivery_link_group');
        const linkInput = document.getElementById('final_delivery_link');
        if (this.value === 'online') {
            linkGroup.style.display = 'block';
            linkInput.setAttribute('required', 'required');
        } else {
            linkGroup.style.display = 'none';
            linkInput.removeAttribute('required');
        }
    });
    
    // Trigger on page load if already set
    if (document.getElementById('final_delivery_method').value === 'online') {
        document.getElementById('final_delivery_link_group').style.display = 'block';
    }
</script>
@endpush
@endsection

