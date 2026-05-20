<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SubtaskProgress extends Model
{
    use HasFactory;
    use HasUuids;

    public const CREATED_AT = null;

    protected $table = 'subtask_progress';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'subtask_id',
        'user_id',
        'progress',
    ];

    protected function casts(): array
    {
        return [
            'progress' => 'integer',
            'updated_at' => 'datetime',
        ];
    }

    public function subtask(): BelongsTo
    {
        return $this->belongsTo(Subtask::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'user_id');
    }
}
