<?php

namespace PortedCheese\BaseSettings\Helpers;

use App\Image;
use App\ImageFilter;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Illuminate\Support\Str;

class FilterActionsManager
{
    public function findByName(string $fileName)
    {
        try {
            return Image::query()
                ->where("path", "like", "%{$fileName}")
                ->firstOrFail();
        } catch (\Exception $exception) {
            //abort(404);
            return false;
        }
    }

    /**
     * @param string $template
     * @param object $img
     * @return mixed
     */

    public  function makeObjectFilterContent(string $template, object $img){
        $class = $this->getTemplate($template);
        $manager = new ImageManager(config("image"));
        $path = $this->getObjectPath($img);
        $intImage = $manager->make(asset($path));
        $newImage = $intImage->filter($class);
        return $newImage->response()->getContent();
    }

    /**
     *
     *
     * @param object $img
     * @return string
     */
    public  function getObjectOriginalPath(object $img){
        return asset($this->getObjectPath($img));
    }

    /**
     * Returns full image path from given filename
     *
     * @return string
     */
    protected function getObjectPath(object $img) {
        foreach (config('image-filter.paths') as $path) {
            $image_path = $path . '/' . str_replace('..', '', $img->file_name);
            if (file_exists($image_path) && is_file($image_path)) {
                return $image_path;
            }
        }
        return '';
    }

    /**
     * @param string $template
     * @param Image $img
     * @return mixed|string
     */
    public function getFilteredPath(string $template, Image $img)
    {
        $filtered = $this->getFilteredImage($template, $img->id);
        if (! empty($filtered)) {
            return $filtered->path;
        }
        else {
            return $this->makeImageFilter($template, $img);
        }
    }

    /**
     * @param string $template
     * @param Image $img
     * @return string|null
     */
    public function getFilteredContent(string $template, Image $img)
    {
        $filtered = $this->getFilteredImage($template, $img->id);
        if (! empty($filtered)) {
            return Storage::get($filtered->path);
        }
        else {
            return Storage::get($this->makeImageFilter($template, $img));
        }
    }

    protected function getFilteredImage(string $template, int $id)
    {
        return ImageFilter::query()
            ->where("image_id", $id)
            ->where("template", $template)
            ->first();
    }


    protected function makeImageFilter(string $template, Image $img)
    {
        $class = $this->getTemplate($template);
        $manager = new ImageManager(config("image"));
        $intImage = $manager->make($img->storage);
        $newImage = $intImage->filter($class);
        $content = $newImage->response()->getContent();

        $image_id = $img->id;
        $path = "filters/{$template}-{$img->id}-" . Str::random(40);
        Storage::put($path, $content);
        $filter = ImageFilter::create([
            'image_id' => $image_id,
            'template' => $template,
            'path' => $path
        ]);
        $filter->save();

        return $path;//$content;
    }

    protected function getTemplate(string $name)
    {
        $template = config("image-filter.templates.{$name}");
        switch (true) {
            // closure template found
            case is_callable($template):
                return $template;

            // filter template found
            case class_exists($template):
                return new $template;

            default:
                // template not found
                abort(404);
                break;
        }
    }
}
