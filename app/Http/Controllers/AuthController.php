<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use \App\Models;

class AuthController extends Controller
{
    static $fiels = [
        "login"=>[
            "fields"=>["Adresse e-mail","Mot de passe"],
            "checkBoxs" => ["Se souvenir de moi"]
        ],
        "register"=>[
            "fields"=>["Prénom","Nom","Adresse e-mail","Mot de passe"],
            "checkBoxs" => ["J'ai lu et j' accepte les condition d'utilisation"]
        ]
    ];

    public function index(string $method = ""){
        if($method == ""){
            $method = "login";
        }
        return Inertia::render("Auth",[
            "authMethod"=>$method,
            "inputs"=>self::$fiels
        ]);
    }

    public function authentificate(string $method,Request $request){
        $data = $request->post();
        try{
            if($method == "register"){

                $current = \App\Models\Utilisateur::register($data["Prénom"],$data["Nom"],$data["Adresse e-mail"],$data["Mot de passe"]);

                session(["EMAIL"=>$current["EMAIL"],"PASSWORD"=>$current["PASSWORD"]]);
                return response("registered successfuly");

            }else if($method == "login"){

                $current = \App\Models\Utilisateur::login($data["Adresse e-mail"],hash("sha256",$data["Mot de passe"]));

                if($data["Se souvenir de moi"]){
                    return response("login successfuly")->cookie(
                        "TOKEN",
                        $current["TOKEN"]
                    );
                }else{
                    session(["EMAIL"=>$current["EMAIL"],"PASSWORD"=>$current["PASSWORD"]]);
                    return response("login successfuly");
                }

            }else{

                throw \App\Models\Exceptions::createError(513);

            }
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
