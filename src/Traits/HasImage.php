<?php

namespace PortedCheese\BaseSettings\Traits;

use App\Image;
use Illuminate\Http\Request;

trait HasImage
{
    protected static function imageBoot()
    {
        static::deleting(function($model) {
            // Удаляем главное изображение.
            $model->clearImage();
            // Чистим галлерею.
            $model->clearImages();
        });
    }

    protected function getImageKey()
    {
        if ($this->imageKey) {
            return $this->imageKey;
        }
        else {
            return "image_id";
        }
    }

    /**
     * Основное изображение.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image()
    {
        return $this->belongsTo(Image::class, $this->getImageKey());
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
     * Изменить/создать главное изображение.
     *
     * @param Request $request
     * @param $path
     * @param string $name
     * @param string $field
     */
    public function uploadImage(Request $request, $path, $name = "image", $field = "title")
    {
        if ($request->hasFile($name)) {
            $this->clearImage();
            $path = $request->file($name)->store($path);
            if (! empty($this->{$field})) {
                $fileName = $this->{$field};
            }
            else {
                $fileName = $request->file($name)->getClientOriginalName();
            }
            $image = Image::create([
                'path' => $path,
                'name' => $fileName,
            ]);
            $this->image()->associate($image);
            $this->save();
        }
    }

    /**
     * Удалить изображение.
     */
    public function clearImage()
    {
        $image = $this->image;
        if (!empty($image)) {
            $image->delete();
        }
        $this->image()->dissociate();
        $this->save();
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