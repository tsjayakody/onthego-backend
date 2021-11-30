<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Hoster extends Model implements HasMedia
{
    use HasFactory;
    use HasSlug;
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'description',
        'linkedin',
        'facebook',
        'instagram',
        'youtube',
        'slug',
        'designation',
        'is_approved',
        'user_name',
        'access_code'
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
            ->addMediaCollection('hoster-image-collection')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg'])
            ->useFallbackUrl('/noimage.png')
            ->useFallbackPath(public_path('/noimage.png'))
            ->singleFile();
    }

    protected $appends = ['hoster_image'];

    public function getCoverImageAttribute()
    {
        return $this->getFirstMediaUrl('hoster-image-collection');
    }
}
