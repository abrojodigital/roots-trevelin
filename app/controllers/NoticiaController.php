<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Noticia;
use App\Models\Usuario;
use App\Models\Alumno;
use App\Models\Curso;

class NoticiaController extends Controller
{
    private $noticiaModel;
    private $usuarioModel;
    private $alumnoModel;
    private $cursoModel;

    public function __construct()
    {
        parent::__construct();
        
        // Verificar autenticación
        if (!$this->getSession('admin')) {
            $this->redirect('/login');
        }
        
        $this->noticiaModel = new Noticia();
        $this->usuarioModel = new Usuario();
        $this->alumnoModel = new Alumno();
        $this->cursoModel = new Curso();
    }

    public function index()
    {
        $page = (int)($this->getQueryParams()['page'] ?? 1);
        $perPage = 10;
        $search = $this->getQueryParams()['buscar'] ?? '';
        $tipo = $this->getQueryParams()['tipo'] ?? '';
        $fecha = $this->getQueryParams()['fecha'] ?? '';

        $conditions = [];
        if (!empty($fecha)) {
            $conditions['DATE(fecha_creacion)'] = $fecha;
        }

        $noticias = $this->noticiaModel->paginate($page, $perPage, $conditions);

        // Aplicar búsqueda si existe
        if (!empty($search)) {
            $noticias['data'] = $this->noticiaModel->searchByTitle($search);
            $noticias['total'] = count($noticias['data']);
        }

        // Filtrar por tipo si se especifica
        if (!empty($tipo)) {
            $noticias['data'] = array_filter($noticias['data'], function($noticia) use ($tipo) {
                switch ($tipo) {
                    case 'general':
                        return empty($noticia['total_usuarios']) && 
                               empty($noticia['total_alumnos']) && 
                               empty($noticia['total_cursos']);
                    case 'usuario':
                        return !empty($noticia['total_usuarios']);
                    case 'alumno':
                        return !empty($noticia['total_alumnos']);
                    case 'curso':
                        return !empty($noticia['total_cursos']);
                    default:
                        return true;
                }
            });
            $noticias['data'] = array_values($noticias['data']);
            $noticias['total'] = count($noticias['data']);
        }

        return $this->renderWithLayout('noticias/index', [
            'noticias' => $noticias,
            'search' => $search,
            'tipo' => $tipo,
            'fecha' => $fecha,
            'page' => $page
        ]);
    }

    public function create()
    {
        $errores = $this->getSession('errores', []);
        $this->unsetSession('errores');

        $usuarios = $this->usuarioModel->findAll();
        $alumnos = $this->alumnoModel->findAll();
        $cursos = $this->cursoModel->findAll();

        return $this->renderWithLayout('noticias/create', [
            'errores' => $errores,
            'usuarios' => $usuarios,
            'alumnos' => $alumnos,
            'cursos' => $cursos
        ]);
    }

    public function store()
    {
        if (!$this->isPost()) {
            $this->redirect('/noticias');
        }

        $data = $this->getPostData();
        $errores = $this->validarDatos($data);

        if (!empty($errores)) {
            $this->setSession('errores', $errores);
            $this->redirect('/noticias/create');
        }

        try {
            $data['fecha_creacion'] = date('Y-m-d H:i:s');
            $noticiaId = $this->noticiaModel->create($data);

            // Asignar usuarios si se seleccionaron
            if (!empty($data['usuarios'])) {
                foreach ($data['usuarios'] as $usuarioId) {
                    $this->noticiaModel->assignToUsuario($noticiaId, $usuarioId);
                }
            }

            // Asignar alumnos si se seleccionaron
            if (!empty($data['alumnos'])) {
                foreach ($data['alumnos'] as $alumnoId) {
                    $this->noticiaModel->assignToAlumno($noticiaId, $alumnoId);
                }
            }

            // Asignar cursos si se seleccionaron
            if (!empty($data['cursos'])) {
                foreach ($data['cursos'] as $cursoId) {
                    $this->noticiaModel->assignToCurso($noticiaId, $cursoId);
                }
            }

            $this->setSession('success', 'Noticia creada exitosamente');
            $this->redirect('/noticias');
        } catch (\Exception $e) {
            $this->setSession('errores', ['Error de base de datos: ' . $e->getMessage()]);
            $this->redirect('/noticias/create');
        }
    }

    public function edit($id)
    {
        $noticia = $this->noticiaModel->findById($id);
        
        if (!$noticia) {
            $this->redirect('/noticias');
        }

        $errores = $this->getSession('errores', []);
        $this->unsetSession('errores');

        $usuarios = $this->usuarioModel->findAll();
        $alumnos = $this->alumnoModel->findAll();
        $cursos = $this->cursoModel->findAll();

        $usuariosAsignados = $this->noticiaModel->getUsuarios($id);
        $alumnosAsignados = $this->noticiaModel->getAlumnos($id);
        $cursosAsignados = $this->noticiaModel->getCursos($id);

        return $this->renderWithLayout('noticias/edit', [
            'noticia' => $noticia,
            'errores' => $errores,
            'usuarios' => $usuarios,
            'alumnos' => $alumnos,
            'cursos' => $cursos,
            'usuariosAsignados' => $usuariosAsignados,
            'alumnosAsignados' => $alumnosAsignados,
            'cursosAsignados' => $cursosAsignados
        ]);
    }

    public function update($id)
    {
        if (!$this->isPost()) {
            $this->redirect('/noticias');
        }

        $data = $this->getPostData();
        $errores = $this->validarDatos($data);

        if (!empty($errores)) {
            $this->setSession('errores', $errores);
            $this->redirect("/noticias/edit/{$id}");
        }

        try {
            // Actualizar datos de la noticia
            $this->noticiaModel->update($id, $data);

            // Actualizar asignaciones
            $this->actualizarAsignaciones($id, $data);

            $this->setSession('success', 'Noticia actualizada exitosamente');
            $this->redirect('/noticias');
        } catch (\Exception $e) {
            $this->setSession('errores', ['Error de base de datos: ' . $e->getMessage()]);
            $this->redirect("/noticias/edit/{$id}");
        }
    }

    public function delete($id)
    {
        try {
            // Remover todas las asignaciones
            $usuarios = $this->noticiaModel->getUsuarios($id);
            foreach ($usuarios as $usuario) {
                $this->noticiaModel->removeFromUsuario($id, $usuario['id']);
            }

            $alumnos = $this->noticiaModel->getAlumnos($id);
            foreach ($alumnos as $alumno) {
                $this->noticiaModel->removeFromAlumno($id, $alumno['id']);
            }

            $cursos = $this->noticiaModel->getCursos($id);
            foreach ($cursos as $curso) {
                $this->noticiaModel->removeFromCurso($id, $curso['id']);
            }

            $this->noticiaModel->delete($id);
            $this->setSession('success', 'Noticia eliminada exitosamente');
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al eliminar noticia');
        }
        
        $this->redirect('/noticias');
    }

    public function view($id)
    {
        $noticia = $this->noticiaModel->findById($id);
        
        if (!$noticia) {
            $this->redirect('/noticias');
        }

        $usuarios = $this->noticiaModel->getUsuarios($id);
        $alumnos = $this->noticiaModel->getAlumnos($id);
        $cursos = $this->noticiaModel->getCursos($id);

        return $this->renderWithLayout('noticias/view', [
            'noticia' => $noticia,
            'usuarios' => $usuarios,
            'alumnos' => $alumnos,
            'cursos' => $cursos
        ]);
    }

    private function validarDatos($data)
    {
        $errores = [];

        if (empty($data['titulo'])) {
            $errores[] = "El título es obligatorio.";
        }
        if (empty($data['contenido'])) {
            $errores[] = "El contenido es obligatorio.";
        }

        return $errores;
    }

    private function actualizarAsignaciones($noticiaId, $data)
    {
        // Actualizar usuarios
        $usuariosActuales = $this->noticiaModel->getUsuarios($noticiaId);
        $usuariosSeleccionados = $data['usuarios'] ?? [];

        foreach ($usuariosActuales as $usuario) {
            if (!in_array($usuario['id'], $usuariosSeleccionados)) {
                $this->noticiaModel->removeFromUsuario($noticiaId, $usuario['id']);
            }
        }

        foreach ($usuariosSeleccionados as $usuarioId) {
            $existe = false;
            foreach ($usuariosActuales as $usuario) {
                if ($usuario['id'] == $usuarioId) {
                    $existe = true;
                    break;
                }
            }
            if (!$existe) {
                $this->noticiaModel->assignToUsuario($noticiaId, $usuarioId);
            }
        }

        // Actualizar alumnos
        $alumnosActuales = $this->noticiaModel->getAlumnos($noticiaId);
        $alumnosSeleccionados = $data['alumnos'] ?? [];

        foreach ($alumnosActuales as $alumno) {
            if (!in_array($alumno['id'], $alumnosSeleccionados)) {
                $this->noticiaModel->removeFromAlumno($noticiaId, $alumno['id']);
            }
        }

        foreach ($alumnosSeleccionados as $alumnoId) {
            $existe = false;
            foreach ($alumnosActuales as $alumno) {
                if ($alumno['id'] == $alumnoId) {
                    $existe = true;
                    break;
                }
            }
            if (!$existe) {
                $this->noticiaModel->assignToAlumno($noticiaId, $alumnoId);
            }
        }

        // Actualizar cursos
        $cursosActuales = $this->noticiaModel->getCursos($noticiaId);
        $cursosSeleccionados = $data['cursos'] ?? [];

        foreach ($cursosActuales as $curso) {
            if (!in_array($curso['id'], $cursosSeleccionados)) {
                $this->noticiaModel->removeFromCurso($noticiaId, $curso['id']);
            }
        }

        foreach ($cursosSeleccionados as $cursoId) {
            $existe = false;
            foreach ($cursosActuales as $curso) {
                if ($curso['id'] == $cursoId) {
                    $existe = true;
                    break;
                }
            }
            if (!$existe) {
                $this->noticiaModel->assignToCurso($noticiaId, $cursoId);
            }
        }
    }
} 