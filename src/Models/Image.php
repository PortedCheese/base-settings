<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use PortedCheese\BaseSettings\Events\ImageUpdate;

class Image extends Model {

    protected $fillable = [
        'path',
        'name',
        'weight',
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($model) {
            event(new ImageUpdate($model, "updated"));
        });

        static::deleting(function ($model) {
            // Чистим кэши изображения.
            $model->cacheClear();
            // Удаляем с диска картинку.
            Storage::delete($model->path);
            $model->filtersClear();

            event(new ImageUpdate($model, "deleting"));
        });
    }

    /**
     * Картинка может относится к любому материалу.
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function imageable() {
        return $this->morphTo();
    }

    /**
     * Фильтры
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function filters(){
        return $this->hasMany(\App\ImageFilter::class, 'image_id')->orderBy("template");
    }

    /**
     * Получить путь к файлу для вывода.
     *
     * @return mixed
     */
    public function getStorageAttribute() {
        return Storage::url($this->path);
    }

    /**
     * Имя файла.
     *
     * @return mixed
     */
    public function getFileNameAttribute() {
        $exploded = explode('/', $this->path);
        return $exploded[count($exploded) - 1];
    }

    /**
     * Returns full image path from given filename
     *
     * @return string
     */
    public function getImagePathAttribute() {
        foreach (config('imagecache.paths') as $path) {
            $image_path = $path . '/' . str_replace('..', '', $this->file_name);
            if (file_exists($image_path) && is_file($image_path)) {
                return $image_path;
            }
        }
        return '';
    }


    /**
     * Подготовить для js.
     *
     * @param $modelObject
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public static function prepareImage($modelObject)
    {
        $collection = $modelObject->images->sortBy('id')->sortBy('weight');
        return \PortedCheese\BaseSettings\Http\Resources\Image::collection($collection);
    }

    /**
     * Найти модель по имени в конфиге.
     *
     * @param $modelName
     * @param $id
     * @return bool
     */
    public static function getGalleryModel($modelName, $id)
    {
        $model = false;
        foreach (config('gallery.models') as $name => $class) {
            if (
                $name == $modelName &&
                class_exists($class)
            ) {
                try {
                    $model = $class::findOrFail($id);
                } catch (\Exception $e) {
                    return false;
                }
                break;
            }
        }
        return $model;
    }

    /**
     * Получить имя класса по имени из конфига.
     *
     * @param $modelName
     * @return bool|mixed
     */
    public static function getModelClass($modelName)
    {
        foreach (config('gallery.models') as $name => $class) {
            if ($name == $modelName) {
                return $class;
            }
        }
        return false;
    }

    /**
     * Получить следующий вес.
     */
    public function setMax()
    {
        $max = \App\Image::query()
            ->where("imageable_type", $this->imageable_type)
            ->where("imageable_id", $this->imageable_id)
            ->max("weight");
        $this->weight = $max + 1;
        $this->save();
    }

    /**
     * Удаляем все кэши размеров у картинки.
     */
    public function cacheClear() {
        $path = $this->image_path;
        if (class_exists(ImageCache::class)){
            foreach (config('imagecache.templates') as $key => $filter) {
                $imageCache = new ImageCache();
                if (class_exists($filter)) {
                    $filter = new $filter;
                }
                $cached = $imageCache
                    ->make($path)
                    ->filter($filter);
                $sum = $cached->checksum();
                if (Cache::has($sum)) {
                    Cache::forget($sum);
                }
            }
        }
    }
    /**
     * Удаляем все кэши фильтры у картинки.
     */
    public function filtersClear() {
        foreach ($this->filters as $item)
        {
            //чистим кэш
            Cache::forget("image-filters:{$item->template}-{$this->file_name}");
            $item->delete();
        }
    }
}
