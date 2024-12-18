<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use \App\Models;

class AuthController extends Controller
{
    public function index(string $method = ""){
        if($method == ""){
            $method = "login";
        }
        return Inertia::render("Auth",[
            "authMethod"=>$method
        ]);
    }

    public function authentificate(string $method,Request $request){
        $data = $request->post();

        if($method == "register"){
            \App\Models\Utilisateur::register($data["First Name"],$data["Last Name"],$data["Adresse e-mail"],$data["Mot de passe"]);
        }
    }
}
