<?php

namespace PortedCheese\BaseSettings\Http\Helpers;


use Illuminate\Support\Arr;
use PortedCheese\BaseSettings\Models\SiteConfig as ConfigModel;

class SiteConfig
{
    const ACTIONS = ['get', 'create'];

    protected $configData;

    public function __construct()
    {
        $this->configData = null;
    }

    public function __call($method, $args)
    {
        if (in_array($method, self::ACTIONS)) {
            $this->methodRouter($method, $args);
        }
        return $this->configData;
    }

    public function methodRouter($method, $args)
    {
        switch ($method) {
            case 'get':
                $this->makeGetMethod($args);
                break;

            case "create":
                $this->makeCreateMethod($args);
                break;
        }
    }

    /**
     * Создание нового конфига.
     *
     * @param $args
     */
    protected function makeCreateMethod($args)
    {
        // Проверяем, достаточно ли данных для создания.
        if (! $this->checkCreate($args)) {
            return;
        }
        list($name, $data, $info) = $args;
        $modelData = [
            'name' => $name,
            'data' => $data,
            'title' => $info['title'],
            'template' => $info['template'],
            'package' => empty($info['pkg']) ? 0 : 1,
        ];
        try {
            $config = ConfigModel::query()
                ->where("name", $name)
                ->firstOrFail();

            $this->configData = "exists";

            // Обновить существующий.
            if (! empty($args[3])) {
                $config->update($modelData);

                $this->configData = "replaces";
            }
        }
        catch (\Exception $exception) {
            ConfigModel::create($modelData);

            $this->configData = "created";
        }
    }

    /**
     * Проверка, можно ли создать.
     *
     * @param $args
     * @return bool
     */
    protected function checkCreate($args)
    {
        if (count($args) < 3) {
            $this->configData = "not enough arg";
            return false;
        }
        if (! is_array($args[2])) {
            $this->configData = "third arg not array";
            return false;
        }
        if (empty($args[2]['title']) || empty($args[2]['template'])) {
            $this->configData = "title or template is empty";
            return false;
        }
        return true;
    }

    /**
     * Получить значения конфигов.
     *
     * @param $args
     */
    protected function makeGetMethod($args)
    {
        if (empty($args)) {
            return;
        }
        $name = array_shift($args);
        $data = ConfigModel::getByName($name);
        $this->configData = [];
        foreach ($args as $arg) {
            if (isset($data[$arg])) {
                $this->configData[$arg] = $data[$arg];
            }
            else {
                $this->configData[$arg] = false;
            }
        }
        // Если аргумент один, то вернется конфиг,
        // если несколько, то вренется набор значений, либо одно значение.
        if (empty($this->configData)) {
            $this->configData = $data;
        }
        elseif (count($this->configData) == 1) {
            $arrayKeys = array_keys($this->configData);
            $this->configData = $this->configData[Arr::first($arrayKeys)];
        }
    }

}