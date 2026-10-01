<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Event extends Model
{
    protected $fillable = ['title', 'description', 'starts_at', 'ends_at', 'all_day', 'color', 'user_id'];

    protected $casts = ['starts_at' => 'datetime', 'ends_at' => 'datetime', 'all_day' => 'bool'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
