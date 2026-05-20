<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;
    use HasUuids;

    public const UPDATED_AT = null;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'title',
        'description',
        'category',
        'subject',
        'start_date',
        'due_date',
        'is_group',
        'group_id',
        'created_by',
        'status',
        'priority',
        'link',
        'file_path',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'datetime',
            'due_date' => 'datetime',
            'is_group' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'created_by');
    }

    public function subtasks(): HasMany
    {
        return $this->hasMany(Subtask::class);
    }

    public function taskLinks(): HasMany
    {
        return $this->hasMany(TaskLink::class);
    }

    public function taskFiles(): HasMany
    {
        return $this->hasMany(TaskFile::class);
    }

    protected static function booted()
    {
        static::deleting(function ($task) {
            $task->subtasks()->each(function($subtask) {
                $subtask->delete();
            });
            $task->taskLinks()->delete();
            $task->taskFiles()->delete();
        });
    }
}
