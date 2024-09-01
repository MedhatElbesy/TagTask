<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Post extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = ['title', 'body', 'cover_image', 'pinned', 'user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('pinned', function (Builder $builder) {
            $builder->orderBy('pinned', 'desc');
        });
    }

    protected static function booted()
    {
        static::created(function () {
            Cache::forget('stats');
        });

        static::updated(function () {
            Cache::forget('stats');
        });

        static::deleted(function () {
            Cache::forget('stats');
        });
    }
}
