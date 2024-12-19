<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use \App\Models;

class AuthController extends Controller
{
    static $fields = [
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
            "inputs"=>self::$fields
        ]);
    }

    public function authentificate(string $method,Request $request){
        $data = $request->post();
        try{
            if($method == "register"){
                $currentFields = self::$fields["register"];
                $current = \App\Models\Utilisateur::register($data[$currentFields["fields"][0]],$data[$currentFields["fields"][1]],$data[$currentFields["fields"][2]],$data[$currentFields["fields"][3]]);

                session(["EMAIL"=>$current["EMAIL"],"PASSWORD"=>$current["PASSWORD"]]);
                return response("registered successfuly");

            }else if($method == "login"){
                $currentFields = self::$fields["login"];
                $current = \App\Models\Utilisateur::login($data[$currentFields["fields"][0]],hash("sha256",$data[$currentFields["fields"][1]]));

                if($data[$currentFields["checkBoxs"][0]]){
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
