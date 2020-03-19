<?php

use Illuminate\Support\Facades\Route;

// Админка.
Route::group([
    'prefix' => "admin",
    'middleware' => ['web', 'super'],
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
        Route::post("/send-login-link", "UserController@sendLoginLink")
            ->name("send-login");
    });
    // Роли.
    Route::resource("roles", "RoleController");
    // Права доступа.
    Route::group([
        "prefix" => "roles/{role}/rules/{rule}",
        "as" => "roles.rules."
    ], function () {
        Route::get("/", "RuleController@show")
            ->name("show");
        Route::put("/", "RuleController@update")
            ->name("update");
    });
});

Route::group([
    'namespace' => 'App\Http\Controllers\Admin',
    'middleware' => ['web', 'management'],
    'as' => 'admin.',
    'prefix' => 'admin',
], function () {
    // Роуты пользователя.
    Route::resource('users', 'UserController')->except([
        'show'
    ]);
});

Route::group([
    'middleware' => ["auth:api", "super"],
    'prefix' => "api",
    'namespace' => 'App\Http\Controllers\Admin',
    "as" => "api.admin."
], function () {
    Route::get("/auth/{email}/send-link", "UserController@sendLoginLinkForCurrentUserTo")
        ->name("get-current-link");
});