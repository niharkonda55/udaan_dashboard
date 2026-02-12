<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * User roles
     */
    const ROLE_ADMIN = 'admin';
    const ROLE_CAMERAMAN = 'cameraman';
    const ROLE_EDITOR = 'editor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Check if user is cameraman
     */
    public function isCameraman(): bool
    {
        return $this->role === self::ROLE_CAMERAMAN;
    }

    /**
     * Check if user is editor
     */
    public function isEditor(): bool
    {
        return $this->role === self::ROLE_EDITOR;
    }

    /**
     * Get projects assigned as cameraman
     */
    public function cameramanProjects()
    {
        return $this->hasMany(Project::class, 'cameraman_id');
    }

    /**
     * Get projects assigned as editor
     */
    public function editorProjects()
    {
        return $this->hasMany(Project::class, 'editor_id');
    }

    /**
     * Get all assigned projects (for cameraman or editor)
     */
    public function assignedProjects()
    {
        if ($this->isCameraman()) {
            return $this->cameramanProjects();
        } elseif ($this->isEditor()) {
            return $this->editorProjects();
        }
        return collect();
    }

    /**
     * Check if user is idle (no active projects)
     */
    public function isIdle(): bool
    {
        if ($this->isAdmin()) {
            return false; // Admin is never idle
        }

        $activeStatuses = ['assigned', 'shooting', 'raw_uploaded', 'editing', 'review'];
        
        if ($this->isCameraman()) {
            return !$this->cameramanProjects()->whereIn('status', $activeStatuses)->exists();
        } elseif ($this->isEditor()) {
            return !$this->editorProjects()->whereIn('status', $activeStatuses)->exists();
        }

        return true;
    }
}

