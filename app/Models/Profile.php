<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Profile extends Model
{
    use HasFactory;
    use HasUuids;

    public const CREATED_AT = null;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'id',
        'username',
        'full_name',
        'avatar_url',
        'class_name',
        'last_login_at',
    ];

    protected function casts(): array
    {
        return [
            'last_login_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function groupMembers(): HasMany
    {
        return $this->hasMany(GroupMember::class, 'user_id');
    }

    public function createdGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'created_by');
    }

    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function taskFiles(): HasMany
    {
        return $this->hasMany(TaskFile::class, 'uploaded_by');
    }

    public function subtaskProgress(): HasMany
    {
        return $this->hasMany(SubtaskProgress::class, 'user_id');
    }

    public function profileAbilities(): HasMany
    {
        return $this->hasMany(ProfileAbility::class, 'user_id');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'user_id');
    }

    public function lecturerClasses(): HasMany
    {
        return $this->hasMany(LecturerClass::class, 'lecturer_id');
    }
}
