<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;
use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Noticia;

class AdminController extends Controller
{
    private $usuarioModel;
    private $alumnoModel;
    private $cursoModel;
    private $noticiaModel;

    public function __construct()
    {
        parent::__construct();
        
        // Verificar autenticación
        if (!$this->getSession('admin')) {
            $this->redirect('/login');
        }
        
        $this->usuarioModel = new Usuario();
        $this->alumnoModel = new Alumno();
        $this->cursoModel = new Curso();
        $this->noticiaModel = new Noticia();
    }

    public function dashboard()
    {
        $totalUsuarios = $this->usuarioModel->count();
        $totalAlumnos = $this->alumnoModel->count();
        $totalCursos = $this->cursoModel->count();
        $totalNoticias = $this->noticiaModel->count();

        return $this->renderWithLayout('admin/dashboard', [
            'totalUsuarios' => $totalUsuarios,
            'totalAlumnos' => $totalAlumnos,
            'totalCursos' => $totalCursos,
            'totalNoticias' => $totalNoticias
        ]);
    }
} 