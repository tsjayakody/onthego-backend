<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class SliderItem extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    protected $fillable = ['title', 'description', 'order_index'];

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('slider-image-collection')
            ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/jpg'])
            ->useFallbackUrl('/noimage.png')
            ->useFallbackPath(public_path('/noimage.png'))
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->width(330)
                    ->height(170);
            });
    }

    protected $appends = ['slider_image', 'slider_image_thumb'];

    public function getSliderImageAttribute()
    {
        return $this->getFirstMediaUrl('slider-image-collection');
    }

    public function getSliderImageThumbAttribute()
    {
        return $this->getFirstMediaUrl('slider-image-collection', 'thumb');
    }
}
