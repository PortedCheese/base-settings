<?php

namespace PortedCheese\BaseSettings\Traits;

use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use PortedCheese\BaseSettings\Events\ImageUpdate;

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

    /**
     * Upload base64 encoded image to path
     *
     * @param $image64
     * @param $path
     * @param $field
     * @return void
     */
    public function uploadBase64GalleryImage($image64, $path, $field = "title")
    {
        if (! $image64) return;
        try {
            $decode = base64_decode($image64);
            $makePath =  $path . "/" . $this->makeImageGalleryName($decode);
            Storage::disk("public")->put($makePath, $decode);

            if (! empty($this->{$field})) {
                $fileName = $this->{$field};
            }
            else {
                $fileName = "";
            }
            $this->clearImages();

            $image = Image::create([
                'path' => $makePath,
                'name' => $fileName,
            ]);

            $this->images()->save($image);
            $image->setMax();
            event(new ImageUpdate($image, "created"));

        }
        catch (\Exception $exception) {
            Log::error("Не удалось загрузить изображения для {$this->slug}");
            return;
        }
    }

    /**
     * Upload image to Path from Url
     * @param $url
     * @param $path
     * @param $field
     * @return void
     */

    public function uploadUrlGalleryImage($url, $path, $field = "title")
    {
        if (! $url) return;

        try{
            $contents = file_get_contents($url);
            if (! $contents)
                return;
        }
        catch (\Exception $e){
            Log::error("Не доступно изображение для {$this->{$field}}");
            return;
        }

        try {
            $makePath =  $path . "/" . $this->makeImageGalleryName($contents);
            Storage::disk("public")->put($makePath, $contents);

            if (! empty($this->{$field})) {
                $fileName = $this->{$field};
            }
            else {
                $fileName = "";
            }
            $this->clearImages();

            $image = Image::create([
                'path' => $makePath,
                'name' => $fileName,
            ]);

            $this->images()->save($image);
            $image->setMax();
            event(new ImageUpdate($image, "created"));
        }
        catch (\Exception $exception) {
            Log::error("Не удалось загрузить изображения для {$this->{$field}} $exception");
            return;
        }
    }

    /**
     * Make random image name
     *
     * @param $contents
     * @return string
     */

    protected function makeImageGalleryName($contents){
        $mimeType = finfo_buffer( finfo_open(), $contents, FILEINFO_MIME_TYPE);//mime_content_type($image);
        $extension = explode('/', $mimeType)[1];
        $imageName = Str::random(40).'.' . $extension;
        return $imageName;
    }
}