<?php

namespace PortedCheese\BaseSettings\Traits;

use App\Image;
use Illuminate\Http\Request;

trait ShouldGallery
{
    protected static function bootShouldGallery()
    {
        static::deleting(function($model) {
            // Чистим галлерею.
            $model->clearImages();
        });
    }

    /**
     * Галерея.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function images() {
        return $this->morphMany(Image::class, 'imageable');
    }

    /**
     * Обложка.
     *
     * @return mixed
     */
    public function cover()
    {
        return $this->morphOne(Image::class, "imageable")->oldest("weight");
    }

    /**
     * Удалить все изображения.
     */
    public function clearImages()
    {
        foreach ($this->images as $image) {
            $image->delete();
        }
    }
}