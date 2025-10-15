<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// CONTROLLER POUR LA PAGE D'ACCUEIL

class HomeController extends Controller
{
    public function index()
    {
        return view('home');

    }
}
