<?php

namespace PortedCheese\BaseSettings\Http\Facades;

/**
 * @method static void test(string $message)
 *
 * @see \PortedCheese\BaseSettings\SiteConfig
 */


class SiteConfig extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return "siteconf";
    }

}