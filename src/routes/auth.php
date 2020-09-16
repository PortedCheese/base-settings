<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => ['web'],
    'namespace' => 'App\Http\Controllers\Site',
    "as" => "profile."
], function () {
    Route::get("auth/email-authenticate/{token}", "ProfileController@authenticateEmail")
        ->name("auth.email-authenticate");
});

// Пользователь.
Route::group([
    'prefix' => 'profile',
    'namespace' => "App\Http\Controllers\Site",
    'middleware' => ["web", "auth", "verified"],
    'as' => 'profile.'
], function () {
    Route::get('/', 'ProfileController@show')
        ->name('show');
    Route::get('/edit', 'ProfileController@edit')
        ->name("edit");
    Route::put('/update', 'ProfileController@update')
        ->name("update");
});