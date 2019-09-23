<?php

namespace PortedCheese\BaseSettings\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteConfig extends Model
{
    protected $fillable = [
        'name',
        'title',
        'data',
        'template',
        'package',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    protected static function boot()
    {
        parent::boot();

        self::created(function (self $model) {
            $model->forgetCache();
        });

        self::deleted(function (self $model) {
            $model->forgetCache();
        });

        self::updated(function (self $model) {
            $model->forgetCache();
        });
    }

    public static function getByName($name)
    {
        $key = "config-$name";
        return Cache::rememberForever($key, function () use ($name) {
            try {
                $config = self::query()
                    ->where("name", $name)
                    ->firstOrFail();
                return $config->data;
            }
            catch (\Exception $exception) {
                return null;
            }
        });
    }

    public function forgetCache()
    {
        $name = $this->name;
        $key = "config-$name";
        if (Cache::get($key)) {
            Cache::forget($key);
        }
    }
}
