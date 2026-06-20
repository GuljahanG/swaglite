<?php

use Illuminate\Support\Facades\Route;
use GuljahanG\SwagLite\Http\Controllers\SwagLiteController;

Route::prefix('swaglite')
    ->group(function () {

        Route::get('/', [
            SwagLiteController::class,
            'index'
        ]);
    });