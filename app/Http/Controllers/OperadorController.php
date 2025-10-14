<?php

namespace App\Http\Controllers;

use App\Models\OperadorModel;
use Illuminate\Http\Request;

class OperadorController extends Controller
{
    public function index()
    {
        return view('agregar.operadores');
    }
}
