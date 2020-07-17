<?php

use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => "admin/vue",
    'middleware' => ['web', 'management'],
    'namespace' => 'PortedCheese\BaseSettings\Http\Controllers'
], function () {
    // Роуты галлереи.
    Route::group([
        'prefix' => "gallery",
        'as' => "admin.vue.gallery.",
    ], function () {
        // Получить изображения.
        Route::get('/{model}/{id}', 'ImageController@get')
            ->name('get');
        // Загрузка изображения.
        Route::post('/{model}/{id}', 'ImageController@post')
            ->name('post');
        // Изменить порядок изображений.
        Route::put("/{model}/{id}", "ImageController@updateOrder")
            ->name("order");
        // Удаление изображения.
        Route::delete('/{model}/{id}/{image}/delete', 'ImageController@delete')
            ->name('delete');
        // Сменить имя изображения.
        Route::post('/{model}/{id}/{image}/name', 'ImageController@name')
            ->name('name');
    });

    // Роуты для приоритета.
    Route::put("priority/{table}/{field}", "Admin\SettingsController@changePriority")
        ->name("admin.vue.priority");
});