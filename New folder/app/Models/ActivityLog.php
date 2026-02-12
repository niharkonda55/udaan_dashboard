<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'project_id',
        'user_id',
        'action',
        'description',
        'old_status',
        'new_status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the project this log belongs to
     */
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Get the user who performed this action
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

