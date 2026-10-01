<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class File extends Model
{
    protected $fillable = ['name', 'path', 'mime', 'size', 'folder_id', 'user_id'];

    public function folder(): BelongsTo
    {
        return $this->belongsTo(Folder::class);
    }

    public function getHumanSizeAttribute(): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = (float) $this->size;
        $i = 0;
        while ($size >= 1024 && $i < 3) {
            $size /= 1024;
            $i++;
        }

        return round($size, $i ? 1 : 0).' '.$units[$i];
    }
}
