<?php

namespace App\Http\Controllers;

use App\Domain\Adresse\Services\AdresseService;
use App\Domain\Commande\Service\CartService;
use App\Domain\Commande\Service\OrderService;
use App\Domain\Shared\CustomExceptions;
use App\Domain\Utilisateur\Services\UtilisateurService;
use App\Domain\Shared\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class CheckOutController extends Controller
{
    private ?CartService $cartService = null;

    public function __construct(
        private UtilisateurService $utilisateurService,
        private OrderService $orderService,
        private AdresseService $adresseService,
    ) {}

    public function index(Request $request){
        // --- Utilisateur ---
        try {
            $user = $this->utilisateurService->getAuthenticatedUser($request);
            if($user == null){
                throw Exceptions::createError(518);
            }
        } catch(CustomExceptions $e) {
            if($e->httpCode == 401 || $e->httpCode == 403){
                return redirect("/auth")->cookie("redirect","/checkout",10,null,null,false,false)->withCookie(Cookie::forget("TOKEN"));
            }else{
                throw $e;
            }
        }
        $this->cartService = new CartService($user);

        $userData = [
            "EMAIL" => $user->getEmail(),
            "NOM" => $user->getNom(),
            "PRENOM" => $user->getPrenom(),
        ];

        // --- Adresses ---

        $adresses = $this->utilisateurService->getAdresses($user);
        $adressesSerialized = [];
        foreach($adresses as $adresse){
            $adressesSerialized[] = $adresse->serialize();
        }

        // --- Panier

        $panier = $this->cartService->getCart($user);
        if (empty($panier->getProducts())) {
            return redirect("/panier");
        }
        $panierSerialized = $this->cartService->getCart($user)->serialize();

        // --- Render ---

        return Inertia::render("CheckOut",[
            "user" => $userData,
            "adresses" => $adressesSerialized,
            "produits" => $panierSerialized,
        ]);
    }

    public function valider(Request $request){
        $data = $request->post();

        $user = $this->utilisateurService->getAuthenticatedUser($request);
        if($user == null){
            $e = Exceptions::createError(518);
            return response()->json($e->getCode(),$e->httpCode);
        }

        if($data["livraison"] != "domicile" && $data["livraison"] != "magasin"){
            $e = Exceptions::createError(520);
            return response($e->getMessage(),$e->httpCode);
        }

        if(!isset($data["paiement"]["nom"]) || !isset($data["paiement"]["numéro"]) || !isset($data["paiement"]["mois"]) || !isset($data["paiement"]["année"]) || !isset($data["paiement"]["cryptograme"])){
            $e = Exceptions::createError(524);
            return response($e->getMessage(),$e->httpCode);
        }

        $this->cartService = new CartService($user);

        $commande = $this->cartService->getCart($user);
        $this->orderService->toOrder($commande);

        if ($data["livraison"] == "domicile") {
            // Domicile
            $this->utilisateurService->getAdresses($user); // Update les adresses
            $adresseCommande = $this->adresseService->findById(intval($data["adresse"]));
            $this->orderService->order($commande, $data['livraison'], $adresseCommande, $user);
        } else {
            // Magasin
            $adresseCommande = $this->adresseService->findByIdMagasin(intval($data["adresse"]));
            $this->orderService->order($commande, $data['livraison'], $adresseCommande, $user);
        }
    }
}
