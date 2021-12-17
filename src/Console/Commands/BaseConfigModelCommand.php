<?php

namespace PortedCheese\BaseSettings\Console\Commands;

use App\Role;
use App\RoleRule;
use Illuminate\Console\Command;
use Illuminate\Container\Container;

class BaseConfigModelCommand extends Command
{
    /**
     * Namespace.
     *
     * @var mixed|string
     */
    protected $namespace = '';

    /**
     * Разработчик пакета
     *
     * @var string
     */
    protected $vendorName = '';
    /**
     * Имя пакета.
     *
     * @var string
     */
    protected $packageName = "";

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
     * Список наблюдателей
     *
     * @var array
     */
    protected $observers = [];

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
     * Js файлы.
     *
     * @var array
     */
    protected $jsIncludes = [];

    /**
     * Права доступа.
     *
     * @var array
     */
    protected $ruleRules = [];

    /**
     * Scss файлы.
     *
     * @var array
     */
    protected $scssIncludes = [];

    /**
     * Имя конфига.
     *
     * @var string
     */
    protected $configName = '';

    /**
     * Заголовок конфига.
     *
     * @var string
     */
    protected $configTitle = "";

    /**
     * Шаблон конфига.
     *
     * @var string
     */
    protected $configTemplate = "";

    /**
     * Значения конфига.
     *
     * @var array
     */
    protected $configValues = [];

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
     * Замена устаревшего метода.
     *
     * @return mixed
     */
    protected function getAppNamespace() {
        return Container::getInstance()->getNamespace();
    }

    /**
     * Добавить настройки по умолчанию.
     */
    protected function makeConfig()
    {
        $data = [
            'title' => $this->configTitle,
            'template' => $this->configTemplate,
            'pkg' => true,
        ];
        $result = base_config()->create($this->configName, $this->configValues, $data);
        if ($result == "exists") {
            if (! $this->confirm("{$this->configTitle} config already exists. Replace it?")) {
                return;
            }
        }

        if ($result !== "created") {
            $result = base_config()->create($this->configName, $this->configValues, $data, true);
        }

        $this->info("Config {$this->configTitle} $result");
    }

    /**
     * Создать файлы для компонентов vue.
     *
     * @param $key
     */
    protected function makeVueIncludes($key)
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
     * Включить js файлы.
     *
     * @param $key
     */
    protected function makeJsIncludes($key)
    {
        $filePath = resource_path("js/vendor/$key-js-includes.js");
        // Создать файл для компонентов сайта, если его нет.
        if (! file_exists($filePath)) {
            file_put_contents(
                $filePath,
                ""
            );
            $this->info("Added file $filePath");
        }
        if (! empty($this->jsIncludes[$key])) {
            foreach ($this->jsIncludes[$key] as $jsInclude) {
                if (! $this->confirm("Add $jsInclude.js file to $filePath?")) {
                    continue;
                }
                file_put_contents(
                    $filePath,
                    "require('./$jsInclude.js');\n",
                    FILE_APPEND
                );
                $this->info("$jsInclude added to $filePath");
            }
        }
    }

    /**
     * Включить scss файлы.
     *
     * @param $key
     */
    protected function makeScssIncludes($key) {
        $filePath = resource_path("sass/vendor/_$key-includes.scss");
        if (! file_exists($filePath)) {
            file_put_contents(
                $filePath,
                ""
            );
            $this->info(" Added file $filePath");
        }
        if (! empty($this->scssIncludes[$key])) {
            foreach ($this->scssIncludes[$key] as $scssInclude) {
                if (! $this->confirm("Add $scssInclude.scss file to $filePath?")) {
                    continue;
                }
                file_put_contents(
                    $filePath,
                    "@import '$scssInclude';\n",
                    FILE_APPEND
                );
                $this->info("$scssInclude added to $filePath");
            }
        }
    }

    /**
     * Create observers files.
     */
    protected function exportObservers()
    {
        $folders = "Observers/Vendor/{$this->packageName}";
        foreach ($this->observers as $observer) {
            $path = app_path("$folders/$observer.php");
            if (file_exists($path)) {
                if (! $this->confirm("The [{$observer}.php] observer already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            if (! is_dir($directory = app_path($folders))) {
                mkdir($directory, 0755, true);
            }

            try {
                file_put_contents(
                    $path,
                    $this->compileObserverStub($observer)
                );

                $this->info("Observer [{$observer}] generated successfully.");
            }
            catch (\Exception $exception) {
                $this->error("Filed put observer");
            }
        }
    }

    /**
     * Replace namespace in observer.
     *
     * @param $observer
     * @return mixed
     */
    protected function compileObserverStub($observer)
    {
        return str_replace(
            ["{{vndName}}" , "{{namespace}}", "{{observer}}", "{{pkgName}}"],
            [$this->vendorName, $this->getAppNamespace(), $observer, $this->packageName],
            file_get_contents(__DIR__ . "/stubs/make/observers/StubObserver.stub")
        );
    }

    /**
     * Create models files.
     */
    protected function exportModels()
    {
        foreach ($this->models as $model) {
            if (file_exists(app_path("$model.php"))) {
                if (!$this->confirm("The [{$model}.php] model already exists. Do you want to replace it?")) {
                    continue;
                }
            }

            try {
                file_put_contents(
                    app_path("$model.php"),
                    $this->compileModelStub($model)
                );

                $this->info("Model [{$model}] generated successfully.");
            }
            catch (\Exception $e) {
                $this->error("Failed put model");
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
            ['{{vndName}}','{{namespace}}', "{{model}}", "{{pkgName}}"],
            [$this->vendorName, $this->namespace, $model, $this->packageName],
            file_get_contents(__DIR__ . "/stubs/make/models/StubModel.stub")
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
     * @param $place
     * @param $controller
     * @return mixed
     */
    protected function compileControllerStub($place, $controller)
    {
        return str_replace(
            ['{{vndName}}','{{namespace}}', '{{pkgName}}', "{{place}}", "{{name}}"],
            [$this->vendorName, $this->getAppNamespace(), $this->packageName, $place, $controller],
            file_get_contents(__DIR__ . "/stubs/make/controllers/StubController.stub")
        );
    }

    /**
     * Создать политики.
     */
    protected function makeRules()
    {
        $editorRole = Role::query()
            ->where("name", "editor")
            ->first();
        foreach ($this->ruleRules as $rule) {
            if (empty($rule['title']) || empty($rule['slug']) || empty($rule['policy'])) {
                $this->error("Не хватает параметров");
                continue;
            }
            $policy = $rule['policy'];

            $model = RoleRule::query()
                ->where("slug", $rule['slug'])
                ->first();

            if (! $model) {
                try {
                    $model = RoleRule::create([
                        "title" => $rule['title'],
                        "slug" => $rule["slug"],
                        "policy" => $policy,
                    ]);
                    $this->info("Model RoleRule  generated ({$model->title} {$model->id})");
                }
                catch (\Exception $exception) {
                    $this->error("Failed to create a RoleRule model");
                    continue;
                }
            }

            $class = "App\Policies\\" . $policy;
            $options = $this->options();
            $default = !empty($options["only-default"]);

            if (file_exists(app_path("Policies/$policy.php")) && ! $default) {
                if (! $this->confirm("The [{$policy}.php] policy already exists. Do you want to replace it?")) {
                    $this->setDefaultPermissions($class, $editorRole, $model);
                    continue;
                }
            }

            if (! $default) {
                try {
                    if (! is_dir($directory = app_path('Policies'))) {
                        mkdir($directory, 0755, true);
                    }
                    file_put_contents(
                        app_path("Policies/$policy.php"),
                        $this->compilePolicyStub($policy)
                    );

                    $this->info("Policy [{$policy}] generated successfully.");
                }
                catch (\Exception $e) {
                    $this->error("Failed put policy");
                }
            }

            $this->setDefaultPermissions($class, $editorRole, $model);
        }
    }

    /**
     * Задать права по умолчанию.
     *
     * @param $class
     * @param $role
     * @param $rule
     */
    protected function setDefaultPermissions($class, $role, $rule)
    {
        try {
            if (
                $role &&
                $rule &&
                method_exists($class, "defaultRules") &&
                $this->confirm("Set default permissions for editor by $class?") &&
                is_numeric($class::defaultRules())
            ) {
                $exist = false;
                $rights = $class::defaultRules();
                foreach ($role->rules as $item) {
                    if ($item->slug == $rule->slug) {
                        $exist = true;
                        break;
                    }
                }
                if (! $exist) {
                    $role->rules()->save($rule, [
                        'rights' => $rights,
                    ]);
                    $this->info("Rules created");
                }
                else {
                    $role->rules()->updateExistingPivot($rule->id, [
                        'rights' => $rights,
                    ]);
                    $this->info("Rules updated");
                }
            }
        }
        catch (\Exception $exception) {
            $this->error("Fail while creating default rules");
        }
    }

    /**
     * Replace namespace in policy.
     *
     * @param $policy
     * @return mixed
     */
    protected function compilePolicyStub($policy)
    {
        return str_replace(
            ['{{vndName}}','{{namespace}}', "{{policy}}", "{{pkgName}}"],
            [$this->vendorName, $this->namespace, $policy, $this->packageName],
            file_get_contents(__DIR__ . "/stubs/make/policies/StubPolicy.stub")
        );
    }
}
