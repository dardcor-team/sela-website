<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LecturerClass extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'lecturer_classes';
    protected $keyType = 'string';
    public $incrementing = false;
    public const UPDATED_AT = null;

    protected $fillable = [
        'lecturer_id',
        'class_name',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function lecturer(): BelongsTo
    {
        return $this->belongsTo(Profile::class, 'lecturer_id');
    }
}
