<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AccountController extends Controller
{
    function index(Request $request){
        try{
            $user = \App\Models\Utilisateur::getLoggedUser($request);
            if($user == null){
                return redirect("/auth")->cookie("redirect","/account",10,null,null,false,false);
            }

            return Inertia::render("Account",[
                "page"=>"info",
                "info"=>[
                    "Prénom"=>$user["PRENOM"],
                    "Nom"=>$user["NOM"],
                    "Téléphone"=>$user["TELEPHONE"],
                    "Adresse mail"=>$user["EMAIL"],
                ],
            ]);
        }catch (\Exception $e){
            if($e->getCode() == 518){
                return redirect("/auth")->cookie("redirect","/account",10,null,null,false,false)->withCookie(\Illuminate\Support\Facades\Cookie::forget("TOKEN"));
            }
        }
    }

    function update(Request $request){
        $data = $request->post();
        $user = \App\Models\Utilisateur::getLoggedUser($request);
        if(isset($data["Nom"]) && isset($data["Prénom"]) && isset($data["Téléphone"])){
            \App\Models\Utilisateur::where("ID",$user["ID"])->update(["NOM"=>$data["Nom"],"PRENOM"=>$data["Prénom"],"TELEPHONE"=>$data["Téléphone"]]);
        }
    }
}
