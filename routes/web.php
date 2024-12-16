<?php

use Illuminate\Support\Facades\Route;

use App\Http\Middleware\DynamicController;

Route::get('/{any}', function () {
    // Ce callback n'est jamais appelé directement grâce au middleware.
})->middleware(DynamicController::class);
