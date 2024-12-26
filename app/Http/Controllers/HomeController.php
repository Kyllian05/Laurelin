<?php

namespace App\Http\Controllers;

use \App\Models\Utilisateur;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index(Request $request){

        $current = Utilisateur::getLoggedUser($request);

        return Inertia::render("Home",[
            "test"=>"coucou2",
            "prenom"=>$current["PRENOM"] ?? "",
        ]);
    }
}
