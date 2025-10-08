<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class MantenimientoController extends Controller
{
    /**
     * Muestra la vista de mantenimientos.
     */
    public function index()
    {

        return view('auth.mantenimiento');

    }
}
