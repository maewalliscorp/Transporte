<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PregistroController extends Controller
{
    public function index()
    {
        return view('pasajero.p-registro');

    }
    public function store(Request $request)
    {
        // Para guardar el registro de pasajero
        return response()->json(['message' => 'Pasajero registrado exitosamente']);
    }
}

