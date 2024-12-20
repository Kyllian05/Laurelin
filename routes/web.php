<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;

Route::get("/",[HomeController::class,"index"]);

Route::get("/auth/{method?}",[AuthController::class,"index"]);

Route::post("/auth/{method}",[AuthController::class,"authentificate"]);

Route::get("/verifyEmail/{ID}/{CODE}",[AuthController::class,"verifyEmail"]);
