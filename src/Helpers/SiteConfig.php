<?php

namespace PortedCheese\BaseSettings\Helpers;

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
                call_user_func_array([$this, "makeGetMethod"], $args);
                break;

            case "create":
                call_user_func_array([$this, "makeCreateMethod"], $args);
                break;

            case "update":
                call_user_func_array([$this, "makeUpdateMethod"], $args);
                break;
        }
    }

    /**
     * Обновить значение конфига.
     *
     * @param string $name
     * @param string $var
     * @param mixed $value
     */
    protected function makeUpdateMethod(string $name, string $var, $value)
    {
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
     * @param string $name
     * @param array $data
     * @param array $info
     * @param bool $force
     */
    protected function makeCreateMethod(string $name, array $data, array $info, $force = false)
    {
        // Проверяем, достаточно ли данных для создания.
        if (! $this->checkCreate($info)) {
            return;
        }
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
            if ($force) {
                $config->update($modelData);

                $this->configData = "replaced";
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
    protected function checkCreate($info)
    {
        if (! is_array($info)) {
            $this->configData = "third arg not array";
            return false;
        }
        if (empty($info['title']) || empty($info['template'])) {
            $this->configData = "title or template is empty";
            return false;
        }
        return true;
    }

    /**
     * Получить значения конфигов.
     *
     * @param string $name
     * @param string $value
     * @param mixed|null $default
     */
    protected function makeGetMethod(string $name, $value = "", $default = null)
    {
        $data = ConfigModel::getByName($name);
        $this->configData = [];
        if (! empty($value)) {
            $this->configData = ! empty($data[$value]) ? $data[$value] : $default;
        }
        else {
            $this->configData = $data;
        }
    }

}