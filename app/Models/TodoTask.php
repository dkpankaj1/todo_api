<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TodoTask extends Model
{
    protected $fillable = [
        'title',
        'is_complete',
        'parent_id',
        'user_id',
        'ordered',
    ];

    public function subTask(): HasMany
    {
        return $this->hasMany(TodoTask::class, 'parent_id');
    }
    public function parent(): BelongsTo
    {
        return $this->belongsTo(TodoTask::class, 'parent_id');
    }
}
