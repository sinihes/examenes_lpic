<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index() {
    $user = auth()->user(); // Obtiene el usuario logueado
    $results = $user->results()->with('exam')->get(); // Sus resultados

    return view('dashboard', compact('user', 'results'));
    }
}
