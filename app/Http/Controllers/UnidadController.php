<?php

namespace App\Http\Controllers;

use App\Models\HorarioModel;
use Illuminate\Http\Request;

class UnidadController extends Controller
{
    public function index()
    {
        return view('agregar.unidades');
    }
}
