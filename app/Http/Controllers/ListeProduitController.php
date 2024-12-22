<?php

namespace App\Http\Controllers;

use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ListeProduitController extends Controller
{
    public function index(Request $request){



        return Inertia::render("ListeProduit", []);
    }
}
