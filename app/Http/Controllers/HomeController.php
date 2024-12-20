<?php

namespace App\Http\Controllers;

use \App\Models\Utilisateur;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Mail\Laurelin;
use Illuminate\Support\Facades\Mail;

class HomeController extends Controller
{
    public function index(Request $request){

        $current = \App\Models\Utilisateur::getLoggedUser($request);

        return Inertia::render("Home",[
            "test"=>"coucou2",
            "prenom"=>$current["PRENOM"] ?? "",
        ]);
    }
}
