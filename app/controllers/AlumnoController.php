<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Alumno;
use App\Models\Usuario;
use App\Models\Curso;

class AlumnoController extends Controller
{
    private $alumnoModel;
    private $usuarioModel;
    private $cursoModel;

    public function __construct()
    {
        parent::__construct();
        
        // Verificar autenticación
        if (!$this->getSession('admin')) {
            $this->redirect('/login');
        }
        
        $this->alumnoModel = new Alumno();
        $this->usuarioModel = new Usuario();
        $this->cursoModel = new Curso();
    }

    public function index()
    {
        $page = (int)($this->getQueryParams()['page'] ?? 1);
        $perPage = 10;
        $search = $this->getQueryParams()['buscar'] ?? '';
        $usuarioId = $this->getQueryParams()['usuario_id'] ?? '';
        $cursoId = $this->getQueryParams()['curso_id'] ?? '';

        $conditions = [];
        if (!empty($usuarioId)) {
            $conditions['usuario_id'] = $usuarioId;
        }
        if (!empty($cursoId)) {
            // Para filtros de curso necesitamos una consulta especial
            $alumnos = $this->alumnoModel->getByCurso($cursoId);
        } else {
            $alumnos = $this->alumnoModel->paginate($page, $perPage, $conditions);
        }

        // Aplicar búsqueda si existe
        if (!empty($search)) {
            $alumnos['data'] = $this->alumnoModel->searchByName($search);
            $alumnos['total'] = count($alumnos['data']);
        }

        // Obtener datos para filtros
        $usuarios = $this->usuarioModel->findAll();
        $cursos = $this->cursoModel->findAll();

        return $this->renderWithLayout('alumnos/index', [
            'alumnos' => $alumnos,
            'usuarios' => $usuarios,
            'cursos' => $cursos,
            'search' => $search,
            'usuarioId' => $usuarioId,
            'cursoId' => $cursoId,
            'page' => $page
        ]);
    }

    public function create()
    {
        $errores = $this->getSession('errores', []);
        $this->unsetSession('errores');

        $usuarios = $this->usuarioModel->findAll();
        $cursos = $this->cursoModel->findAll();

        return $this->renderWithLayout('alumnos/create', [
            'errores' => $errores,
            'usuarios' => $usuarios,
            'cursos' => $cursos
        ]);
    }

    public function store()
    {
        if (!$this->isPost()) {
            $this->redirect('/alumnos');
        }

        $data = $this->getPostData();
        $errores = $this->validarDatos($data);

        if (!empty($errores)) {
            $this->setSession('errores', $errores);
            $this->redirect('/alumnos/create');
        }

        try {
            $alumnoId = $this->alumnoModel->create($data);

            // Asignar cursos si se seleccionaron
            if (!empty($data['cursos'])) {
                foreach ($data['cursos'] as $cursoId) {
                    $this->alumnoModel->assignToCurso($alumnoId, $cursoId);
                }
            }

            $this->setSession('success', 'Alumno creado exitosamente');
            $this->redirect('/alumnos');
        } catch (\Exception $e) {
            $this->setSession('errores', ['Error de base de datos: ' . $e->getMessage()]);
            $this->redirect('/alumnos/create');
        }
    }

    public function edit($id)
    {
        $alumno = $this->alumnoModel->getWithUsuarioAndCursos($id);
        
        if (!$alumno) {
            $this->redirect('/alumnos');
        }

        $errores = $this->getSession('errores', []);
        $this->unsetSession('errores');

        $usuarios = $this->usuarioModel->findAll();
        $cursos = $this->cursoModel->findAll();
        $cursosAsignados = $this->alumnoModel->getCursos($id);

        return $this->renderWithLayout('alumnos/edit', [
            'alumno' => $alumno,
            'errores' => $errores,
            'usuarios' => $usuarios,
            'cursos' => $cursos,
            'cursosAsignados' => $cursosAsignados
        ]);
    }

    public function update($id)
    {
        if (!$this->isPost()) {
            $this->redirect('/alumnos');
        }

        $data = $this->getPostData();
        $errores = $this->validarDatos($data);

        if (!empty($errores)) {
            $this->setSession('errores', $errores);
            $this->redirect("/alumnos/edit/{$id}");
        }

        try {
            // Actualizar datos del alumno
            $this->alumnoModel->update($id, $data);

            // Actualizar cursos asignados
            $cursosActuales = $this->alumnoModel->getCursos($id);
            $cursosSeleccionados = $data['cursos'] ?? [];

            // Remover cursos no seleccionados
            foreach ($cursosActuales as $curso) {
                if (!in_array($curso['id'], $cursosSeleccionados)) {
                    $this->alumnoModel->removeFromCurso($id, $curso['id']);
                }
            }

            // Agregar nuevos cursos
            foreach ($cursosSeleccionados as $cursoId) {
                $existe = false;
                foreach ($cursosActuales as $curso) {
                    if ($curso['id'] == $cursoId) {
                        $existe = true;
                        break;
                    }
                }
                if (!$existe) {
                    $this->alumnoModel->assignToCurso($id, $cursoId);
                }
            }

            $this->setSession('success', 'Alumno actualizado exitosamente');
            $this->redirect('/alumnos');
        } catch (\Exception $e) {
            $this->setSession('errores', ['Error de base de datos: ' . $e->getMessage()]);
            $this->redirect("/alumnos/edit/{$id}");
        }
    }

    public function delete($id)
    {
        try {
            // Remover todas las asignaciones de cursos
            $cursos = $this->alumnoModel->getCursos($id);
            foreach ($cursos as $curso) {
                $this->alumnoModel->removeFromCurso($id, $curso['id']);
            }

            $this->alumnoModel->delete($id);
            $this->setSession('success', 'Alumno eliminado exitosamente');
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al eliminar alumno');
        }
        
        $this->redirect('/alumnos');
    }

    public function view($id)
    {
        $alumno = $this->alumnoModel->getWithUsuarioAndCursos($id);
        
        if (!$alumno) {
            $this->redirect('/alumnos');
        }

        $cursos = $this->alumnoModel->getCursos($id);

        return $this->renderWithLayout('alumnos/view', [
            'alumno' => $alumno,
            'cursos' => $cursos
        ]);
    }

    private function validarDatos($data)
    {
        $errores = [];

        if (empty($data['nombre'])) {
            $errores[] = "El nombre es obligatorio.";
        }
        if (empty($data['apellido'])) {
            $errores[] = "El apellido es obligatorio.";
        }
        if (empty($data['usuario_id'])) {
            $errores[] = "Debe seleccionar una familia.";
        }

        return $errores;
    }
} 