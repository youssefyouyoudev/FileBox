<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'size',
        'mime',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }

    public function getHumanSizeAttribute(): string
    {
        $size = (float) $this->size;

        if ($size >= 1048576) {
            return number_format($size / 1048576, 2) . ' MB';
        }

        return number_format($size / 1024, 1) . ' KB';
    }

    public function isImage(): bool
    {
        return str_starts_with($this->mime, 'image/');
    }

    public function isFolder(): bool
    {
        return $this->mime === 'folder';
    }
}
