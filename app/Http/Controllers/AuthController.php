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
            try{
                \App\Models\Utilisateur::register($data["First Name"],$data["Last Name"],$data["Adresse e-mail"],$data["Mot de passe"]);
            }catch (\Exception $e){
                $class = explode("\\",get_class($e));
                $class = $class[sizeof($class)-1];
                if($class == "CustomExceptions"){
                    return response($e->getMessage(),$e->getCode());
                }else{
                    \Log::info($e);
                    return response("Erreur inconnu",500);
                }
            }
        }
    }
}
