<?php

namespace PortedCheese\BaseSettings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class BaseConfigModelCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The models that need to be exported.
     * @var array
     */
    protected $models = [];

    protected $configName = '';

    protected $configValues = [];

    protected $namespace = '';

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
     * Добавить настройки новостей по умолчанию.
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

            file_put_contents(
                app_path($model),
                $this->compileModetStub($key)
            );

            $this->info("Model [{$model}] generated successfully.");
        }
    }

    /**
     * Replace namespace in model.
     *
     * @param $model
     * @return mixed
     */
    protected function compileModetStub($model)
    {
        return str_replace(
            '{{namespace}}',
            $this->namespace,
            file_get_contents("{$this->dir}/stubs/make/models/{$model}")
        );
    }
}
