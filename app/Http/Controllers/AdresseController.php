<?php
namespace App\Http\Controllers;

use App\Domain\Adresse\Services\AdresseService;
use App\Domain\Adresse\Services\VilleService;
use App\Domain\Utilisateur\Services\UtilisateurService;
use Illuminate\Http\Request;

class AdresseController extends Controller{

    public function __construct(
        private UtilisateurService $utilisateurService,
        private AdresseService $adresseService,
        private VilleService $villeService
    ) {}

    function ajout(Request $request){
        $data = $request->post();

        $utilisateur = $this->utilisateurService->getAuthenticatedUser($request);
        $ville = $this->villeService->findById(intval($data['Ville']['ID']));
        $adresse = $this->adresseService->add(intval($data["Numéro"]), $data["Nom de rue"], $ville, $utilisateur);

        return response($adresse->serialize());
    }

    function supprimer(Request $request){
        $data = $request->post();
        $this->utilisateurService->getAuthenticatedUser($request); // Sécurité
        $this->adresseService->delete($this->adresseService->findById($data["ID"]));
    }

    function getVilles(string $codePostal, Request $request) {
        $villes = $this->villeService->findByCodePostal($codePostal);
        $villesSerialized = [];
        foreach ($villes as $ville) {
            $villesSerialized[] = $ville->serialize();
        }
        return response($villesSerialized)->header('Content-Type', 'application/json');
    }
}
