<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Unidad;
use App\Models\Operador;
use App\Models\Ruta;
use App\Models\Horario;
use App\Models\Asignacion;
use Illuminate\Http\Request;

class InicioController extends Controller
{
    public function index()
    {
        // Para los selects
        $unidades = Unidad::all();
        $operadores = Operador::all();
        $rutas = Ruta::all();
        $horarios = Horario::all();

        // Obtener los asignados reales, incluyendo la relación usuario bajo operador
        $asignados = Asignacion::with([
            'unidad',
            'operador',  // para obtener el nombre del usuario asociado
            'ruta',
            'horario'
        ])->get();

        // Obtener los ID de unidades ya asignadas
        $idsUnidadesAsignadas = Asignacion::pluck('id_unidad')->toArray();

        // Unidades NO asignadas (disponibles)
        $unidadesDisponibles = Unidad::whereNotIn('id_unidad', $idsUnidadesAsignadas)->get();

        // Convertir en una estructura para pasar a la vista
        $disponibles = $unidadesDisponibles->map(function ($unidad) {
            return (object)[
                'unidad' => $unidad,
                // No hay operador verdadero aún para estas unidades
                'operador' => (object)[
                    'usuario' => (object)['name' => '—'],
                    'licencia' => '—'
                ],
                'ruta' => (object)['nombre' => '—'],
                'horario' => (object)[
                    'horaSalida' => '—',
                    'horaLlegada' => '—',
                    'fecha' => '—'
                ],
            ];
        });

        return view('auth.inicio', compact(
            'unidades',
            'operadores',
            'rutas',
            'horarios',
            'asignados',
            'disponibles'
        ));
    }

    public function asignar(Request $request)
    {
        // Validación
        $request->validate([
            'unidad' => 'required|exists:unidad,id_unidad',
            'operador' => 'required|exists:operador,id_operator',
            'ruta' => 'required|exists:ruta,id_ruta',
            'horario' => 'required|exists:horario,id_horario',
        ]);

        // Crear asignación
        Asignacion::create([
            'id_unidad'  => $request->unidad,
            'id_operator' => $request->operador,
            'id_ruta'     => $request->ruta,
            'id_horario'  => $request->horario,
        ]);

        return redirect()->route('auth.inicio')->with('success', 'Asignación creada correctamente.');
    }
}
