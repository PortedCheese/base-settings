<?php

namespace PortedCheese\BaseSettings\Facades;

/**
 * @method static get(string $name, $value = "", $default = null)
 *
 * @see \PortedCheese\BaseSettings\Helpers\SiteConfig
 */

use Illuminate\Support\Facades\Facade;

class SiteConfig extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "siteconf";
    }

}