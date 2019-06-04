<?php

namespace PortedCheese\BaseSettings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class BaseOverrideCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The models that need to be exported.
     * @var array
     */
    protected $controllers = [];

    protected $packageName = '';

    protected $dir = __DIR__;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Добавляет роуты.
     */
    protected function expandSiteRoutes($place)
    {
        if (! $this->confirm("Do you want add routes to {$place}.php file?")) {
            return;
        }

        try {
            file_put_contents(
                base_path("routes/{$place}.php"),
                file_get_contents($this->dir . "/stubs/make/{$place}.stub"),
                FILE_APPEND
            );

            $this->info("Routes added to {$place}.php");
        }
        catch (\Exception $e) {
            $this->error("Failed append to file");
        }
    }

    /**
     * Create controllers.
     */
    protected function createControllers($place)
    {
        if (empty($this->controllers[$place])) {
            $this->info("$place not found in controllers");
            return;
        }
        foreach ($this->controllers[$place] as $controller) {
            if (file_exists(app_path("Http/Controllers/{$this->packageName}/{$place}/{$controller}.php"))) {
                if (! $this->confirm("The [{$place}/$controller.php] controller already exists. Do you want to replace it?")) {
                    return;
                }
            }

            if (! is_dir($directory = app_path("Http/Controllers/{$this->packageName}/{$place}"))) {
                mkdir($directory, 0755, true);
            }


            try {
                file_put_contents(
                    app_path("Http/Controllers/{$this->packageName}/{$place}/{$controller}.php"),
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
}
