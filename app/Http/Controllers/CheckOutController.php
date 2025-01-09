<?php

namespace App\Http\Controllers;

use App\Models\Exceptions;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CheckOutController extends Controller
{
    public function index(Request $request){
        try{
            $user = \App\Models\Utilisateur::getLoggedUser($request);
            if($user == null){
                throw Exceptions::createError(518);
            }
        }catch(\Exception $e){
            if($e->getCode() == 518){
                return redirect("/auth")->cookie("redirect","/checkout",10,null,null,false,false)->withCookie(\Illuminate\Support\Facades\Cookie::forget("TOKEN"));
            }else{
                throw $e;
            }
        }

        $userData = [
            "EMAIL" => $user["EMAIL"],
            "NOM" => $user["NOM"],
            "PRENOM" => $user["PRENOM"],
        ];

        $adresses = \App\Models\Adresse::getAllUserAdresse($user)->toArray();

        for($i = 0; $i < count($adresses); $i++){
            $ville = \App\Models\Ville::where("ID",$adresses[$i]["ID_VILLE"])->firstOrFail();
            $adresses[$i]["VILLE"] = $ville["NOM"];
            $adresses[$i]["CODE_POSTAL"] = $ville["CODE_POSTAL"];
        }

        return Inertia::render("CheckOut",[
            "user" => $userData,
            "adresses" => $adresses,
        ]);
    }
}
