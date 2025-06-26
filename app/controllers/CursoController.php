<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Curso;
use App\Models\Alumno;

class CursoController extends Controller
{
    private $cursoModel;
    private $alumnoModel;

    public function __construct()
    {
        parent::__construct();
        
        // Verificar autenticación
        if (!$this->getSession('admin')) {
            $this->redirect('/login');
        }
        
        $this->cursoModel = new Curso();
        $this->alumnoModel = new Alumno();
    }

    public function index()
    {
        $page = (int)($this->getQueryParams()['page'] ?? 1);
        $perPage = 10;
        $search = $this->getQueryParams()['buscar'] ?? '';
        $nivel = $this->getQueryParams()['nivel'] ?? '';

        $conditions = [];
        if (!empty($nivel)) {
            $conditions['nivel'] = $nivel;
        }

        $cursos = $this->cursoModel->paginate($page, $perPage, $conditions);

        // Aplicar búsqueda si existe
        if (!empty($search)) {
            $cursos['data'] = $this->cursoModel->searchByName($search);
            $cursos['total'] = count($cursos['data']);
        }

        return $this->renderWithLayout('cursos/index', [
            'cursos' => $cursos,
            'search' => $search,
            'nivel' => $nivel,
            'page' => $page
        ]);
    }

    public function create()
    {
        $errores = $this->getSession('errores', []);
        $this->unsetSession('errores');

        $alumnos = $this->alumnoModel->findAll();

        return $this->renderWithLayout('cursos/create', [
            'errores' => $errores,
            'alumnos' => $alumnos
        ]);
    }

    public function store()
    {
        if (!$this->isPost()) {
            $this->redirect('/cursos');
        }

        $data = $this->getPostData();
        $errores = $this->validarDatos($data);

        if (!empty($errores)) {
            $this->setSession('errores', $errores);
            $this->redirect('/cursos/create');
        }

        try {
            $cursoId = $this->cursoModel->create($data);

            // Asignar alumnos si se seleccionaron
            if (!empty($data['alumnos'])) {
                foreach ($data['alumnos'] as $alumnoId) {
                    $this->cursoModel->addAlumno($cursoId, $alumnoId);
                }
            }

            $this->setSession('success', 'Curso creado exitosamente');
            $this->redirect('/cursos');
        } catch (\Exception $e) {
            $this->setSession('errores', ['Error de base de datos: ' . $e->getMessage()]);
            $this->redirect('/cursos/create');
        }
    }

    public function edit($id)
    {
        $curso = $this->cursoModel->getWithAlumnosDetail($id);
        
        if (!$curso) {
            $this->redirect('/cursos');
        }

        $errores = $this->getSession('errores', []);
        $this->unsetSession('errores');

        $alumnos = $this->alumnoModel->findAll();
        $alumnosAsignados = $this->cursoModel->getAlumnos($id);
        $alumnosDisponibles = $this->cursoModel->getAvailableAlumnos($id);

        return $this->renderWithLayout('cursos/edit', [
            'curso' => $curso,
            'errores' => $errores,
            'alumnos' => $alumnos,
            'alumnosAsignados' => $alumnosAsignados,
            'alumnosDisponibles' => $alumnosDisponibles
        ]);
    }

    public function update($id)
    {
        if (!$this->isPost()) {
            $this->redirect('/cursos');
        }

        $data = $this->getPostData();
        $errores = $this->validarDatos($data);

        if (!empty($errores)) {
            $this->setSession('errores', $errores);
            $this->redirect("/cursos/edit/{$id}");
        }

        try {
            // Actualizar datos del curso
            $this->cursoModel->update($id, $data);

            // Actualizar alumnos asignados
            $alumnosActuales = $this->cursoModel->getAlumnos($id);
            $alumnosSeleccionados = $data['alumnos'] ?? [];

            // Remover alumnos no seleccionados
            foreach ($alumnosActuales as $alumno) {
                if (!in_array($alumno['id'], $alumnosSeleccionados)) {
                    $this->cursoModel->removeAlumno($id, $alumno['id']);
                }
            }

            // Agregar nuevos alumnos
            foreach ($alumnosSeleccionados as $alumnoId) {
                $existe = false;
                foreach ($alumnosActuales as $alumno) {
                    if ($alumno['id'] == $alumnoId) {
                        $existe = true;
                        break;
                    }
                }
                if (!$existe) {
                    $this->cursoModel->addAlumno($id, $alumnoId);
                }
            }

            $this->setSession('success', 'Curso actualizado exitosamente');
            $this->redirect('/cursos');
        } catch (\Exception $e) {
            $this->setSession('errores', ['Error de base de datos: ' . $e->getMessage()]);
            $this->redirect("/cursos/edit/{$id}");
        }
    }

    public function delete($id)
    {
        try {
            // Remover todas las asignaciones de alumnos
            $alumnos = $this->cursoModel->getAlumnos($id);
            foreach ($alumnos as $alumno) {
                $this->cursoModel->removeAlumno($id, $alumno['id']);
            }

            $this->cursoModel->delete($id);
            $this->setSession('success', 'Curso eliminado exitosamente');
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al eliminar curso');
        }
        
        $this->redirect('/cursos');
    }

    public function view($id)
    {
        $curso = $this->cursoModel->getWithAlumnosDetail($id);
        
        if (!$curso) {
            $this->redirect('/cursos');
        }

        $alumnos = $this->cursoModel->getAlumnos($id);

        return $this->renderWithLayout('cursos/view', [
            'curso' => $curso,
            'alumnos' => $alumnos
        ]);
    }

    private function validarDatos($data)
    {
        $errores = [];

        if (empty($data['nombre'])) {
            $errores[] = "El nombre del curso es obligatorio.";
        }
        if (empty($data['nivel'])) {
            $errores[] = "El nivel es obligatorio.";
        }

        return $errores;
    }
} 