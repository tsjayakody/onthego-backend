<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Category extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use InteractsWithMedia;

    protected $fillable = ['name', 'slug'];

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
            ->addMediaCollection('cover-image-collection')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg'])
            ->useFallbackUrl('/noimage.png')
            ->useFallbackPath(public_path('/noimage.png'))
            ->singleFile();
    }

    protected $appends = ['cover_image', 'episode_count'];

    // get cover image which is associate with the category
    public function getCoverImageAttribute()
    {
        return $this->getFirstMediaUrl('cover-image-collection');
    }

    // the episodes that belongs to the category
    public function episodes()
    {
        return $this->belongsToMany(Episode::class, 'category_episode', 'category_id', 'episode_id');
    }

    // get the associated episodes count
    public function getEpisodeCountAttribute()
    {
        return $this->episodes()->count();
    }
}
