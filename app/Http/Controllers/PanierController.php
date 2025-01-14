<?php

namespace App\Http\Controllers;

use App\Domain\Commande\Service\CartService;
use App\Domain\Produit\Services\ProduitService;
use App\Domain\Utilisateur\Services\UtilisateurService;
use App\Domain\Shared\Exceptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Inertia\Inertia;

class PanierController extends Controller
{
    private ?CartService $cartService = null;

    public function __construct(
        private UtilisateurService $utilisateurService,
        private ProduitService $produitService,
    ) {}

    public function index(Request $request){

        try{
            $user = $this->utilisateurService->getAuthenticatedUser($request);
            if($user == null){
                throw Exceptions::createError(518);
            }
        }catch(\Exception $e){
            if($e->getCode() == 518){
                return redirect("/auth")->cookie("redirect","/panier",10,null,null,false,false)->withCookie(Cookie::forget("TOKEN"));
            }else{
                throw $e;
            }
        }

        $this->cartService = new CartService($user);

        return Inertia::render("Panier",[
            "panier" => $this->cartService->getCart($user)->serialize(),
        ]);
    }

    public function ajouterAuPanier(Request $request){
        $data = $request->post();

        $user = $this->utilisateurService->getAuthenticatedUser($request);
        if($user == null){
            $e = Exceptions::createError(525);
            return response()->json($e->getMessage(),$e->httpCode);
        }
        $this->cartService = new CartService($user);
        $panier = $this->cartService->getCart($user);

        $this->cartService->addProduct(
            $panier,
            $this->produitService->findById($data["produit"]),
        );

        return response($panier->serialize(), 200)->header('Content-Type', 'application/json');
    }

    public function supprimerDuPanier(Request $request){
        $data = $request->post();

        $user = $this->utilisateurService->getAuthenticatedUser($request);
        if($user == null){
            $e = Exceptions::createError(525);
            return response()->json($e->getMessage(),$e->httpCode);
        }
        $this->cartService = new CartService($user);
        $panier = $this->cartService->getCart($user);

        try {
            $this->cartService->removeProduct(
                $panier,
                $this->produitService->findById($data["produit"]),
            );
            return response($panier->serialize(), 200)->header('Content-Type', 'application/json');
        } catch (\Exception $e) {
            return response($e->getMessage(), 500);
        }
    }

    function getNumberInPanier(Request $request){
        //TODO : Optimisation: pas besoin d'obtenir le détail des produits
        //TODO : Panier pour les utilisateurs non connectés
        $user = $this->utilisateurService->getAuthenticatedUser($request);
        if ($user) {
            $this->cartService = new CartService($user);
            $produitCommandes = $this->cartService->getCart($user)->getProducts();
            $result = 0;
            foreach ($produitCommandes as $produitCommande) {
                $result += $produitCommande->getQuantite();
            }
            return response($result,200)->header('Content-Type', 'application/json');
        }else{
            $e = Exceptions::createError(525);
            return response()->json($e->getMessage(),$e->httpCode);
        }
    }
}
