<?php

namespace App\Http\Controllers;

use App\Domain\Shared\Exceptions as DomainExceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use App\Domain\Utilisateur\Services\UtilisateurService;

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

    public function __construct(private UtilisateurService $userService) {}

    public function index(string $method = ""){
        if($method == ""){
            $method = "login";
        }
        return Inertia::render("Auth",[
            "authMethod"=>$method,
            "inputs"=>self::$fields
        ]);
    }

    public function authentificate(string $method, Request $request) {
        $data = $request->post();
        try{
            if($method == "register") {
                $currentFields = self::$fields["register"];

                if (!$data[$currentFields["checkBoxs"][0]]) {
                    throw DomainExceptions::createErrorWithMessage(521, "Veuillez accepter les conditions d'utilisation");
                }

                $this->userService->register($data[$currentFields["fields"][0]],$data[$currentFields["fields"][1]],$data[$currentFields["fields"][2]],$data[$currentFields["fields"][3]]);

                return response("registered successfully");

            } else if($method == "login") {
                $currentFields = self::$fields["login"];

                $user = $this->userService->login($data[$currentFields["fields"][0]], $data[$currentFields["fields"][1]]);

                if($data[$currentFields["checkBoxs"][0]]){
                    return response("login successfuly")->cookie(
                        "TOKEN",
                        $user->getToken(),
                        43800,
                        "/",
                        null,
                        true
                    );
                }
                return redirect("/");
            } else {
                throw \App\Domain\Shared\Exceptions::createError(513);
            }
        }catch (\Exception $e){
            $class = explode("\\",get_class($e));
            $class = $class[sizeof($class)-1];
            if($class == "CustomExceptions"){
                return response($e->getMessage(),$e->getCode());
            }else{
                \Log::info($e);
                return response("Erreur inconnue",500);
            }
        }
    }

    public function verifyEmail(string $rawID, string $rawCode, Request $request){
        try {
            $id = intval($rawID);
            $code = intval($rawCode);
            $this->userService->verifyCode($id,$code);
        } catch (\Exception $e){
            $class = explode("\\",get_class($e));
            $class = $class[sizeof($class)-1];
            if($class == "CustomExceptions"){
                return response($e->getMessage(),$e->getCode());
            }else{
                \Log::info($e);
                return response("Erreur inconnue",500);
            }
        }

        return redirect("/account");
    }

    public function recoverPassword(string $ID, string $token,Request $request){
        if($request->getMethod() == "GET"){
            return Inertia::render("RecoverPassword",[
                "ID"=>$ID,
                "token"=>$token
            ]);
        }else{
            try {
                $this->userService->changePassword($ID,$token,$request->post()["Nouveau mot de passe"]);
            } catch (\Exception $e){
                $class = explode("\\",get_class($e));
                $class = $class[sizeof($class)-1];
                if($class == "CustomExceptions"){
                    return response($e->getMessage(),$e->getCode());
                }else{
                    \Log::info($e);
                    return response("Erreur inconnue",500);
                }
            }
        }
    }

    public function sendRecoveryMail(Request $request){
        $data = $request->post();
        $user = $this->userService->getRepository()->findByEmail($data["Adresse mail"]);
        Mail::to($user->getEmail())->send(new \App\Mail\PasswordRecovery($user->getId(), $user->getToken()));
    }
}
