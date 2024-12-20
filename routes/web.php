<?php

use App\Http\Controllers\PersonnalController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MagasinController;

Route::get("/",[HomeController::class,"index"]);

Route::get("/auth/{method?}",[AuthController::class,"index"]);

Route::post("/auth/{method}",[AuthController::class,"authentificate"]);

Route::get("/verifyEmail/{ID}/{CODE}",[AuthController::class,"verifyEmail"]);

Route::get("/personnal",[PersonnalController::class,"index"]);

Route::get("/shop", [MagasinController::class, "index"]);
