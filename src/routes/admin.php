<?php

use Illuminate\Support\Facades\Route;

// Админка.
Route::group([
    'prefix' => "admin",
    'middleware' => ['web', 'role:admin'],
    'namespace' => 'App\Http\Controllers\Admin',
    "as" => "admin."
], function () {
    // Настройки сайта.
    Route::resource("settings", "SettingsController")->except(["show"]);
    Route::put("settings/favicon", "SettingsController@updateFavicon")
        ->name("settings.favicon");
    Route::put("settings/{user}/token", "SettingsController@updateToken")
        ->name("settings.token");

    Route::group([
        "prefix" => "users/{user}",
        "as" => "users.auth.",
    ], function () {
        // Ссылка входа под пользователем.
        Route::post("/get-login-link", "UserController@getLoginLink")
            ->name("get-login");
    });
});