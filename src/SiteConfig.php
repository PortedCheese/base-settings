<?php
/**
 * Created by PhpStorm.
 * User: vladimirpeskov
 * Date: 2019-04-25
 * Time: 16:01
 */

namespace PortedCheese\BaseSettings;


class SiteConfig
{

    public function __construct($app = null)
    {
        if (!$app) {
            $app = app();   //Fallback when $app is not given
        }
        $this->app = $app;
        $this->version = $app->version();

        $this->configData = null;
        $this->configName = 'siteconfigurations';
        $this->default = true;
    }

    public function __call($method, $args)
    {
        $configActions = ['get', 'set', 'save'];
        if (in_array($method, $configActions)) {
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

            case 'set':
                $this->makeSetMethod($args);
                break;

            case 'save':
                $this->makeSaveMethod($args);
                break;
        }
    }

    protected function makeSaveMethod($args)
    {
        $this->configData = $this;
        if (!$this->checkSave($args)) {
            return;
        }
        list($name, $data) = $args;
        $config = config($this->configName);
        $config[$name] = $data;
        file_put_contents(
            config_path($this->configName . '.php'),
            $this->makeSaveStr($config)
        );
    }

    protected function makeSaveStr($config)
    {
        $str = var_export($config, true);
        return "<?php return $str;";
    }

    protected function checkSave($args)
    {
        if (!$this->default) {
            return false;
        }
        if (count($args) != 2) {
            return false;
        }
        return true;
    }

    protected function makeGetMethod($args)
    {
        $this->configData = [];
        foreach ($args as $arg) {
            $this->configData[$arg] = $this->getByName($arg);
        }
        if (count($this->configData) == 1) {
            $arrayKeys = array_keys($this->configData);
            $this->configData = $this->configData[array_first($arrayKeys)];
        }
    }

    protected function getByName($name)
    {
        $key = $this->configName . '.' . $name;
        return config($key);
    }

    protected function makeSetMethod($args)
    {
        if (count($args)) {
            $config = array_shift($args);
            $this->configName = $config;
        }
        $this->default = false;
        $this->configData = $this;
    }

}