<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Estudante;
use App\Models\Unidade;
use App\Models\Curso;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Dashboard principal
     */
    public function index()
    {
        $usuario = auth()->user();
        
        $totalEstudantes = Estudante::count();
        $totalUnidades = Unidade::count();
        $totalCursos = Curso::count();
        
        return view('admin.home', compact('usuario', 'totalEstudantes', 'totalUnidades', 'totalCursos'));
    }
}
