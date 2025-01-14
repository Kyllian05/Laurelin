<?php

namespace App\Http\Controllers;

use App\Domain\ProductGroup\Services\CollectionService;
use App\Domain\Produit\Services\ProduitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(
        private CollectionService $collectionService,
        private ProduitService $produitService,
    ) {}

    public function index(Request $request)
    {
        $collectionProducts = $this->collectionService->getProducts($this->collectionService->findByName("Trinity"));
        return Inertia::render("Home",[
            "produits" => $this->produitService->serializes($collectionProducts),
            "collections" => $this->collectionService->getAllSerialize()
        ]);
    }

    public function search(string $query, Request $request)
    {
        $escapedQuery = addslashes($query);

        // A modifier
        $products = DB::select("SELECT ID, NOM FROM Produit WHERE NOM LIKE '%$escapedQuery%'");

        return response()->json($products);
    }
}
