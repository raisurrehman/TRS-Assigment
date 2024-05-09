<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Profile extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'experience',
        'skills',
        'location',
        'education',
    ];

    protected $with = ['avatar', 'resume'];
    protected $appends = ['avatar_url', 'resume_url'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')->singleFile();
        $this->addMediaCollection('resume')->onlyKeepLatest(1);
    }

    public function avatar(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')
            ->where('collection_name', 'avatar');
    }

    public function getAvatarUrlAttribute()
    {
        $url = null;

        if ($this->relationLoaded('avatar')) {
            $url = optional($this->avatar)->getUrl();
        }
        return $url ? asset($url) : asset(env('ADMIN_THEME') . '/images/avatar.png');
    }

    public function resume(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')
            ->where('collection_name', 'resume');
    }

    public function getResumeUrlAttribute()
    {
        $url = null;

        if ($this->relationLoaded('resume')) {

            $url = optional($this->resume)->getUrl();
        }
        return $url ? asset($url) : asset(env('ADMIN_THEME') . '/images/placeholder.png');
    }
}
