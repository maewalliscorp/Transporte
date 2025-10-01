<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class InicioController extends Controller
{
    /**
     * Muestra la vista de inicio después del login.
     */
    public function index()
    {
        return view('auth.inicio');

    }
}

