<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia;
    protected $with = ['featuredImage', 'categories'];
    protected $appends = ['featured_image_url'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured_image')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this->addMediaConversion('compressed')
                    ->width(800)
                    ->quality(70)
                    ->optimize();
            });

        $this->addMediaCollection('gallery')->onlyKeepLatest(15);
    }

    public function featuredImage(): MorphOne
    {
        return $this->morphOne(config('media-library.media_model'), 'model')
            ->where('collection_name', 'featured_image');
    }

    public function getFeaturedImageUrlAttribute()
    {
        $url = null;

        if ($this->relationLoaded('featuredImage')) {
            $url = optional($this->featuredImage)->getUrl();
        }
        return $url ? asset($url) : asset(env('ADMIN_THEME') . '/images/placeholder.png');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

}
