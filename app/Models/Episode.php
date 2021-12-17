<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Episode extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use InteractsWithMedia;

    protected $fillable = [
        'name', 'slug', 'description', 'media_url', 'tags', 'featured_hosts', 'is_featured', 'is_popular', 'show_id'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('episode-image-collection')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg'])
            ->useFallbackUrl('/noimage.png')
            ->useFallbackPath(public_path('/noimage.png'))
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(100)
                    ->height(100);
            });
    }

    protected $appends = ['episode_image', 'episode_image_thumb'];

    public function getEpisodeImageAttribute()
    {
        return $this->getFirstMediaUrl('episode-image-collection');
    }

    public function getEpisodeImageThumbAttribute()
    {
        return $this->getFirstMediaUrl('episode-image-collection', 'thumb');
    }

    public function getTagsAttribute($value)
    {
        return unserialize($value);
    }

    public function setTagsAttribute($value)
    {
        $this->attributes['tags'] = serialize($value);
    }

    public function getFeaturedHostsAttribute($value)
    {
        return unserialize($value);
    }

    public function setFeaturedHostsAttribute($value)
    {
        $this->attributes['featured_hosts'] = serialize($value);
    }

    public function categories() {
        return $this->belongsToMany(Category::class, 'category_episode');
    }

    public function show() {
        return $this->belongsTo(Show::class, 'show_id');
    }
}
