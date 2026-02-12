<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    /**
     * Deadline validation rules/messages shared by create + update.
     */
    private function deadlineValidation(): array
    {
        return [
            'rules' => [
                'final_deadline' => [
                    'nullable',
                    'date',
                    'after_or_equal:today',
                ],
                'cameraman_deadline' => [
                    'nullable',
                    'date',
                    'before_or_equal:final_deadline',
                ],
                'editor_deadline' => [
                    'nullable',
                    'date',
                    'before_or_equal:final_deadline',
                    'after_or_equal:cameraman_deadline',
                ],
            ],
            'messages' => [
                'final_deadline.after_or_equal' =>
                    'Final deadline cannot be in the past.',
                'cameraman_deadline.before_or_equal' =>
                    'Cameraman deadline must be on or before the final deadline.',
                'editor_deadline.before_or_equal' =>
                    'Editor deadline must be on or before the final deadline.',
                'editor_deadline.after_or_equal' =>
                    'Editor deadline must be after or equal to the cameraman deadline.',
            ],
        ];
    }

    /**
     * Show admin dashboard
     */
    public function index()
    {
        $totalProjects = Project::active()->count();
        $completedProjects = Project::active()->completed()->count();
        $activeProjects = Project::active()->whereIn('status', [
            Project::STATUS_ASSIGNED,
            Project::STATUS_SHOOTING,
            Project::STATUS_RAW_UPLOADED,
            Project::STATUS_EDITING,
            Project::STATUS_REVIEW,
        ])->count();
        
        // Split idle users into cameramen and editors
        $idleCameramen = User::where('role', User::ROLE_CAMERAMAN)
            ->get()
            ->filter(fn($user) => $user->isIdle());
        
        $idleEditors = User::where('role', User::ROLE_EDITOR)
            ->get()
            ->filter(fn($user) => $user->isIdle());

        $projects = Project::active()->with(['cameraman', 'editor'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('admin.dashboard', compact(
            'totalProjects',
            'completedProjects',
            'activeProjects',
            'idleCameramen',
            'idleEditors',
            'projects'
        ));
    }

    /**
     * Show project creation form
     */
    public function createProject()
    {
        $idleCameramen = User::where('role', User::ROLE_CAMERAMAN)
            ->get()
            ->filter(fn($user) => $user->isIdle());
        
        $idleEditors = User::where('role', User::ROLE_EDITOR)
            ->get()
            ->filter(fn($user) => $user->isIdle());

        return view('admin.projects.create', compact('idleCameramen', 'idleEditors'));
    }

    /**
     * Store new project
     */
    public function storeProject(Request $request)
    {
        $deadlineValidation = $this->deadlineValidation();

        $validated = $request->validate(array_merge([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'cameraman_id' => 'nullable|exists:users,id',
            'editor_id' => 'nullable|exists:users,id',
        ], $deadlineValidation['rules']), $deadlineValidation['messages']);
        

        DB::beginTransaction();
        try {
            $project = Project::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'priority' => $validated['priority'],
                'status' => $validated['cameraman_id'] && $validated['editor_id'] 
                    ? Project::STATUS_ASSIGNED 
                    : Project::STATUS_CREATED,
                'cameraman_id' => $validated['cameraman_id'] ?? null,
                'editor_id' => $validated['editor_id'] ?? null,
                'final_deadline' => $validated['final_deadline'] ?? null,
                'cameraman_deadline' => $validated['cameraman_deadline'] ?? null,
                'editor_deadline' => $validated['editor_deadline'] ?? null,
            ]);

            // Log activity
            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'project_created',
                'description' => "Project '{$project->name}' created",
                'old_status' => null,
                'new_status' => $project->status,
            ]);

            if ($project->status === Project::STATUS_ASSIGNED) {
                ActivityLog::create([
                    'project_id' => $project->id,
                    'user_id' => auth()->id(),
                    'action' => 'project_assigned',
                    'description' => "Project assigned to cameraman and editor",
                    'old_status' => Project::STATUS_CREATED,
                    'new_status' => Project::STATUS_ASSIGNED,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.dashboard')->with('success', 'Project created successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create project: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Update project deadlines (Admin only)
     */
    public function updateDeadlines(Request $request, Project $project)
    {
        // Optional: do not allow editing deadlines if project is completed
        if ($project->status === Project::STATUS_COMPLETED) {
            return back()->with('error', 'Deadlines cannot be updated for completed projects.');
        }

        $deadlineValidation = $this->deadlineValidation();
        $validated = $request->validate($deadlineValidation['rules'], $deadlineValidation['messages']);

        $old = [
            'final_deadline' => $project->final_deadline,
            'cameraman_deadline' => $project->cameraman_deadline,
            'editor_deadline' => $project->editor_deadline,
        ];

        DB::beginTransaction();
        try {
            $project->update([
                'final_deadline' => $validated['final_deadline'] ?? null,
                'cameraman_deadline' => $validated['cameraman_deadline'] ?? null,
                'editor_deadline' => $validated['editor_deadline'] ?? null,
            ]);

            $changed = [];
            foreach (['final_deadline', 'cameraman_deadline', 'editor_deadline'] as $field) {
                $before = optional($old[$field])->format('Y-m-d');
                $after = optional($project->{$field})->format('Y-m-d');
                if ($before !== $after) {
                    $label = str_replace('_', ' ', $field);
                    $changed[] = ucfirst($label) . ": " . ($before ?: 'None') . " → " . ($after ?: 'None');
                }
            }

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'deadline_updated',
                'description' => 'Deadlines updated' . (count($changed) ? ' (' . implode(', ', $changed) . ')' : ''),
                'old_status' => $project->status,
                'new_status' => $project->status,
            ]);

            DB::commit();
            return back()->with('success', 'Deadlines updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update deadlines.')->withInput();
        }
    }

    /**
     * Show project details
     */
    public function showProject(Project $project)
    {
        $project->load(['cameraman', 'editor', 'activityLogs.user']);
        // Get all cameramen and editors for assignment (not just idle ones)
        $cameramen = User::where('role', User::ROLE_CAMERAMAN)->orderBy('name')->get();
        $editors = User::where('role', User::ROLE_EDITOR)->orderBy('name')->get();

        return view('admin.projects.show', compact('project', 'cameramen', 'editors'));
    }

    /**
     * Update project assignment
     */
    // public function updateAssignment(Request $request, Project $project)
    // {
    //     $validated = $request->validate([
    //         'cameraman_id' => 'nullable|exists:users,id',
    //         'editor_id' => 'nullable|exists:users,id',
    //     ]);

    //     DB::beginTransaction();
    //     try {
    //         $oldStatus = $project->status;
            
    //         $project->update([
    //             'cameraman_id' => $validated['cameraman_id'] ?? $project->cameraman_id,
    //             'editor_id' => $validated['editor_id'] ?? $project->editor_id,
    //             'status' => ($validated['cameraman_id'] && $validated['editor_id']) 
    //                 ? Project::STATUS_ASSIGNED 
    //                 : $project->status,
    //         ]);

    //         // Log activity
    //         ActivityLog::create([
    //             'project_id' => $project->id,
    //             'user_id' => auth()->id(),
    //             'action' => 'project_reassigned',
    //             'description' => "Project assignment updated",
    //             'old_status' => $oldStatus,
    //             'new_status' => $project->status,
    //         ]);

    //         DB::commit();
    //         return back()->with('success', 'Assignment updated successfully');
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         return back()->with('error', 'Failed to update assignment: ' . $e->getMessage());
    //     }
    // }

    public function updateAssignment(Request $request, Project $project)
    {
        $validated = $request->validate([
            'update_type'  => 'required|in:cameraman,editor',
            'cameraman_id' => 'nullable|exists:users,id',
            'editor_id'    => 'nullable|exists:users,id',
        ]);

        DB::beginTransaction();

        try {
            $oldStatus = $project->status;

            /*
            |--------------------------------------------------------------------------
            | Update Cameraman
            |--------------------------------------------------------------------------
            | Allowed ONLY before shooting starts
            */
            if ($validated['update_type'] === 'cameraman') {

                if ($project->shooting_started_at) {
                    return back()->with('error', 'Cameraman cannot be changed after shooting has started');
                }

                if (
                    isset($validated['cameraman_id']) &&
                    $validated['cameraman_id'] != $project->cameraman_id
                ) {
                    $project->cameraman_id = $validated['cameraman_id'];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | Update Editor
            |--------------------------------------------------------------------------
            | Allowed at any stage, must NOT touch cameraman or workflow
            */
            if ($validated['update_type'] === 'editor') {

                if (
                    isset($validated['editor_id']) &&
                    $validated['editor_id'] != $project->editor_id
                ) {
                    $project->editor_id = $validated['editor_id'];
                }
            }

            /*
            |--------------------------------------------------------------------------
            | IMPORTANT: Workflow status protection
            |--------------------------------------------------------------------------
            | Status must NEVER move backwards
            */
            // Do not touch status if shooting already started or completed
            if (!$project->shooting_started_at && !$project->shooting_completed_at) {
                if ($project->cameraman_id && $project->editor_id) {
                    $project->status = Project::STATUS_ASSIGNED;
                }
            }

            $project->save();

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id'    => auth()->id(),
                'action'     => $validated['update_type'] === 'cameraman'
                                ? 'cameraman_reassigned'
                                : 'editor_reassigned',
                'description'=> 'Project assignment updated',
                'old_status' => $oldStatus,
                'new_status' => $project->status,
            ]);

            DB::commit();

            return back()->with('success', 'Assignment updated successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update assignment: ' . $e->getMessage());
        }
    }


    /**
     * Approve project
     */
    public function approveProject(Project $project)
    {
        if ($project->status !== Project::STATUS_REVIEW) {
            return back()->with('error', 'Project must be in review status');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $project->status;
            $project->update(['status' => Project::STATUS_APPROVED]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'project_approved',
                'description' => "Project approved",
                'old_status' => $oldStatus,
                'new_status' => Project::STATUS_APPROVED,
            ]);

            DB::commit();
            return back()->with('success', 'Project approved successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to approve project');
        }
    }

    /**
     * Send project for rework
     */
    public function sendRework(Project $project)
    {
        if ($project->status !== Project::STATUS_REVIEW) {
            return back()->with('error', 'Project must be in review status');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $project->status;
            $project->update(['status' => Project::STATUS_REWORK]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'project_rework',
                'description' => "Project sent for rework",
                'old_status' => $oldStatus,
                'new_status' => Project::STATUS_REWORK,
            ]);

            DB::commit();
            return back()->with('success', 'Project sent for rework');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to send project for rework');
        }
    }

    /**
     * Complete project (Mark as Completed)
     */
    public function completeProject(Project $project)
    {
        if ($project->status !== Project::STATUS_APPROVED) {
            return back()->with('error', 'Project must be approved first');
        }

        // Enforce transfer rule: at least one transfer method must be recorded
        if (is_null($project->raw_media_method) && is_null($project->final_delivery_method)) {
            return back()->with('error', 'Project cannot be completed until at least one transfer method is recorded.');
        }

        DB::beginTransaction();
        try {
            $oldStatus = $project->status;
            $project->update(['status' => Project::STATUS_COMPLETED]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'project_completed',
                'description' => "Project marked as completed",
                'old_status' => $oldStatus,
                'new_status' => Project::STATUS_COMPLETED,
            ]);

            DB::commit();
            return back()->with('success', 'Project completed successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to complete project');
        }
    }

    /**
     * Manually change project status (Admin only)
     */
    public function changeStatus(Request $request, Project $project)
    {
        $validated = $request->validate([
            'status' => 'required|in:' . implode(',', [
                Project::STATUS_CREATED,
                Project::STATUS_ASSIGNED,
                Project::STATUS_SHOOTING,
                Project::STATUS_RAW_UPLOADED,
                Project::STATUS_EDITING,
                Project::STATUS_REVIEW,
                Project::STATUS_APPROVED,
                Project::STATUS_REWORK,
                Project::STATUS_COMPLETED,
            ]),
        ]);

        // Enforce completion rule: at least one transfer method must be recorded
        if ($validated['status'] === Project::STATUS_COMPLETED) {
            if (is_null($project->raw_media_method) && is_null($project->final_delivery_method)) {
                return back()->with('error', 'Project cannot be completed until at least one transfer method is recorded.');
            }
        }

        DB::beginTransaction();
        try {
            $oldStatus = $project->status;
            $project->update(['status' => $validated['status']]);

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'status_changed_manually',
                'description' => "Status manually changed from '{$oldStatus}' to '{$validated['status']}' by admin",
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
            ]);

            DB::commit();
            return back()->with('success', 'Project status updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update project status');
        }
    }

    /**
     * Soft delete project (Trash)
     */
    public function trashProject(Project $project)
    {
        DB::beginTransaction();
        try {
            $project->delete(); // Soft delete

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'project_trashed',
                'description' => "Project moved to trash",
                'old_status' => $project->status,
                'new_status' => $project->status,
            ]);

            DB::commit();

            // Redirect to admin dashboard so we don't stay on a now-trashed URL
            return redirect()
                ->route('admin.dashboard')
                ->with('success', 'Project moved to trash successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to trash project');
        }
    }

    /**
     * Restore trashed project
     */
    public function restoreProject($id)
    {
        $project = Project::onlyTrashed()->findOrFail($id);

        DB::beginTransaction();
        try {
            $project->restore();

            ActivityLog::create([
                'project_id' => $project->id,
                'user_id' => auth()->id(),
                'action' => 'project_restored',
                'description' => "Project restored from trash",
                'old_status' => $project->status,
                'new_status' => $project->status,
            ]);

            DB::commit();
            return back()->with('success', 'Project restored successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to restore project');
        }
    }

    /**
     * Show trashed projects
     */
    public function showTrash(Request $request)
    {
        $query = Project::onlyTrashed()
            ->with(['cameraman', 'editor'])
            ->orderBy('deleted_at', 'desc');

        $total = (clone $query)->count();
        $perPage = 10;
        $page = max(1, (int) $request->query('page', 1));
        
        // Handle empty collections gracefully - always show the view
        if ($total === 0) {
            $projects = $query->paginate($perPage);
            return view('admin.projects.trash', compact('projects'));
        }
        
        $lastPage = max(1, (int) ceil($total / $perPage));

        // If requested page is beyond the last available page, redirect to lastPage
        if ($page > $lastPage) {
            $target = $lastPage === 1
                ? route('admin.projects.trash.index')
                : route('admin.projects.trash.index', ['page' => $lastPage]);

            return redirect($target);
        }

        $projects = $query->paginate($perPage);

        return view('admin.projects.trash', compact('projects'));
    }

    /**
     * Show completed projects
     */
    public function showCompleted(Request $request)
    {
        $query = Project::active()
            ->completed()
            ->with(['cameraman', 'editor'])
            ->orderBy('updated_at', 'desc');

        $total = (clone $query)->count();
        $perPage = 10;
        $page = max(1, (int) $request->query('page', 1));
        
        // Handle empty collections gracefully - always show the view
        if ($total === 0) {
            $projects = $query->paginate($perPage);
            return view('admin.projects.completed', compact('projects'));
        }
        
        $lastPage = max(1, (int) ceil($total / $perPage));

        // If requested page is beyond the last available page, redirect to lastPage
        if ($page > $lastPage) {
            $target = $lastPage === 1
                ? route('admin.projects.completed')
                : route('admin.projects.completed', ['page' => $lastPage]);

            return redirect($target);
        }

        $projects = $query->paginate($perPage);

        return view('admin.projects.completed', compact('projects'));
    }
}

