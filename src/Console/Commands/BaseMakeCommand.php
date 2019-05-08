<?php

namespace PortedCheese\BaseSettings\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\DetectsApplicationNamespace;

class BaseMakeCommand extends Command
{
    use DetectsApplicationNamespace;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:base-settings
                    {--views : Only scaffold views}
                    {--force : Overwrite existing views by default}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scaffold basic models';

    /**
     * The models that need to be exported.
     * @var array
     */
    protected $models = [
        'Role.stub' => 'Role.php',
        'Image.stub' => 'Image.php',
        'User.stub' => 'User.php',
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
        'layouts/messages.stub' => 'layouts/messages.blade.php',
        'layouts/user-menu.stub' => 'layouts/user-menu.blade.php',
        'layouts/footer.stub' => 'layouts/footer.blade.php',
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
     *
     * @return mixed
     */
    public function handle()
    {
        $this->createDirectories();

        $this->exportViews();

        if (!$this->option('views')) {
            $this->exportModels();
            $this->exportFilters();
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
     * Replace namespace in filter.
     *
     * @param $model
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
            file_get_contents(__DIR__ . "/stubs/make/models/$model")
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
