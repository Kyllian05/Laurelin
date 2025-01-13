<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdresseController extends Controller{

    function ajout(Request $request){
        $data = $request->post();

        $utilisateur = \App\Models\Utilisateur::getLoggedUser($request);

        \Log::info($data);

        $adresse = \App\Models\Adresse::addAdresse($utilisateur,$data["Numéro"],$data["Nom de rue"],$data["Ville"]["Nom"],$data["Ville"]["Code Postal"]);

        $resultadresse = [];
        $resultadresse["Numéro"] = $adresse["NUM_RUE"];
        $resultadresse["Rue"] = $adresse["NOM_RUE"];
        $resultadresse["Code Postal"] = $data["Ville"]["Code Postal"];
        $resultadresse["Ville"] = $data["Ville"]["Nom"];
        $resultadresse["ID"] = $adresse["ID"];
        return response($resultadresse);
    }

    function supprimer(Request $request){
        $data = $request->post();

        try{
            $utilisateur = \App\Models\Utilisateur::getLoggedUser($request);

            \App\Models\Adresse::where(["ID"=>$data["ID"],"ID_UTILISATEUR" => $utilisateur["ID"]])->delete();
        }catch(\Exception $e){
            if($e->getCode() == 23000){
                $e = \App\Models\Exceptions::createError(523);
            }
            return response($e->getMessage(),$e->getCode());
        }
    }

    function getVilles(string $codepostale,Request $request){

        return response(\App\Models\Ville::getByCodePostal($codepostale))->header('Content-Type', 'application/json');
    }
}
