<?php


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class MonitoreoController extends Controller
{
    /**
     * Muestra la vista de monitoreo.
     */
    public function index()
    {
        return view('auth.monitoreo');

    }
}


