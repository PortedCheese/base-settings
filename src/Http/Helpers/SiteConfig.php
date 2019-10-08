<?php

namespace PortedCheese\BaseSettings\Http\Helpers;

use PortedCheese\BaseSettings\Models\SiteConfig as ConfigModel;

class SiteConfig
{
    const ACTIONS = ['get', 'create', 'update'];

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

            case "update":
                $this->makeUpdateMethod($args);
                break;
        }
    }

    /**
     * Обновить значение конфига.
     *
     * @param $args
     */
    protected function makeUpdateMethod($args)
    {
        if (count($args) !== 3) {
            return;
        }
        list($name, $var, $value) = $args;

        try {
            $config = ConfigModel::query()
                ->where("name", $name)
                ->firstOrFail();
        }
        catch (\Exception $exception) {
            return;
        }
        $data = $config->data;
        $data[$var] = $value;
        $config->data = $data;
        $config->save();
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
        // Если аргумент один, то вернется конфиг,
        // если несколько, то вренется значение.
        if (empty($args)) {
            return;
        }
        $name = array_shift($args);
        $data = ConfigModel::getByName($name);
        $this->configData = [];
        if (! empty($args)) {
            $value = array_shift($args);
            if (isset($data[$value])) {
                $this->configData = $data[$value];
            }
            elseif (! empty($args[0])) {
                $this->configData = $args[0];
            }
            else {
                $this->configData = false;
            }
            return;
        }
        $this->configData = $data;
    }

}