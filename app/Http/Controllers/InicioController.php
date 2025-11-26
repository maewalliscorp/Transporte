<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InicioController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar página de bienvenida
     */
    public function index()
    {
        $user = Auth::user();
        $role = $user->getRole();

        // Nombres de roles en español
        $roleNames = [
            'administrador' => 'Administrador',
            'contador' => 'Contador',
            'operador' => 'Operador',
            'supervisor' => 'Supervisor',
            'pasajero' => 'Pasajero'
        ];

        return view('auth.inicio', [
            'user' => $user,
            'role' => $role,
            'roleName' => $roleNames[$role] ?? 'Usuario'
        ]);
    }
}
//no tiene permiso administrador
