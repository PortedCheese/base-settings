<?php

namespace PortedCheese\BaseSettings\Traits;

use Illuminate\Support\Str;

trait ShouldSlug
{
    protected static function bootShouldSlug()
    {
        static::creating(function($model) {
            // Записать slug.
            $model->fixSlug();
        });

        static::updating(function ($model) {
            // Записать slug.
            $model->fixSlug(true);
        });
    }

    /**
     * Искать по столбцу slug.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Получить поле для формирования slug.
     *
     * @return string
     */
    protected function getSlugKey()
    {
        if (! empty($this->slugKey)) {
            return $this->slugKey;
        }
        else {
            return "title";
        }
    }

    /**
     * Исправить slug.
     *
     * @param bool $updating
     * @param string $key
     */
    public function fixSlug($updating = false)
    {
        if ($updating && ($this->original["slug"] == $this->slug)) {
            return;
        }
        if (empty($this->slug)) {
            $slug = $this->{$this->getSlugKey()};
        }
        else {
            $slug = $this->slug;
        }
        $slug = Str::slug($slug);
        $buf = $slug;
        $i = 1;
        if ($updating) {
            $id = $this->id;
        }
        else {
            $id = 0;
        }
        while (self::query()
            ->select("id")
            ->where("slug", $buf)
            ->where("id", "!=", $id)
            ->count())
        {
            $buf = $slug . "-" . $i++;
        }
        $this->slug = $buf;
    }
}