<?php
namespace PortedCheese\BaseSettings\Facades;

use Illuminate\Support\Facades\Facade;

class SmartCaptcha extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "smartcaptcha";
    }
}