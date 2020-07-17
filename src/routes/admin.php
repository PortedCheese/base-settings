<?php

use Illuminate\Support\Facades\Route;

// Админка.
Route::get('/admin', 'App\Http\Controllers\Admin\DefaultController@dashboard')
    ->middleware(["web", "management"])
    ->name("admin");

if (class_exists("\Rap2hpoutre\LaravelLogViewer\LogViewerController")) {
    Route::get("/admin/logs", "\Rap2hpoutre\LaravelLogViewer\LogViewerController@index")
        ->middleware(["web", "super"])
        ->name("admin.logs");
}
else {
    Route::get("/admin/logs", function () {
        return "For logs install rap2hpoutre/laravel-log-viewer";
    })
        ->middleware(["web", "super"])
        ->name("admin.logs");
}