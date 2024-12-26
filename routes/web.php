<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ListeProduitController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MagasinController;

// Home
Route::get("/",[HomeController::class,"index"]);

// ---
// Auth
// ---
Route::get("/auth/{method?}",[AuthController::class,"index"]);

Route::post("/auth/{method}",[AuthController::class,"authentificate"]);

Route::get("/verifyEmail/{ID}/{CODE}",[AuthController::class,"verifyEmail"]);

Route::get("/recoverPassword/{ID}/{TOKEN}", [AuthController::class, "recoverPassword"]);

Route::post("/recoverPassword/{ID}/{TOKEN}", [AuthController::class, "recoverPassword"]);

Route::post("/sendRecoveryMail", [AuthController::class, "sendRecoveryMail"]);

// ---
// Account
// ---
Route::get("/account",[AccountController::class,"index"]);

Route::post("/updateInfo", [AccountController::class, "update"]);

// ---
// Magasin
// ---
Route::get("/categories", [MagasinController::class, "index"]);

Route::get("/listeproduit", [ListeProduitController::class, "index"]);
