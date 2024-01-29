<?php

namespace PortedCheese\BaseSettings\Facades;

use Illuminate\Support\Facades\Facade;
use App\Image;

/**
 * @method static Image findByName(string $fileName)
 * @method static mixed|string getFilteredContent(string $template, Image $img)
 * @method static mixed|string getFilteredPath(string $template, Image $img)
 * @method static mixed makeObjectFilterContent(string $template, Object $img)
 * @method static getObjectOriginalPath(Object $img)
 *
 * @see ThumbnailActionsManager
 */
class FilterActions extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "filter-actions";
    }
}
