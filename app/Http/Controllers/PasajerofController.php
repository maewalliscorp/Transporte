<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class PasajerofController extends Controller
{
    /**
     * Muestra la vista de pasajeros frecuentes.
     */
    public function index()
    {
        return view('auth.pasajerof');

    }
}
