<?php

namespace PortedCheese\BaseSettings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PortedCheese\BaseSettings\Console\Commands\BaseMakeCommand;
use PortedCheese\BaseSettings\Console\Commands\GenerateLoginLink;
use PortedCheese\BaseSettings\Filters\LgGrid3;
use PortedCheese\BaseSettings\Filters\LgGrid4;
use PortedCheese\BaseSettings\Filters\LgGrid6;
use PortedCheese\BaseSettings\Filters\MdGrid4;
use PortedCheese\BaseSettings\Filters\MdGrid6;
use PortedCheese\BaseSettings\Filters\SmGrid12;
use PortedCheese\BaseSettings\Filters\SmGrid6;
use PortedCheese\BaseSettings\Helpers\DateHelper;
use PortedCheese\BaseSettings\Helpers\ReCaptcha;
use PortedCheese\BaseSettings\Helpers\SiteConfig;
use PortedCheese\BaseSettings\Http\Middleware\CheckRole;

class BaseSettingsServiceProvider extends ServiceProvider
{

    public function boot()
    {
        setlocale(LC_ALL, 'ru_RU.UTF-8');

        $this->hasRoleBlade();
        $this->extendViews();
        $this->bladeComponents();
        $this->bladeIncludes();
        $this->extendImages();

        // Задать middleware.
        $this->app['router']->aliasMiddleware('role', CheckRole::class);

        // Подключение роутов.
        $this->loadRoutesFrom(__DIR__ . '/routes/ajax.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/admin.php');
        $this->loadRoutesFrom(__DIR__ . '/routes/auth.php');

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/js/components' => resource_path('js/components/vendor/base-settings'),
            __DIR__ . '/resources/sass' => resource_path('sass/vendor'),
            __DIR__ . '/resources/js/scripts' => resource_path("js/vendor"),
        ], 'public');

        // Config.
        $this->publishes([
            // TODO: add config for package variables.
            __DIR__ . '/config/gallery.php' => config_path('gallery.php'),
            __DIR__ . '/config/theme.php' => config_path('theme.php'),
        ], 'config');

        // Подключение миграций.
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Подключение шаблонов.
        $this->loadViewsFrom(__DIR__ . '/resources/views', 'base-settings');

        // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                BaseMakeCommand::class,
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
    }

    /**
     * Стили для изображений.
     */
    private function extendImages()
    {
        $imagecache = app()->config['imagecache.templates'];

        $imagecache['lg-grid-6'] = LgGrid6::class;
        $imagecache['lg-grid-3'] = LgGrid3::class;
        $imagecache['lg-grid-4'] = LgGrid4::class;
        $imagecache['md-grid-4'] = MdGrid4::class;
        $imagecache['md-grid-6'] = MdGrid6::class;
        $imagecache['sm-grid-6'] = SmGrid6::class;
        $imagecache['sm-grid-12'] = SmGrid12::class;

        app()->config['imagecache.templates'] = $imagecache;
    }

    /**
     * Добавить переменные в шаблоны.
     */
    private function extendViews()
    {
        // Ко всем шаблонам цепляем переменную тукущего роута.
        // Ко всем что бы не добавлять шаблон каждый раз как понадобится эта переменная.
        view()->composer('*', function ($view) {
            $view->with('currentRoute', Route::currentRouteName());
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
        Blade::component("base-settings::components.google-captcha", "gCaptcha");
        Blade::component("base-settings::components.hidden-captcha", "hCaptcha");

        Blade::component("base-settings::components.picture", 'picture');
        Blade::component("base-settings::components.image", 'image');
        Blade::component("base-settings::components.gallery", 'gallery');
    }

    /**
     * Инклуды приложения.
     */
    private function bladeIncludes()
    {
        Blade::include("base-settings::includes.google-captcha-v2", "googleCaptcha2");
        Blade::include("base-settings::includes.hidden-captcha", "hiddenCaptcha");

        Blade::include("base-settings::components.picture", "pic");
        Blade::include("base-settings::components.image", "img");
        Blade::include("base-settings::components.gallery", "images");
    }
}
