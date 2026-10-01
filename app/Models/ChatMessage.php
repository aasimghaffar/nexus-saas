<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    protected $fillable = ['sender_id', 'recipient_id', 'body', 'read_at'];

    protected $casts = ['read_at' => 'datetime'];

    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function scopeBetween($query, int $a, int $b)
    {
        return $query->where(fn ($q) => $q
            ->where(fn ($q) => $q->where('sender_id', $a)->where('recipient_id', $b))
            ->orWhere(fn ($q) => $q->where('sender_id', $b)->where('recipient_id', $a)));
    }
}
