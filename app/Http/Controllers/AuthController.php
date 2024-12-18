<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class AuthController extends Controller
{
    public function index($params){
        return $this->login(array());
    }

    public function login($params){
        return Inertia::render("Auth",[
            "authMethod"=>"login"
        ]);
    }

    public function register($params){
        return Inertia::render("Auth",[
            "authMethod"=>"register"
        ]);
    }
}
