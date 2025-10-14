<?php

namespace App\Http\Controllers;

use App\Models\RutaModel;
use Illuminate\Http\Request;

class RutaController extends Controller
{
    public function index()
    {
        return view('agregar.rutas');
    }
}
