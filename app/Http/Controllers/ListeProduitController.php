<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListeProduitController extends Controller
{

    public function index(Request $request)
    {
        // Récupérer les produits avec une limite ou une pagination si nécessaire
        $produits = Produit::select('ID', 'NOM', 'MATERIAUX', 'DESCRIPTION', 'PRIX', 'ETAT')
            ->where('ETAT', 'Disponible') // Exemple : seulement les produits disponibles
            ->orderBy('ANNEE_CREATION', 'desc') // Exemple : tri par année de création
            ->get();

        // Retourner les données au composant Vue
        return Inertia::render("ListeProduit", [
            'produits' => $produits
        ]);
    }
}
