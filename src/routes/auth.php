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