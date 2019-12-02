<?php
/**
 * Created by PhpStorm.
 * User: vladimirpeskov
 * Date: 2019-07-02
 * Time: 13:15
 */

namespace PortedCheese\BaseSettings\Facades;

use Illuminate\Support\Facades\Facade;

class ReCaptcha extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "geocaptcha";
    }
}