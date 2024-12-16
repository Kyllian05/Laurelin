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
        // Récupère le chemin de l'URL
        $path = trim($request->path(), '/'); // Supprime les "/" en début et fin

        // Si le chemin est vide ("/"), utilise le contrôleur HomeController par défaut
        
        $controller = explode("/",$path)[0];

        $method = "index";
        if ($controller === '') {
            $controllerClass = "App\\Http\\Controllers\\HomeController";
        } else {
            // Pour les autres chemins, génère dynamiquement le contrôleur et la méthode
            $controllerClass = "App\\Http\\Controllers\\" . ucfirst($controller) . "Controller";
        }

        // Vérifie si le contrôleur et la méthode existent
        if (class_exists($controllerClass) && method_exists($controllerClass, $method)) {
            return app($controllerClass)->$method(explode("/",$path)); // Appelle dynamiquement la méthode
        }

        // Si rien n'est trouvé, retourne une erreur 404
        throw new NotFoundHttpException("Controller or method not found.");
    }
}