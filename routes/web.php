<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DynamicController;

Route::get('/{any?}', function () {
    // Ce callback est ignoré grâce au middleware.
})->where('any', '.*')->middleware(DynamicController::class);