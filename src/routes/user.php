<?php

use Illuminate\Support\Facades\Route;

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
    'prefix' => "admin",
    'middleware' => ['web', 'super'],
    'namespace' => 'App\Http\Controllers\Admin',
    "as" => "admin."
], function () {
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