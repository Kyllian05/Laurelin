<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class DynamicController
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Récupérer le segment de l'URL (par exemple : "/home" → "home")
        $path = $request->path(); // Récupère le chemin (sans domaine, ex: "home")

        // Construit le nom du contrôleur et de la méthode
        $controllerClass = "App\\Http\\Controllers\\" . ucfirst($path) . "Controller";
        $method = "index"; // Méthode par défaut

        // Vérifie si le contrôleur et la méthode existent
        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            return app($controllerClass)->$method($request); // Appelle dynamiquement la méthode
        }

        // Si rien n'est trouvé, retourne une erreur 404
        throw new NotFoundHttpException("Controller or method not found.");
    }
}