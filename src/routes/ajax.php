<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => "admin",
    'middleware' => ['web', 'role:admin|editor'],
    'namespace' => 'PortedCheese\BaseSettings\Http\Controllers'
], function () {
    // Роуты для аякса.
    Route::prefix('vue')->group(function () {
        // Роуты галлереи.
        Route::group([
            'prefix' => "gallery",
            'as' => "admin.vue.gallery.",
            'namespace' => "Site",
        ], function () {
            // Получить изображения.
            Route::get('/{model}/{id}', 'ImageController@get')
                ->name('get');
            // Загрузка изображения.
            Route::post('/{model}/{id}/create', 'ImageController@post')
                ->name('post');
            // Удаление изображения.
            Route::delete('/{model}/{id}/{image}/delete', 'ImageController@delete')
                ->name('delete');
            // Сменить вес изображения.
            Route::post('/{model}/{id}/{image}/weight', 'ImageController@weight')
                ->name('weight');
            // Сменить имя изображения.
            Route::post('/{model}/{id}/{image}/name', 'ImageController@name')
                ->name('name');
        });
    });
});