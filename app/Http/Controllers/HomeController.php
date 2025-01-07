<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{

    public function index(Request $request)
    {
        return Inertia::render("Home",[
            "prenom" => "",
        ]);
    }

    public function search(string $query, Request $request)
    {
        $products = DB::select("SELECT ID, NOM FROM Produit WHERE NOM LIKE '%$query%'");

        return response()->json($products);
    }
}
