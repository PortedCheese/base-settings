<?php

namespace PortedCheese\BaseSettings\Console\Commands;

class BaseMakeCommand extends BaseConfigModelCommand
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    // TODO: add more options.
    protected $signature = 'make:base-settings
                    {--views : Only scaffold views}
                    {--force : Overwrite existing views by default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold basic models';

    protected $packageName = "BaseSettings";

    /**
     * The models that need to be exported.
     * @var array
     */
    protected $models = [
        'Role', 'Image', 'User',
    ];

    /**
     * The filters that need to be exported.
     * @var array
     */
    protected $filters = [
        'Avatar.stub' => 'Avatar.php',
        'SmallAvatar.stub' => 'SmallAvatar.php',
    ];

    /**
     * The views that need to be exported.
     * @var array
     */
    protected $views = [
        'admin/layout.stub' => 'admin/layout.blade.php',

        'layouts/admin.stub' => 'layouts/admin.blade.php',
        'layouts/app.stub' => 'layouts/app.blade.php',
        'layouts/boot.stub' => 'layouts/boot.blade.php',
        'layouts/paper.stub' => 'layouts/paper.blade.php',

        'auth/login-modal.stub' => 'auth/login-modal.blade.php',
        'auth/ajax-login.stub' => 'auth/ajax-login.blade.php',
    ];

    protected $controllers = [
        'Admin' => ['UserController', "SettingsController"],
        'Site' => ['ProfileController'],
    ];

    protected $vueIncludes = [
        'admin' => [
            'confirm-delete-model-button' => "ConfirmDeleteModelButtonComponent",
            'gallery' => "GalleryComponent",
        ],
        'app' => [],
    ];

    protected $vueFolder = "base-settings";

    protected $jsIncludes = [
        'app' => ['app-base'],
        'admin' => ['admin-base'],
    ];

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
     * Execute the console command.
     */
    public function handle()
    {
        $this->createDirectories();

        $this->exportViews();

        if (!$this->option('views')) {
            $this->exportModels();
            $this->exportFilters();

            $this->exportControllers("Admin");
            $this->exportControllers("Site");

            $this->makeVueIncludes('admin');
            $this->makeVueIncludes('app');

            $this->makeJsIncludes('admin');
            $this->makeJsIncludes('app');
        }
    }

    /**
     * Export the authentication views.
     *
     * @return void
     */
    protected function exportViews()
    {
        foreach ($this->views as $key => $value) {
            if (
                file_exists($view = resource_path('views/'.$value)) &&
                !$this->option('force')
            ) {
                if (! $this->confirm("The [{$value}] view already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            copy(
                __DIR__.'/stubs/make/views/'.$key,
                $view
            );

            $this->info("View [{$value}] generated successfully.");
        }
    }

    /**
     * Create the directories for the files.
     *
     * @return void
     */
    protected function createDirectories()
    {
        if (! is_dir($directory = resource_path('views/layouts'))) {
            mkdir($directory, 0755, true);
        }

        if (! is_dir($directory = resource_path('views/admin'))) {
            mkdir($directory, 0755, true);
        }

        if (! is_dir($directory = app_path('Filters'))) {
            mkdir($directory, 0755, true);
        }
    }

    /**
     * Create filters files.
     */
    protected function exportFilters()
    {
        foreach ($this->filters as $key => $value) {
            if (file_exists(app_path("Filters/{$value}"))) {
                if (!$this->confirm("The [{$value}] filter already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            file_put_contents(
                app_path("Filters/{$value}"),
                $this->compileFilterStub($key)
            );

            $this->info("Filter [{$value}] generated successfully.");
        }
    }

    /**
     * Переписать что бы помещались не в вендор.
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
            if (file_exists(app_path("Http/Controllers/{$place}/{$controller}.php"))) {
                if (! $this->confirm("The [{$place}/$controller.php] controller already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            if (! is_dir($directory = app_path("Http/Controllers/{$place}"))) {
                mkdir($directory, 0755, true);
            }

            file_put_contents(
                app_path("Http/Controllers/{$place}/{$controller}.php"),
                $this->compileControllerStub($place, $controller)
            );

            $this->info("[{$place}/$controller.php] created");
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
            file_get_contents(__DIR__ . "/stubs/make/controllers/{$place}{$controller}.stub")
        );
    }

    /**
     * Replace namespace in filter.
     *
     * @param $filter
     * @return mixed
     */
    protected function compileFilterStub($filter)
    {
        return str_replace(
            '{{namespace}}',
            $this->namespace,
            file_get_contents(__DIR__ . "/stubs/make/filters/$filter")
        );
    }

    /**
     * Compiles the CheckRole stub.
     *
     * @return string
     */
    protected function compileMiddlewareStub()
    {
        return str_replace(
            '{{namespace}}',
            $this->getAppNamespace(),
            file_get_contents(__DIR__.'/stubs/make/middleware/CheckRole.stub')
        );
    }
}
