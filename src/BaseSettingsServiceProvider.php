<?php

namespace PortedCheese\BaseSettings;

use App\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PortedCheese\BaseSettings\Console\Commands\BaseMakeCommand;

class BaseSettingsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->hasRoleBlade();

        $this->extendViews();

        // Подключение роутов.
        $this->loadRoutesFrom(__DIR__ . '/routes/ajax.php');

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/js/components' => resource_path('js/components/vendor/base-settings'),
        ], 'public');

        // Config.
        $this->publishes([
            __DIR__ . '/config/gallery.php' => config_path('gallery.php'),
        ], 'config');

        // Подключение миграций.
        $this->loadMigrationsFrom(__DIR__ . '/database/migrations');

        // Console.
        if ($this->app->runningInConsole()) {
            $this->commands([
                BaseMakeCommand::class,
            ]);
        }
    }

    public function register()
    {

    }

    /**
     * Добавить переменные в шаблоны.
     */
    private function extendViews()
    {
        // Добавляем главное меню сайта в основной шаблон сайта.
        view()->composer('layouts.app', function ($view) {
            if (class_exists('\App\Menu')) {
                $view->with('mainMenu', Menu::getByKey('main'));
            }
            else {
                $view->with('mainMenu', []);
            }
        });
        // Ко всем шаблонам цепляем переменную тукущего роута.
        // Ко всем что бы не добавлять шаблон каждый раз как понадобится эта переменная.
        view()->composer('*', function ($view) {
            $view->with('currentRoute', Route::currentRouteName());
        });
        // Выбор темы.
        view()->composer('layouts.boot', function ($view) {
            $currentRouteName = Route::currentRouteName();
            $theme = 'site';
            if (strstr($currentRouteName, 'admin')) {
                $theme = 'admin';
            }
            if (strstr($currentRouteName, 'webflow')) {
                $theme = 'webflow';
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

}
