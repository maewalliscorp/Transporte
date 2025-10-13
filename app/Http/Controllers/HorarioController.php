<?php

namespace App\Http\Controllers;

use App\Models\HorarioModel;
use Illuminate\Http\Request;

class HorarioController extends Controller
{
    public function index()
    {
        $horarioModel = new HorarioModel();
        $horarios = $horarioModel->obtenerTodos();

        return view('agregar.horarios', compact('horarios'));
    }
}
