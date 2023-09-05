<?php

namespace PortedCheese\BaseSettings\Traits;

use App\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait ShouldImage
{
    protected static function bootShouldImage()
    {
        static::deleting(function($model) {
            // Удаляем главное изображение.
            $model->clearImage();
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
     * Upload base64 encoded image to path
     *
     * @param $image64
     * @param $path
     * @param $field
     * @return void
     */
    public function uploadBase64Image($image64, $path, $field = "title")
    {
        if (! $image64) return;
        try {
            $decode = base64_decode($image64);
            $makePath =  $path . "/" . $this->makeImageName($decode);
            Storage::disk("public")->put($makePath, $decode);

            if (! empty($this->{$field})) {
                $fileName = $this->{$field};
            }
            else {
                $fileName = "";
            }
            $this->clearImage();

            $image = Image::create([
                'path' => $makePath,
                'name' => $fileName,
            ]);
            $this->image()->associate($image);
            $this->save();
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

    public function uploadUrlImage($url, $path, $field = "title")
    {
        if (! $url) return;
        $headers = @get_headers($url);
        if(strpos('200', $headers[0])) {
            $contents = file_get_contents($url);
            if (! $contents)
                return;
        }
        else{
            Log::error("Не доступно изображение для {$this->{$field}}");
            return;
        }
        try {
            $makePath =  $path . "/" . $this->makeImageName($contents);
            Storage::disk("public")->put($makePath, $contents);

            if (! empty($this->{$field})) {
                $fileName = $this->{$field};
            }
            else {
                $fileName = "";
            }
            $this->clearImage();

            $image = Image::create([
                'path' => $makePath,
                'name' => $fileName,
            ]);
            $this->image()->associate($image);
            $this->save();
        }
        catch (\Exception $exception) {
            Log::error("Не удалось загрузить изображения для {$this->{$field}}");
            return;
        }
    }

    /**
     * Make random image name
     *
     * @param $contents
     * @return string
     */

    protected function makeImageName($contents){
        $mimeType = finfo_buffer( finfo_open(), $contents, FILEINFO_MIME_TYPE);//mime_content_type($image);
        $extension = explode('/', $mimeType)[1];
        $imageName = Str::random(40).'.' . $extension;
        return $imageName;
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
}