<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use \App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $current = Utilisateur::getLoggedUser($request);

        return Inertia::render("Home",[
            "prenom" => $current["PRENOM"] ?? "",
        ]);
    }

    public function search(string $query, Request $request)
    {
        $products = DB::select("SELECT ID, NOM FROM Produit WHERE NOM LIKE '%$query%'");

        return response()->json($products);
    }
}
