<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Group extends Model
{
    use HasFactory;
    use HasUuids;

    public const UPDATED_AT = null;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'course_name',
        'class_name',
        'group_number',
        'member_limit',
        'invitation_code',
        'lecture_code',
        'created_by',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'group_number' => 'integer',
            'member_limit' => 'integer',
            'created_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'created_by');
    }

    public function members(): HasMany
    {
        return $this->hasMany(GroupMember::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    protected static function booted()
    {
        static::deleting(function ($group) {
            $group->tasks()->each(function ($task) {
                $task->delete();
            });
            $group->members()->delete();
        });
    }
}
