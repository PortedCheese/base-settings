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
        Route::prefix('gallery')->group(function () {
            // Получить изображения.
            Route::get('/{model}/{id}', 'ImageController@get')
                ->name('admin.vue.gallery.get');
            // Загрузка изображения.
            Route::post('/{model}/{id}/create', 'ImageController@post')
                ->name('admin.vue.gallery.post');
            // Удаление изображения.
            Route::delete('/{model}/{id}/{image}/delete', 'ImageController@delete')
                ->name('admin.vue.gallery.delete');
            // Сменить вес изображения.
            Route::post('/{model}/{id}/{image}/weight', 'ImageController@weight')
                ->name('admin.vue.gallery.weight');
        });
    });
});