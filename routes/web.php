<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdresseController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HistoireController;
use App\Http\Controllers\ListeProduitController;
use \App\Http\Controllers\FavorisController;
use App\Http\Controllers\ProduitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PanierController;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MagasinController;

// Home
Route::get("/",[HomeController::class,"index"]);

Route::get("/search/{query}", [HomeController::class, "search"]);

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
Route::get("/account/{page?}",[AccountController::class,"index"]);

Route::post("/updateInfo", [AccountController::class, "update"]);

// ---
// Magasin
// ---
Route::get("/bijoux", [MagasinController::class, "list_categories_collections"]);

Route::get("/categories/{name}", [MagasinController::class, "list_categories"]);

Route::get("/collections/{name}", [MagasinController::class, "list_collections"]);

// ---
// Produit
// ---
Route::get("/produit/{id}", [ProduitController::class, "show"]);
Route::get("/produitData/{id}", [ProduitController::class, "produitData"]);
Route::get("/getProduitPicture/{id}", [ProduitController::class, "getProduitPicture"]);

// ---
// Favoris
// ---
Route::post("/ajouterFavoris", [FavorisController::class, "ajoutFavoris"]);
Route::post("/supprimerFavoris", [FavorisController::class, "supprimerFavoris"]);

// ---
// Histoire
// ---
Route::get("/histoire", [HistoireController::class, "index"]);

// ---
// Contact
// ---
Route::get("/contact", [ContactController::class, "index"]);

// ---
// Adresse
// ---
Route::post("/adresse/ajout", [AdresseController::class, "ajout"]);
Route::post("/adresse/supprimer", [AdresseController::class, "supprimer"]);


// ---
// Panier
// ---
Route::get("/panier", [PanierController::class, "index"]);
Route::post("/panier/ajout", [PanierController::class, "ajouterAuPanier"]);
Route::post("/panier/supprimer", [PanierController::class, "supprimerDuPanier"]);
