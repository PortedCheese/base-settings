<?php

namespace PortedCheese\BaseSettings;

use App\Menu;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use PortedCheese\BaseSettings\Console\Commands\BaseMakeCommand;
use PortedCheese\BaseSettings\Events\UserUpdate;
use PortedCheese\BaseSettings\Http\Middleware\CheckRole;

class BaseSettingsServiceProvider extends ServiceProvider
{

    public function boot()
    {
        setlocale(LC_ALL, 'ru_RU.UTF-8');

        /**
         * Событие обновление пользователя.
         */
        User::updated(function ($user) {
            event(new UserUpdate($user));
        });

        $this->hasRoleBlade();
        $this->extendViews();

        // Задать middleware.
        $this->app['router']->aliasMiddleware('role', CheckRole::class);

        // Подключение роутов.
        $this->loadRoutesFrom(__DIR__ . '/routes/ajax.php');

        // Assets.
        $this->publishes([
            __DIR__ . '/resources/js/components' => resource_path('js/components/vendor/base-settings'),
        ], 'public');

        // Config.
        $this->publishes([
            __DIR__ . '/config/gallery.php' => config_path('gallery.php'),
            __DIR__ . '/config/theme.php' => config_path('theme.php'),
            __DIR__ . '/config/siteconfigurations.php' => config_path('siteconfigurations.php'),
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

        view()->composer('layouts.admin', function ($view) {
            if (class_exists('\App\Menu')) {
                $view->with('adminMenu', Menu::getByKey('admin'));
            }
            else {
                $view->with('adminMenu', []);
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

}
