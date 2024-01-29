<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'namespace' => 'App\Http\Controllers\Site',
    'middleware' => ['web'],
], function () {
    Route::get("/". config("image-filter.url","filter-img")."/{template}/{filename}", "FilterController@show")
        ->name("image-filter");
});




