<?php

namespace PortedCheese\BaseSettings\Facades;

/**
 * @method static get(string $name, $value = "", $default = null)
 * @method static create(string $name, array $data, array $info, $force = false)
 * @method static update(string $name, string $var, $value)
 *
 * @see \PortedCheese\BaseSettings\Helpers\ConfigManager
 */

use Illuminate\Support\Facades\Facade;

class BaseConfig extends Facade
{
    protected static function getFacadeAccessor()
    {
        return "base-config";
    }

}