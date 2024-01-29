<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;


class ImageFilter extends Model {

    protected $fillable = [
        'path',
        'template',
        'image_id',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($model) {
            // Удаляем с диска картинку.
            Storage::delete($model->path);
        });
    }

    /**
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function image() {
        return $this->belongsTo(\App\Image::class);
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


}
