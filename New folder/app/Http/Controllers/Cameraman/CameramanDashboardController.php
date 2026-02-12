<?php

namespace App\Http\Controllers\Cameraman;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CameramanDashboardController extends Controller
{
    /**
     * Show cameraman dashboard
     */
    public function index()
    {
        $projects = Project::active()
            ->where('cameraman_id', auth()->id())
            // Cameraman should not see completed or rework tasks
            ->whereNotIn('status', [Project::STATUS_COMPLETED, Project::STATUS_REWORK])
            ->with('editor')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('cameraman.dashboard', compact('projects'));
    }

    /**
     * Show completed projects for cameraman
     */
    public function showCompleted()
    {
        $projects = Project::active()
            ->where('cameraman_id', auth()->id())
            ->completed()
            ->with('editor')
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('cameraman.projects.completed', compact('projects'));
    }

    /**
     * Show project details
     */
    public function showProject(Project $project)
    {
        // Ensure user can only access their assigned projects
        if ($project->cameraman_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $project->load(['editor', 'activityLogs.user']);
        return view('cameraman.projects.show', compact('project'));
    }

    /**
     * Mark shooting started
     */
    public function markShootingStarted(Project $project)
    {
        if ($project->cameraman_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        if ($project->status !== Project::STATUS_ASSIGNED) {
            return back()->with('error', 'Invalid status transition');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $project->status;
            $project->update(['status' => Project::STATUS_SHOOTING]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'shooting_started',
                'description' => "Shooting started",
                'old_status' => $oldStatus,
                'new_status' => Project::STATUS_SHOOTING,
            ]);

            DB::commit();
            return back()->with('success', 'Shooting marked as started');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status');
        }
    }

    /**
     * Mark shooting completed
     */
    public function markShootingCompleted(Project $project)
    {
        if ($project->cameraman_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        if ($project->status !== Project::STATUS_SHOOTING) {
            return back()->with('error', 'Invalid status transition');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $project->status;
            $project->update(['status' => Project::STATUS_RAW_UPLOADED]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'shooting_completed',
                'description' => "Shooting completed",
                'old_status' => $oldStatus,
                'new_status' => Project::STATUS_RAW_UPLOADED,
            ]);

            DB::commit();
            return back()->with('success', 'Shooting marked as completed');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update status');
        }
    }

    /**
     * Update raw media information
     */
    public function updateRawMedia(Request $request, Project $project)
    {
        if ($project->cameraman_id !== auth()->id()) {
            abort(403, 'Unauthorized access');
        }

        $validated = $request->validate([
            'raw_media_method' => 'required|in:physical,online',
            'raw_media_link' => 'nullable|url|required_if:raw_media_method,online',
            'cameraman_notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $project->update([
                'raw_media_method' => $validated['raw_media_method'],
                'raw_media_link' => $validated['raw_media_link'] ?? null,
                'cameraman_notes' => $validated['cameraman_notes'] ?? null,
            ]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'raw_media_updated',
                'description' => "Raw media information updated: " . $validated['raw_media_method'],
                'old_status' => $project->status,
                'new_status' => $project->status,
            ]);

            DB::commit();
            return back()->with('success', 'Raw media information updated');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update raw media information');
        }
    }
}

