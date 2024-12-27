<?php

namespace App\Http\Controllers;

use \App\Models\Utilisateur;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProduitController extends Controller
{
    public function index(Request $request){

        return Inertia::render("Produit",[]);
    }
}
