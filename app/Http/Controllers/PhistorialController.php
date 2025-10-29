<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class PhistorialController extends Controller
{
    public function index()
    {
        return view('pasajero.p-historial');

    }

    public function getHistorial(Request $request)
    {
        // Lógica para obtener el historial de viajes
        return response()->json([]);
    }
}

