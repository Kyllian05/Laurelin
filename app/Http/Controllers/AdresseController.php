<?php
namespace App\Http\Controllers;

use App\Domain\Utilisateur\Services\UtilisateurService;
use Illuminate\Http\Request;

class AdresseController extends Controller{

    public function __construct(private UtilisateurService $utilisateurService) {}

    function ajout(Request $request){
        $data = $request->post();

        $utilisateur = $this->utilisateurService->getAuthenticatedUser($request);

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

        $utilisateur = $this->utilisateurService->getAuthenticatedUser($request);

        \App\Models\Adresse::where(["ID"=>$data["ID"],"ID_UTILISATEUR" => $utilisateur->getId()])->delete();
    }

    function getVilles(string $codepostale,Request $request){
        return response(\App\Models\Ville::getByCodePostal($codepostale))->header('Content-Type', 'application/json');
    }
}
