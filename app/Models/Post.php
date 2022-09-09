<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const CLOSED = 0;
    const PUBLISHED = 1;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function scopeOnlyPublished($query)
    {
        $query->where('status', self::PUBLISHED);
    }

    public function isClosed()
    {
        return $this->status === self::CLOSED;
    }
}
