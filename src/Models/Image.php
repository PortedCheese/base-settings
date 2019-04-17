<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageCache;

class Image extends Model {

    protected $fillable = [
        'path',
        'name',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($image) {
            // Чистим кэши изображения.
            $image->cacheClear();
            // Удаляем с диска картинку.
            Storage::delete($image->path);
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
     * У пользователя есть аватар.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user() {
        return $this->hasOne('App\User', 'avatar_id');
    }

    /**
     * Получить путь к файлу для вывода.
     *
     * @return mixed
     */
    public function getStorageAttribute() {
        return Storage::url($this->path);
    }

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
     * Elfляем все кэши размеров у картинки.
     */
    public function cacheClear() {
        $path = $this->image_path;
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
