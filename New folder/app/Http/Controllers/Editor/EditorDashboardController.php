<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EditorDashboardController extends Controller
{
    /**
     * Show editor dashboard
     */
    public function index()
    {
        $projects = Project::active()
            ->where('editor_id', auth()->id())
            ->where('status', '!=', Project::STATUS_COMPLETED)
            ->with('cameraman')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('editor.dashboard', compact('projects'));
    }

    /**
     * Show completed projects for editor
     */
    public function showCompleted()
    {
        $projects = Project::active()
            ->where('editor_id', auth()->id())
            ->completed()
            ->with('cameraman')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('editor.projects.completed', compact('projects'));
    }

    /**
     * Show project details
     */
    public function showProject(Project $project)
    {
        // Ensure user can only access their assigned projects
        if ($project->editor_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $project->load(['cameraman', 'activityLogs.user']);
        return view('editor.projects.show', compact('project'));
    }

    /**
     * Mark editing started
     */
    public function markEditingStarted(Project $project)
    {
        if ($project->editor_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        // Allow editing to start either after raw upload or when project is sent for rework
        if (! in_array($project->status, [Project::STATUS_RAW_UPLOADED, Project::STATUS_REWORK], true)) {
            return back()->with('error', 'Invalid status transition');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $project->status;
            $project->update(['status' => Project::STATUS_EDITING]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'editing_started',
                'description' => "Editing started",
                'old_status' => $oldStatus,
                'new_status' => Project::STATUS_EDITING,
            ]);

            DB::commit();
            return back()->with('success', 'Editing marked as started');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status');
        }
    }

    /**
     * Mark final output ready for review
     */
    public function markReadyForReview(Project $project)
    {
        if ($project->editor_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        if ($project->status !== Project::STATUS_EDITING) {
            return back()->with('error', 'Invalid status transition');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $project->status;
            $project->update(['status' => Project::STATUS_REVIEW]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'ready_for_review',
                'description' => "Final output ready for review",
                'old_status' => $oldStatus,
                'new_status' => Project::STATUS_REVIEW,
            ]);

            DB::commit();
            return back()->with('success', 'Project marked as ready for review');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status');
        }
    }

    /**
     * Update final delivery information
     */
    public function updateFinalDelivery(Request $request, Project $project)
    {
        if ($project->editor_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'final_delivery_method' => 'required|in:physical,online',
            'final_delivery_link' => 'nullable|url|required_if:final_delivery_method,online',
            'editor_notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $project->update([
                'final_delivery_method' => $validated['final_delivery_method'],
                'final_delivery_link' => $validated['final_delivery_link'] ?? null,
                'editor_notes' => $validated['editor_notes'] ?? null,
            ]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'final_delivery_updated',
                'description' => "Final delivery information updated: " . $validated['final_delivery_method'],
                'old_status' => $project->status,
                'new_status' => $project->status,
            ]);

            DB::commit();
            return back()->with('success', 'Final delivery information updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update final delivery information');
        }
    }
}

