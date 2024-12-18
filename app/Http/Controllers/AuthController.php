<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AuthController extends Controller
{
    public function index(string $method = ""){
        if($method == ""){
            $method = "login";
        }
        return Inertia::render("Auth",[
            "authMethod"=>$method
        ]);
    }

    public function authentificate(string $method,Request $request){
        $data = $request->post();

        \Log::info($data);
    }
}
