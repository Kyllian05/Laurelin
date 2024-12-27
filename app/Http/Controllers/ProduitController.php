<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use \App\Models\Utilisateur;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProduitController extends Controller
{
    public function show(string $id, Request $request){

        return Inertia::render("Produit",[
            "produit" => Produit::find($id)
        ]);
    }
}
