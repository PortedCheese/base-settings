<?php

namespace PortedCheese\BaseSettings\Facades;

/**
 * @method static void test(string $message)
 *
 * @see \PortedCheese\BaseSettings\SiteConfig
 */

use Illuminate\Support\Facades\Facade;

class SiteConfig extends Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return "siteconf";
    }

}