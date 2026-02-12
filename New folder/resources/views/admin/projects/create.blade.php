@extends('layouts.app')

@section('title', 'Create New Project')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2><i class="bi bi-plus-circle"></i> Create New Project</h2>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Back to Dashboard
    </a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.projects.store') }}">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Project Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
            </div>

            <div class="mb-3">
                <label for="priority" class="form-label">Priority <span class="text-danger">*</span></label>
                <select class="form-select" id="priority" name="priority" required>
                    <option value="low" {{ old('priority') == 'low' ? 'selected' : '' }}>Low</option>
                    <option value="medium" {{ old('priority') == 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="high" {{ old('priority') == 'high' ? 'selected' : '' }}>High</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="cameraman_id" class="form-label">Cameraman <span class="text-danger">*</span></label>
                <select class="form-select" id="cameraman_id" name="cameraman_id" required>
                    <option value="">Select Cameraman</option>
                    @foreach($idleCameramen as $cameraman)
                        <option value="{{ $cameraman->id }}" {{ old('cameraman_id') == $cameraman->id ? 'selected' : '' }}>
                            {{ $cameraman->name }} (Idle)
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Only idle cameramen are shown</small>
            </div>

            <div class="mb-3">
                <label for="editor_id" class="form-label">Editor<span class="text-danger">*</span></label>
                <select class="form-select" id="editor_id" name="editor_id" required>
                    <option value="">Select Editor</option>
                    @foreach($idleEditors as $editor)
                        <option value="{{ $editor->id }}" {{ old('editor_id') == $editor->id ? 'selected' : '' }}>
                            {{ $editor->name }} (Idle)
                        </option>
                    @endforeach
                </select>
                <small class="form-text text-muted">Only idle editors are shown</small>
            </div>

            <hr class="my-4">
            <h5 class="mb-3">Deadlines (Optional)</h5>

            <div class="mb-3">
                <label for="final_deadline" class="form-label">Final Deadline</label>
                <input type="date" class="form-control" id="final_deadline" name="final_deadline" value="{{ old('final_deadline') }}">
                <small class="form-text text-muted">Overall project deadline</small>
            </div>

            <div class="mb-3">
                <label for="cameraman_deadline" class="form-label">Cameraman Deadline</label>
                <input type="date" class="form-control" id="cameraman_deadline" name="cameraman_deadline" value="{{ old('cameraman_deadline') }}">
                <small class="form-text text-muted">Deadline for cameraman to complete shooting</small>
            </div>

            <div class="mb-3">
                <label for="editor_deadline" class="form-label">Editor Deadline</label>
                <input type="date" class="form-control" id="editor_deadline" name="editor_deadline" value="{{ old('editor_deadline') }}">
                <small class="form-text text-muted">Deadline for editor to complete editing</small>
            </div>

            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-circle"></i> Create Project
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

