<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'App\Http\Controllers\Site',
    'middleware' => ['web'],
    'as' => 'redirect.',
    'prefix' => 'redirect',
], function () {
    // Роуты пользователя.
    Route::get("/{url}", "RedirectController@to")
        ->name("to");
});

