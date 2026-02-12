<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Project extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Project statuses
     */
    const STATUS_CREATED = 'created';
    const STATUS_ASSIGNED = 'assigned';
    const STATUS_SHOOTING = 'shooting';
    const STATUS_RAW_UPLOADED = 'raw_uploaded';
    const STATUS_EDITING = 'editing';
    const STATUS_REVIEW = 'review';
    const STATUS_APPROVED = 'approved';
    const STATUS_REWORK = 'rework';
    const STATUS_COMPLETED = 'completed';

    /**
     * Media transfer methods
     */
    const TRANSFER_PHYSICAL = 'physical';
    const TRANSFER_ONLINE = 'online';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
        'priority',
        'status',
        'cameraman_id',
        'editor_id',
        'raw_media_method',
        'raw_media_link',
        'cameraman_notes',
        'editor_notes',
        'final_delivery_method',
        'final_delivery_link',
        'final_deadline',
        'cameraman_deadline',
        'editor_deadline',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'final_deadline' => 'date',
        'cameraman_deadline' => 'date',
        'editor_deadline' => 'date',
    ];

    /**
     * Get the cameraman assigned to this project
     */
    public function cameraman()
    {
        return $this->belongsTo(User::class, 'cameraman_id');
    }

    /**
     * Get the editor assigned to this project
     */
    public function editor()
    {
        return $this->belongsTo(User::class, 'editor_id');
    }

    /**
     * Get activity logs for this project
     */
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class)->orderBy('created_at', 'desc');
    }

    /**
     * Check if project is active
     */
    public function isActive(): bool
    {
        $activeStatuses = [
            self::STATUS_ASSIGNED,
            self::STATUS_SHOOTING,
            self::STATUS_RAW_UPLOADED,
            self::STATUS_EDITING,
            self::STATUS_REVIEW,
        ];
        return in_array($this->status, $activeStatuses);
    }

    /**
     * Get status display name
     */
    public function getStatusDisplayAttribute(): string
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    /**
     * Get priority badge class
     */
    public function getPriorityBadgeClassAttribute(): string
    {
        return match($this->priority) {
            'high' => 'badge bg-danger',
            'medium' => 'badge bg-warning',
            'low' => 'badge bg-info',
            default => 'badge bg-secondary',
        };
    }

    /**
     * Check if cameraman deadline is overdue
     */
    public function isCameramanDeadlineOverdue(): bool
    {
        return $this->cameraman_deadline && 
               $this->cameraman_deadline->isPast() && 
               !in_array($this->status, [self::STATUS_RAW_UPLOADED, self::STATUS_EDITING, self::STATUS_REVIEW, self::STATUS_APPROVED, self::STATUS_COMPLETED]);
    }

    /**
     * Check if editor deadline is overdue
     */
    public function isEditorDeadlineOverdue(): bool
    {
        return $this->editor_deadline && 
               $this->editor_deadline->isPast() && 
               !in_array($this->status, [self::STATUS_REVIEW, self::STATUS_APPROVED, self::STATUS_COMPLETED]);
    }

    /**
     * Check if final deadline is overdue
     */
    public function isFinalDeadlineOverdue(): bool
    {
        return $this->final_deadline && 
               $this->final_deadline->isPast() && 
               $this->status !== self::STATUS_COMPLETED;
    }

    /**
     * Scope to get only non-trashed projects
     */
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Scope to get only trashed projects
     */
    public function scopeTrashed($query)
    {
        return $query->onlyTrashed();
    }

    /**
     * Scope to get completed projects
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }
}

