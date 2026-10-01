<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MailMessage extends Model
{
    protected $fillable = ['from_user_id', 'to_user_id', 'subject', 'body', 'read_at', 'starred_by_recipient', 'deleted_by_sender', 'deleted_by_recipient'];

    protected $casts = ['read_at' => 'datetime', 'starred_by_recipient' => 'bool', 'deleted_by_sender' => 'bool', 'deleted_by_recipient' => 'bool'];

    public function from(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(User::class, 'to_user_id');
    }
}
