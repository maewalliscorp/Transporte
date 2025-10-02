<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class ContabilidadController extends Controller
{
    /**
     * Muestra la vista de contabilidad.
     */
    public function index()
    {
        return view('auth.contabilidad');

    }
}


