<?php

namespace PortedCheese\BaseSettings\Facades;

use Illuminate\Support\Facades\Facade;
use Carbon\Carbon;

/**
 * @method static Carbon|null forFilter($value, $to = false)
 * @method static Carbon|null changeTz($value)
 * @method static Carbon|null format($value, $format = "d.m.Y H:i")
 *
 * @see \PortedCheese\BaseSettings\Helpers\DateHelper
 */
class DateHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "datehelper";
    }
}