<?php


namespace PortedCheese\BaseSettings\Helpers;


use PortedCheese\BaseSettings\Models\SiteConfig as ConfigModel;

class ConfigManager
{
    protected $configData;

    public function __construct()
    {
        $this->configData = null;
    }

    /**
     * Получить конфигурацию.
     *
     * @param string $name
     * @param string $value
     * @param null $default
     * @return mixed|null
     */
    public function get(string $name, $value = "", $default = null)
    {
        $data = ConfigModel::getByName($name);
        $this->configData = [];

        if (! empty($value)) {
            $this->configData = ! empty($data[$value]) ? $data[$value] : $default;
        }
        else {
            $this->configData = $data;
        }

        return $this->configData;
    }

    /**
     * Создание нового конфига.
     *
     * @param string $name
     * @param array $data
     * @param array $info
     * @param bool $force
     * @return string|void|null
     */
    public function create(string $name, array $data, array $info, $force = false)
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

        return $this->configData;
    }

    /**
     * Проверка, можно ли создать.
     *
     * @param $info
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
     * Обновить значение конфига.
     *
     * @param string $name
     * @param string $var
     * @param $value
     * @return void|null
     */
    public function update(string $name, string $var, $value)
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
        $this->configData = $data;

        return $this->configData;
    }
}