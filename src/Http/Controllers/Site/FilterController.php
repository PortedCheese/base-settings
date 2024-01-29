<?php

namespace PortedCheese\BaseSettings\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Image;
use Illuminate\Http\Response as IlluminateResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use PortedCheese\BaseSettings\Facades\FilterActions;

class FilterController extends Controller
{

    /**
     * Show filtered image
     *
     * @param string $template
     * @param string $fileName
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|IlluminateResponse|\Illuminate\Routing\Redirector|never
     */

    public function show(string $template, string $fileName) {
        // изобрражение из БД
        $key = "image-filters:{$template}-{$fileName}";
        $filterPath = Cache::rememberForever($key,function () use ($template, $fileName){
            $img = FilterActions::findByName($fileName);
            if (! $img) return false;
            switch ($template) {
                case "original":
                    return $img->path;
                default:
                    return FilterActions::getFilteredPath($template, $img);
            }
        });
        if ($filterPath)  return $this->buildResponse(Storage::get($filterPath));

        // изображение не из БД (верстка)
        $obj = (object) ["id" => $fileName, "path" => "", "file_name" => $fileName ];
        $ttl = 60*config("image-filter.lifetime", 0);
        // original
        if ($template == 'original') {
            $key = "object-filters-original:{$fileName}";
            $filterPath = Cache::remember($key,$ttl, function () use ($obj)
            {
                return FilterActions::getObjectOriginalPath($obj);
            });
            if ($filterPath == '') return abort(404);
            return redirect($filterPath);
        }
        // filter
        $key = "object-filters-content:{$template}-{$fileName}";
        $content = Cache::remember($key, $ttl, function () use ($template, $obj){
            return  FilterActions::makeObjectFilterContent($template, $obj);
        });
        return $this->buildResponse($content);
    }


    /**
     *
     * @param string $template
     * @param Image $img
     * @return IlluminateResponse
     */
    protected function makeImage(string $template, Image $img)
    {
        $content = FilterActions::getFilteredContent($template, $img);
        return $this->buildResponse($content);
    }

    /**
     * @param $content
     * @return IlluminateResponse
     */
    protected function buildResponse($content)
    {
        // define mime type
        $mime = finfo_buffer(finfo_open(FILEINFO_MIME_TYPE), $content);

        // respond with 304 not modified if browser has the image cached
        $etag = md5($content);
        $not_modified = isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag;
        $content = $not_modified ? NULL : $content;
        $status_code = $not_modified ? 304 : 200;

        // return http response
        return new IlluminateResponse($content, $status_code, array(
            'Content-Type' => $mime,
            'Cache-Control' => 'max-age='.(config('image-filter.lifetime')*60).', public',
            'Content-Length' => strlen($content),
            'Etag' => $etag
        ));
    }


}
