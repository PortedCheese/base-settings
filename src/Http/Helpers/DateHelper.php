<?php

namespace PortedCheese\BaseSettings\Http\Helpers;

use Carbon\Carbon;

class DateHelper
{
    const TZ = "Europe/Moscow";
    const UTC = "Etc/UTC";

    /**
     * Значение для фильтров.
     *
     * @param $value
     * @param bool $to
     * @return mixed
     */
    public static function getForFilterDate($value, $to = false)
    {
        if ($to) {
            $value = "$value 23:59:59";
        }
        else {
            $value = "$value 00:00:00";
        }
        $carbon = Carbon::createFromFormat("Y-m-d H:i:s",  $value, self::TZ);
        $carbon->timezone = self::UTC;
        return $carbon->toDateTimeString();
    }

    /**
     * Изменить временную зону.
     *
     * @param $value
     * @return string
     */
    public static function changeTz($value)
    {
        if (empty($value)) {
            return $value;
        }
        try {
            $carbon = new Carbon($value);
        }
        catch (\Exception $e) {
            return $value;
        }
        $carbon->timezone = self::TZ;
        return $carbon->toDateTimeString();
    }

    /**
     * Отформатировать значение.
     *
     * @param $value
     * @param string $format
     * @return false|string
     */
    public static function formatValue($value, $format = "d.m.Y H:i")
    {
        if (empty($value)) {
            return $value;
        }
        return date($format, strtotime($value));
    }
}
