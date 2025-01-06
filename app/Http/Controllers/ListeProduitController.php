<?php

namespace App\Http\Controllers;

use App\Models\Produit;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ListeProduitController extends Controller
{

    public function index(Request $request)
    {
        // Récupérer les produits avec une limite ou une pagination si nécessaire
        $produits = DB::table('Produit')
            ->leftJoin('Image', 'Produit.ID', '=', 'Image.ID_PRODUIT')
            ->select(
                'Produit.ID',
                'Produit.NOM',
                'Produit.MATERIAUX',
                'Produit.DESCRIPTION',
                'Produit.PRIX',
                'Produit.ETAT',
                'Produit.ANNEE_CREATION',
                DB::raw('MIN(Image.URL) as URL') // MIN pour récupérer une seule image
            )
            ->where('Produit.ETAT', 'Disponible') // Seulement les produits disponibles
            ->groupBy('Produit.ID', 'Produit.NOM', 'Produit.MATERIAUX', 'Produit.DESCRIPTION', 'Produit.PRIX', 'Produit.ETAT', 'Produit.ANNEE_CREATION')
            ->get();

        // Retourner les données au composant Vue
        return Inertia::render("ListeProduit", [
            'produits' => $produits
        ]);
    }
}
