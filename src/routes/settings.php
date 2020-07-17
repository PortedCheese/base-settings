<?php

use Illuminate\Support\Facades\Route;

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
});