<?php

namespace PortedCheese\BaseSettings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PortedCheese\BaseSettings\Console\Commands\BaseMakeCommand;
use PortedCheese\BaseSettings\Console\Commands\GenerateLoginLink;
use PortedCheese\BaseSettings\Console\Commands\ImageFiltersClearCommand;
use PortedCheese\BaseSettings\Filters\Badge;
use PortedCheese\BaseSettings\Filters\Large;
use PortedCheese\BaseSettings\Filters\LgGrid3;
use PortedCheese\BaseSettings\Filters\LgGrid4;
use PortedCheese\BaseSettings\Filters\LgGrid6;
use PortedCheese\BaseSettings\Filters\MdGrid4;
use PortedCheese\BaseSettings\Filters\MdGrid6;
use PortedCheese\BaseSettings\Filters\Medium;
use PortedCheese\BaseSettings\Filters\ProfileImage;
use PortedCheese\BaseSettings\Filters\Small;
use PortedCheese\BaseSettings\Filters\SmGrid12;
use PortedCheese\BaseSettings\Filters\SmGrid6;
use PortedCheese\BaseSettings\Filters\WidenLogo;
use PortedCheese\BaseSettings\Filters\XxlGrid3;
use PortedCheese\BaseSettings\Filters\XxlGrid4;
use PortedCheese\BaseSettings\Filters\XxlGrid6;
use PortedCheese\BaseSettings\Filters\XlGrid3;
use PortedCheese\BaseSettings\Filters\XlGrid4;
use PortedCheese\BaseSettings\Filters\XlGrid6;
use PortedCheese\BaseSettings\Helpers\ConfigManager;
use PortedCheese\BaseSettings\Helpers\DateHelper;
use PortedCheese\BaseSettings\Helpers\ReCaptcha;
use PortedCheese\BaseSettings\Helpers\SiteConfig;
use PortedCheese\BaseSettings\Helpers\SmartCaptcha;
use PortedCheese\BaseSettings\Http\Middleware\CheckRole;
use PortedCheese\BaseSettings\Http\Middleware\EditorUser;
use PortedCheese\BaseSettings\Http\Middleware\Management;
use PortedCheese\BaseSettings\Http\Middleware\SuperUser;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;

class BaseSettingsServiceProvider extends ServiceProvider
{

    public function boot()
    {
        setlocale(LC_ALL, 'ru_RU.UTF-8');

        // Config.
        $this->publishes([
            __DIR__ . '/config/gallery.php' => config_path('gallery.php'),
            __DIR__ . '/config/theme.php' => config_path('theme.php'),
            __DIR__ . '/config/image-filter.php' => config_path('image-filter.php'),
        ], 'config');

        $this->setGates();
        $this->hasRoleBlade();
        $this->extendViews();
        $this->bladeComponents();
        $this->bladeIncludes();
        $this->extendImages();
        $this->setMiddleware();
        $this->addRoutes();

        Schema::defaultStringLength(255);

        Paginator::useBootstrap();

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/js/components' => resource_path('js/components/vendor/base-settings'),
            __DIR__ . '/resources/sass' => resource_path('sass/vendor'),
            __DIR__ . '/resources/js/scripts' => resource_path("js/vendor"),
        ], 'public');

        // Подключение миграций.
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Подключение шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'base-settings');

        // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                BaseMakeCommand::class,
            ]);
            $this->commands([
                ImageFiltersClearCommand::class,
            ]);
        }
        $this->commands([
            GenerateLoginLink::class,
        ]);

        $this->app['validator']->extend('hidden_captcha', function ($attribute, $value) {
            return empty($value);
        });
        $this->app['validator']->extend('google_captcha', function ($attribute, $value) {
            return $this->app['geocaptcha']->verifyResponse($value, $this->app['request']->getClientIp());
        });
        $this->app['validator']->extend('smart_captcha', function ($attribute, $value) {
            return $this->app['smartcaptcha']->verifyResponse($value, $this->app['request']->getClientIp(),$this->app["request"]->input("smart-token"));
        });
    }

    public function register()
    {
        $this->app->bind('siteconf', function () {
            return app(SiteConfig::class);
        });
        $this->app->bind('datehelper', function () {
            return app(DateHelper::class);
        });
        $this->app->singleton('geocaptcha', function ($app) {
            return new ReCaptcha();
        });
        $this->app->singleton('smartcaptcha', function ($app) {
            return new SmartCaptcha();
        });
        $this->app->singleton("base-config", function () {
           return new ConfigManager;
        });

        $this->app->singleton("filter-actions", function () {
            $class = config("image-filter.filterFacade");
            return new $class;
        });

        $this->mergeConfigFrom(__DIR__ . "/config/theme.php", "theme");
        $this->mergeConfigFrom(__DIR__ . "/config/image-filter.php", "image-filter");
    }

    private function addRoutes()
    {
        $baseAppRoutePath = "routes/vendor/base-settings";
        $basePkgRoutePath = __DIR__ . "/routes";
        $routeFiles = ["admin", "ajax", "auth", "settings", "user", "roles", "redirect","filter"];
        foreach ($routeFiles as $routeFile) {
            if (! file_exists(base_path("{$baseAppRoutePath}/{$routeFile}.php"))) {
                $this->loadRoutesFrom("{$basePkgRoutePath}/{$routeFile}.php");
            }
        }
    }

    /**
     * Задать доступы.
     */
    private function setGates()
    {
        Gate::before(function ($user, $ability) {
            if ($user->hasRole("admin")) {
                return true;
            }
        });

        Gate::define("site-management", "App\Policies\BasePolicy@siteManagement");
        Gate::define("settings-management", "App\Policies\BasePolicy@settingsManagement");
    }

    /**
     * Задать middleware.
     */
    private function setMiddleware()
    {
        $this->app['router']->aliasMiddleware('role', CheckRole::class);
        $this->app['router']->aliasMiddleware('management', Management::class);
        $this->app['router']->aliasMiddleware('super', SuperUser::class);
        $this->app['router']->aliasMiddleware('editor', EditorUser::class);
    }

    /**
     * Стили для изображений.
     */
    private function extendImages()
    {
        $imagecache = app()->config['imagecache.templates'];

        $imagecache['badge'] = Badge::class;
        $imagecache['small'] = Small::class;
        $imagecache['medium'] = Medium::class;
        $imagecache['large'] = Large::class;
        $imagecache['xxl-grid-6'] = XxlGrid6::class;
        $imagecache['xxl-grid-3'] = XxlGrid3::class;
        $imagecache['xxl-grid-4'] = XxlGrid4::class;
        $imagecache['xl-grid-6'] = XlGrid6::class;
        $imagecache['xl-grid-3'] = XlGrid3::class;
        $imagecache['xl-grid-4'] = XlGrid4::class;
        $imagecache['lg-grid-6'] = LgGrid6::class;
        $imagecache['lg-grid-3'] = LgGrid3::class;
        $imagecache['lg-grid-4'] = LgGrid4::class;
        $imagecache['md-grid-4'] = MdGrid4::class;
        $imagecache['md-grid-6'] = MdGrid6::class;
        $imagecache['sm-grid-6'] = SmGrid6::class;
        $imagecache['sm-grid-12'] = SmGrid12::class;

        $imagecache["profile-image"] = ProfileImage::class;

        $imagecache["widen-logo"] = WidenLogo::class;

 //       app()->config['imagecache.templates'] = $imagecache;
        app()->config['image-filter.templates'] = $imagecache;
    }

    /**
     * Добавить переменные в шаблоны.
     */
    private function extendViews()
    {
        // Ко всем шаблонам цепляем переменную тукущего роута.
        // Ко всем что бы не добавлять шаблон каждый раз как понадобится эта переменная.
        view()->composer('*', function ($view) {
            $detectIe =
                strpos(request()->userAgent(), "Trident")  || strpos(request()->userAgent(), "MSIE" ) ?? 1;
            $view->with('currentRoute', Route::currentRouteName());
            $view->with('detectIe', $detectIe);
        });

        // Выбор темы.
        view()->composer('layouts.boot', function ($view) {
            $currentRouteName = Route::currentRouteName();
            $themes = config('theme.themes');
            $theme = config('theme.default');
            foreach ($themes as $item => $template) {
                if (strstr($currentRouteName, $item)) {
                    $theme = $template;
                    break;
                }
            }
            $view->with('theme', $theme);
        });
    }

    /**
     * Условие на проверку роли в шаблоне.
     */
    private function hasRoleBlade()
    {
        Blade::if('role', function($role) {
            $exploded = explode("|", $role);
            $condition = FALSE;
            if (Auth::check()) {
                foreach ($exploded as $item) {
                    $condition |= Auth::user()->hasRole($item);
                }
            }
            return $condition;
        });
    }

    /**
     * Компоненты приложения.
     */
    private function bladeComponents()
    {
        Blade::aliasComponent("base-settings::components.google-captcha", "gCaptcha");
        Blade::aliasComponent("base-settings::components.hidden-captcha", "hCaptcha");
        Blade::aliasComponent("base-settings::components.smart-captcha", "sCaptcha");

        Blade::aliasComponent("base-settings::components.picture", 'picture');
        Blade::aliasComponent("base-settings::components.image", 'image');
        Blade::aliasComponent("base-settings::components.picture-lazy", 'pictureLazy');
        Blade::aliasComponent("base-settings::components.image-lazy", 'imageLazy');
        Blade::aliasComponent("base-settings::components.gallery", 'gallery');
        Blade::aliasComponent("base-settings::components.gallery-lazy", 'galleryLazy');
    }

    /**
     * Инклуды приложения.
     */
    private function bladeIncludes()
    {
        Blade::include("base-settings::includes.google-captcha-v2", "googleCaptcha2");
        Blade::include("base-settings::includes.hidden-captcha", "hiddenCaptcha");
        Blade::include("base-settings::includes.smart-captcha", "smartCaptcha");

        Blade::include("base-settings::components.picture", "pic");
        Blade::include("base-settings::components.image", "img");
        Blade::include("base-settings::components.picture-lazy", "picLazy");
        Blade::include("base-settings::components.image-lazy", "imgLazy");
        Blade::include("base-settings::components.gallery", "images");
        Blade::include("base-settings::components.gallery-lazy", "imagesLazy");

        Blade::include("base-settings::components.edit-gallery", "eGall");
    }
}
