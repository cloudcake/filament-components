<?php

use Cloudcake\Components\IconPicker\Http\Controllers\IconController;
use Cloudcake\Components\IconPicker\Http\Controllers\IconSvgController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web'])->group(function () {
    Route::get('/cloudcake-icons/icons', IconController::class)
        ->name('cloudcake.icons');

    Route::get('/cloudcake-icons/icon/{icon}', IconSvgController::class)
        ->name('cloudcake.icon')
        ->where('icon', '.*');
});
