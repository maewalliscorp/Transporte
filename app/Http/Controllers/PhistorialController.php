<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PhistorialModel;
use Illuminate\Support\Facades\Auth;

class PhistorialController extends Controller
{
    public function index()
    {
        $phistorialModel = new PhistorialModel();

        // Obtener el id_pasajero del usuario actual
        $userId = Auth::id();
        $idPasajero = $phistorialModel->obtenerIdPasajeroPorUsuario($userId);

        // Obtener historial y rutas
        $historialViajes = [];
        $rutas = [];

        if ($idPasajero) {
            $historialViajes = $phistorialModel->obtenerHistorialViajes($idPasajero);
            $rutas = $phistorialModel->obtenerRutas();
        }

        return view('pasajero.p-historial', compact('historialViajes', 'rutas'));
    }
}

