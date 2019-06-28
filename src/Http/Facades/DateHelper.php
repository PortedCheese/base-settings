<?php
/**
 * Created by PhpStorm.
 * User: vladimirpeskov
 * Date: 2019-06-28
 * Time: 09:51
 */

namespace PortedCheese\BaseSettings\Http\Facades;


use Illuminate\Support\Facades\Facade;

class DateHelper extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "datehelper";
    }
}