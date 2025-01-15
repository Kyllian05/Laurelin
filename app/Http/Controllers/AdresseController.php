<?php
namespace App\Http\Controllers;

use App\Domain\Adresse\Services\AdresseService;
use App\Domain\Adresse\Services\VilleService;
use App\Domain\Utilisateur\Services\UtilisateurService;
use App\Domain\Shared\Exceptions;
use Illuminate\Http\Request;

class AdresseController extends Controller{

    public function __construct(
        private UtilisateurService $utilisateurService,
        private AdresseService $adresseService,
        private VilleService $villeService
    ) {}

    function ajout(Request $request){
        $data = $request->post();

        try{
            $utilisateur = $this->utilisateurService->getAuthenticatedUser($request);
            if($utilisateur == null){
                throw Exceptions::createError(525);
            }
        }catch(\Exception $e){
            $e = Exceptions::createError(525);
            return response()->json($e->getMessage(),$e->httpCode);
        }
        $ville = $this->villeService->findById(intval($data['Ville']['ID']));
        $adresse = $this->adresseService->add(intval($data["Numéro"]), $data["Nom de rue"], $ville, $utilisateur);

        return response($adresse->serialize());
    }

    function supprimer(Request $request){
        $data = $request->post();
        $user = $this->utilisateurService->getAuthenticatedUser($request); // Sécurité

        if($user == null){
            $e = Exceptions::createError(525);
            return response()->json($e->getMessage(),$e->httpCode);
        }
        try {
            $this->adresseService->delete($this->adresseService->findById($data["ID"]));
        } catch(\Exception $e){
            if($e->getCode() == 23000){
                $e = Exceptions::createError(525);
            }
            return response($e->getMessage(),$e->getCode());
        }
    }

    function getVilles(string $codePostal, Request $request) {
        $villes = $this->villeService->findByCodePostal($codePostal);
        $villesSerialized = [];
        foreach ($villes as $ville) {
            $villesSerialized[] = $ville->serialize();
        }
        return response($villesSerialized)->header('Content-Type', 'application/json');
    }

    function getMagasins(string $codepostal, Request $request)
    {
        $magasins = $this->adresseService->findMagasinByCodePostal($codepostal);
        $magasinsSerialized = [];
        foreach ($magasins as $magasin) {
            $magasinsSerialized[] = $magasin->serialize();
        }
        return response($magasinsSerialized, 200)->header('Content-Type', 'application/json');
    }
}
