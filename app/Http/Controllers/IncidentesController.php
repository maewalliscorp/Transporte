<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class InicioController extends Controller
{
    /**
     * Muestra la vista de Incidentes :)
     */
    public function index()
    {
        return view('auth.incidentes');

    }
}
