<?php

use Illuminate\Support\Facades\Route;

// Админка.
Route::group([
    'prefix' => "admin",
    'middleware' => ['web', 'role:admin'],
    'namespace' => 'App\Http\Controllers\Admin',
    "as" => "admin."
], function () {
    Route::resource("settings", "SettingsController")->except(["show"]);
    Route::put("settings/favicon", "SettingsController@updateFavicon")
        ->name("settings.favicon");
});