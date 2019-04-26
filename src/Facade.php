<?php
/**
 * Created by PhpStorm.
 * User: vladimirpeskov
 * Date: 2019-04-25
 * Time: 16:00
 */

namespace PortedCheese\BaseSettings;

/**
 * @method static void test(string $message)
 *
 * @see \PortedCheese\BaseSettings\SiteConfig
 */


class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return SiteConfig::class;
    }

}