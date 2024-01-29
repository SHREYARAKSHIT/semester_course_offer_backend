<?php
//by @bhijeet

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

Route::controller(AdminController::class)->group(function () {
    Route::post('addModule', 'addModule');
    Route::get('get/modules', 'getModules');
    Route::post('add/menu', 'addMenu');
    Route::post('test', 'test');
});
