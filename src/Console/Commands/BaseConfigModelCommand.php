<?php

namespace PortedCheese\BaseSettings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Container\Container;

class BaseConfigModelCommand extends Command
{

    /**
     * Список моделей.
     *
     * @var array
     */
    protected $models = [];

    /**
     * Список контроллеров.
     *
     * @var array
     */
    protected $controllers = [];

    /**
     * Имя пакета.
     *
     * @var string
     */
    protected $packageName = "";

    /**
     * Имя конфига.
     *
     * @var string
     */
    protected $configName = '';

    /**
     * Значения конфига.
     *
     * @var array
     */
    protected $configValues = [];

    /**
     * Namespace.
     *
     * @var mixed|string
     */
    protected $namespace = '';

    /**
     * Vue файлы.
     *
     * @var array
     */
    protected $vueIncludes = [];

    /**
     * Vue folder name.
     *
     * @var string
     */
    protected $vueFolder = "";

    /**
     * Директория.
     *
     * @var string
     */
    protected $dir = __DIR__;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $namespace = $this->getAppNamespace();
        $this->namespace = str_replace("\\", '', $namespace);
    }

    /**
     * Добавить настройки по умолчанию.
     */
    protected function makeConfig()
    {
        $config = siteconf()->get($this->configName);
        if (!empty($config)) {
            if (! $this->confirm("{$this->configName} config already exists. Replace it?")) {
                return;
            }
        }

        siteconf()->save($this->configName, $this->configValues);

        $this->info("Config {$this->configName} added to siteconfig");
    }

    protected function makeVueIncludes()
    {
        $this->compileVueStub("admin");
        $this->compileVueStub("app");
    }

    protected function compileVueStub($key)
    {
        $filePath = resource_path("js/vendor/$key-vue-includes.js");
        // Создать файл для компонентов сайта, если его нет.
        if (! file_exists($filePath)) {
            file_put_contents(
                $filePath,
                ""
            );
            $this->info("Added file $filePath");
        }
        if (! empty($this->vueIncludes[$key])) {
            foreach ($this->vueIncludes[$key] as $name => $vueInclude) {
                if (! $this->confirm("Add $name => $vueInclude.vue component to $filePath?")) {
                    continue;
                }
                $data = [
                    "Vue.component(",
                    "'$name', ",
                    "require('../components/vendor/{$this->vueFolder}/{$vueInclude}.vue').default",
                    ");\n\n",
                ];
                file_put_contents(
                    $filePath,
                    implode("", $data),
                    FILE_APPEND
                );
                $this->info("$name added to $filePath");
            }
        }
    }

    /**
     * Create models files.
     */
    protected function exportModels()
    {
        foreach ($this->models as $key => $model) {
            if (file_exists(app_path($model))) {
                if (!$this->confirm("The [{$model}] model already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            try {
                file_put_contents(
                    app_path($model),
                    $this->compileModelStub($key)
                );

                $this->info("Model [{$model}] generated successfully.");
            }
            catch (\Exception $e) {
                $this->error("Filed put model");
            }
        }
    }

    /**
     * Replace namespace in model.
     *
     * @param $model
     * @return mixed
     */
    protected function compileModelStub($model)
    {
        return str_replace(
            '{{namespace}}',
            $this->namespace,
            file_get_contents("{$this->dir}/stubs/make/models/{$model}")
        );
    }

    /**
     * Создать контроллеры.
     *
     * @param $place
     */
    protected function exportControllers($place)
    {
        if (empty($this->controllers[$place])) {
            $this->info("$place not found in controllers");
            return;
        }
        foreach ($this->controllers[$place] as $controller) {
            if (file_exists(app_path("Http/Controllers/Vendor/{$this->packageName}/{$place}/{$controller}.php"))) {
                if (! $this->confirm("The [{$place}/$controller.php] controller already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            if (! is_dir($directory = app_path("Http/Controllers/Vendor/{$this->packageName}/{$place}"))) {
                mkdir($directory, 0755, true);
            }


            try {
                file_put_contents(
                    app_path("Http/Controllers/Vendor/{$this->packageName}/{$place}/{$controller}.php"),
                    $this->compileControllerStub($place, $controller)
                );

                $this->info("[{$place}/$controller.php] created");
            }
            catch (\Exception $e) {
                $this->error("Failed put controller");
            }
        }
    }

    /**
     * Compiles the Controller stub.
     *
     * @return string
     */
    protected function compileControllerStub($place, $controller)
    {
        return str_replace(
            '{{namespace}}',
            $this->getAppNamespace(),
            file_get_contents("{$this->dir}/stubs/make/controllers/{$place}{$controller}.stub")
        );
    }

    protected function getAppNamespace() {
        return Container::getInstance()->getNamespace();
    }
}
