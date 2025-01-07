<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdresseController extends Controller{

    function ajout(Request $request){
        $data = $request->post();

        $utilisateur = \App\Models\Utilisateur::getLoggedUser($request);

        $adresse = \App\Models\Adresse::addAdresse($utilisateur,$data["Numéro"],$data["Nom de rue"],$data["Code Postale"]);

        $resultadresse = [];
        $resultadresse["Numéro"] = $adresse["NUM_RUE"];
        $resultadresse["Rue"] = $adresse["NOM_RUE"];
        $resultadresse["Code Postal"] = $adresse["CODE_POSTAL"];
        $resultadresse["Ville"] = \App\Models\Ville::getByCodePostal($adresse["CODE_POSTAL"])["NOM"];
        $resultadresse["ID"] = $adresse["ID"];
        return response($resultadresse);
    }

    function supprimer(Request $request){
        $data = $request->post();

        $utilisateur = \App\Models\Utilisateur::getLoggedUser($request);

        \App\Models\Adresse::where(["ID"=>$data["ID"],"ID_UTILISATEUR" => $utilisateur["ID"]])->delete();
    }
}
