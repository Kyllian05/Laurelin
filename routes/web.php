<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ListeProduitController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MagasinController;

Route::get("/",[HomeController::class,"index"]);

Route::get("/auth/{method?}",[AuthController::class,"index"]);

Route::post("/auth/{method}",[AuthController::class,"authentificate"]);

Route::get("/verifyEmail/{ID}/{CODE}",[AuthController::class,"verifyEmail"]);

Route::get("/account",[AccountController::class,"index"]);

Route::get("/shop", [MagasinController::class, "index"]);

Route::get("/listeproduit", [ListeProduitController::class, "index"]);

Route::get("/recoverPassword/{ID}/{TOKEN}", [AuthController::class, "recoverPassword"]);

Route::post("/recoverPassword/{ID}/{TOKEN}", [AuthController::class, "recoverPassword"]);

Route::post("/sendRecoveryMail", [AuthController::class, "sendRecoveryMail"]);
