<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => "admin",
    'middleware' => ['web', 'super'],
    'namespace' => 'App\Http\Controllers\Admin',
    "as" => "admin."
], function () {
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