<?php

namespace PortedCheese\BaseSettings\Helpers;

use Carbon\Carbon;

/**
 * @method Carbon|null forFilter($value, $to = false)
 * @method Carbon|null changeTz($value)
 * @method Carbon|null format($value, $format = "d.m.Y H:i")
 */
class DateHelper
{
    const TZ = "Europe/Moscow";
    const UTC = "Etc/UTC";
    const ACTIONS = [
        'forFilter',
        'changeTz',
        'format',
    ];

    public $timeZone;
    public $date;

    public function __construct($timeZone = self::TZ)
    {
        $this->timeZone = $timeZone;
        $this->date = NULL;
    }

    public function __call($method, $args)
    {
        if (in_array($method, self::ACTIONS)) {
            $this->methodRouter($method, $args);
        }
        return $this->date;
    }

    public function methodRouter($method, $args)
    {
        switch ($method) {
            case 'forFilter':
                call_user_func_array([$this, "getForFilterDate"], $args);
                break;

            case 'changeTz':
                call_user_func_array([$this, "changeTimeZone"], $args);
                break;

            case 'format':
                call_user_func_array([$this, "formatValue"], $args);
                break;
        }
    }

    /**
     * Значение для фильтров.
     *
     * @param $value
     * @param bool $to
     * @return mixed
     */
    protected function getForFilterDate($value, $to = false)
    {
        if ($to) {
            $value = "$value 23:59:59";
        }
        else {
            $value = "$value 00:00:00";
        }
        try {
            $carbon = Carbon::createFromFormat("Y-m-d H:i:s",  $value, $this->timeZone);
            $carbon->timezone = self::UTC;
            $this->date = $carbon->toDateTimeString();
        }
        catch (\Exception $e) {
            $this->date = NULL;
        }
    }

    /**
     * Изменить временную зону.
     *
     * @param $value
     * @return string
     */
    protected function changeTimeZone($value)
    {
        if (empty($value)) {
            return $value;
        }
        try {
            $carbon = new Carbon($value);
        }
        catch (\Exception $e) {
            $this->date = $value;
        }
        $carbon->timezone = $this->timeZone;
        $this->date = $carbon->toDateTimeString();
    }

    /**
     * Отформатировать значение.
     *
     * @param $value
     * @param string $format
     * @return false|string
     */
    protected function formatValue($value, $format = "d.m.Y H:i")
    {
        if (empty($value)) {
            $this->date = $value;
        }
        $this->date = date($format, strtotime($value));
    }
}
