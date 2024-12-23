<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    function index(Request $request){
        $user = \App\Models\Utilisateur::getLoggedUser($request);
        if($user == null){
            return redirect("/auth")->cookie("redirect","/account",10,null,null,false,false);
        }

        return Inertia::render("Account",[
            "page"=>"info",
            "info"=>[
                "Prénom"=>$user["PRENOM"],
                "Nom"=>$user["NOM"],
                "Adresse mail"=>$user["EMAIL"],
                "Téléphone"=>$user["TELEPHONE"],
            ],
        ]);
    }
}
