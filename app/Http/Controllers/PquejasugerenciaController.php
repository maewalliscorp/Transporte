<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
class PquejasugerenciaController extends Controller
{
    public function index()
    {
        return view('pasajero.p-queja-sugerencia');
    }
    public function store(Request $request)
    {
        // Lógica para guardar quejas y sugerencias
        return response()->json(['message' => 'Queja/sugerencia enviada exitosamente']);
    }
}


