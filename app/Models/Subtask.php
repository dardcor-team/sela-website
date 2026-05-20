<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subtask extends Model
{
    use HasFactory;
    use HasUuids;

    public const UPDATED_AT = null;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'task_id',
        'title',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function progressEntries(): HasMany
    {
        return $this->hasMany(SubtaskProgress::class, 'subtask_id');
    }

    protected static function booted()
    {
        static::deleting(function ($subtask) {
            $subtask->progressEntries()->delete();
        });
    }
}
