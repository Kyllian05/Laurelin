<?php

namespace App\Http\Controllers;

use http\Cookie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
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
                try{
                    $current = \App\Models\Utilisateur::login($data[$currentFields["fields"][0]],hash("sha256",$data[$currentFields["fields"][1]]));
                }catch(\Exception $e){
                    throw \App\Models\Exceptions::createError(515);
                }

                if($data[$currentFields["checkBoxs"][0]]){
                    return response("login successfuly")->cookie(
                        "TOKEN",
                        $current["TOKEN"],
                        43800,
                        "/",
                        null,
                        true
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

    public function verifyEmail(string $rawID,string $rawCode,Request $request){
        $ID = intval($rawID);
        $CODE = intval($rawCode);

        \App\Models\Code::verifyCode($ID,$CODE);

        return redirect("/");
    }

    public function recoverPassword(string $ID, string $token,Request $request){
        if($request->getMethod() == "GET"){
            return Inertia::render("RecoverPassword",[
                "ID"=>$ID,
                "token"=>$token
            ]);
        }else{
            \App\Models\Utilisateur::changePassword($ID,$token,$request->post()["Nouveau mot de passe"]);
        }
    }

    public function sendRecoveryMail(Request $request){
        $data = $request->post();
        $user = \App\Models\Utilisateur::where("EMAIL",$data["Adresse mail"])->firstOrFail();
        Mail::to($user["EMAIL"])->send(new \App\Mail\PasswordRecovery($user["ID"],$user["TOKEN"]));
    }


    public function logout(Request $request)
    {
        Session::invalidate();
        $response = response()->json(['message' => 'Déconnexion réussie']);
        $response->headers->clearCookie('TOKEN');
        return $response;
    }
}
