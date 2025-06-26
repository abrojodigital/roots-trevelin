<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;
use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Noticia;

class ApiController extends Controller
{
    private $usuarioModel;
    private $alumnoModel;
    private $cursoModel;
    private $noticiaModel;

    public function __construct()
    {
        parent::__construct();
        
        // Configurar headers para API
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        $this->usuarioModel = new Usuario();
        $this->alumnoModel = new Alumno();
        $this->cursoModel = new Curso();
        $this->noticiaModel = new Noticia();
    }

    // ==================== USUARIOS API ====================
    
    public function getUsuarios()
    {
        try {
            $page = (int)($this->getQueryParams()['page'] ?? 1);
            $limit = (int)($this->getQueryParams()['limit'] ?? 10);
            $search = $this->getQueryParams()['search'] ?? '';

            if (!empty($search)) {
                $usuarios = $this->usuarioModel->searchByName($search);
                $total = count($usuarios);
            } else {
                $result = $this->usuarioModel->paginate($page, $limit);
                $usuarios = $result['data'];
                $total = $result['total'];
            }

            $this->json([
                'success' => true,
                'data' => $usuarios,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'pages' => ceil($total / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Error al obtener usuarios: ' . $e->getMessage());
        }
    }

    public function getUsuario($id)
    {
        try {
            $usuario = $this->usuarioModel->getWithAlumnos($id);
            
            if (!$usuario) {
                $this->jsonError('Usuario no encontrado', 404);
                return;
            }

            $this->json([
                'success' => true,
                'data' => $usuario
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Error al obtener usuario: ' . $e->getMessage());
        }
    }

    public function createUsuario()
    {
        try {
            if (!$this->isPost()) {
                $this->jsonError('Método no permitido', 405);
                return;
            }

            $data = $this->getPostData();
            $errores = $this->validarUsuario($data);

            if (!empty($errores)) {
                $this->jsonError('Datos inválidos', 400, $errores);
                return;
            }

            $id = $this->usuarioModel->createWithPassword($data);
            
            $this->json([
                'success' => true,
                'message' => 'Usuario creado exitosamente',
                'data' => ['id' => $id]
            ], 201);
        } catch (\Exception $e) {
            $this->jsonError('Error al crear usuario: ' . $e->getMessage());
        }
    }

    public function updateUsuario($id)
    {
        try {
            if (!$this->isPost()) {
                $this->jsonError('Método no permitido', 405);
                return;
            }

            $usuario = $this->usuarioModel->findById($id);
            if (!$usuario) {
                $this->jsonError('Usuario no encontrado', 404);
                return;
            }

            $data = $this->getPostData();
            $errores = $this->validarUsuario($data, false);

            if (!empty($errores)) {
                $this->jsonError('Datos inválidos', 400, $errores);
                return;
            }

            $this->usuarioModel->updateWithPassword($id, $data);
            
            $this->json([
                'success' => true,
                'message' => 'Usuario actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Error al actualizar usuario: ' . $e->getMessage());
        }
    }

    public function deleteUsuario($id)
    {
        try {
            $usuario = $this->usuarioModel->findById($id);
            if (!$usuario) {
                $this->jsonError('Usuario no encontrado', 404);
                return;
            }

            $this->usuarioModel->delete($id);
            
            $this->json([
                'success' => true,
                'message' => 'Usuario eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Error al eliminar usuario: ' . $e->getMessage());
        }
    }

    // ==================== ALUMNOS API ====================
    
    public function getAlumnos()
    {
        try {
            $page = (int)($this->getQueryParams()['page'] ?? 1);
            $limit = (int)($this->getQueryParams()['limit'] ?? 10);
            $usuarioId = $this->getQueryParams()['usuario_id'] ?? '';
            $cursoId = $this->getQueryParams()['curso_id'] ?? '';

            $conditions = [];
            if (!empty($usuarioId)) {
                $conditions['usuario_id'] = $usuarioId;
            }

            if (!empty($cursoId)) {
                $alumnos = $this->alumnoModel->getByCurso($cursoId);
                $total = count($alumnos);
            } else {
                $result = $this->alumnoModel->paginate($page, $limit, $conditions);
                $alumnos = $result['data'];
                $total = $result['total'];
            }

            $this->json([
                'success' => true,
                'data' => $alumnos,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $total,
                    'pages' => ceil($total / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Error al obtener alumnos: ' . $e->getMessage());
        }
    }

    public function getAlumno($id)
    {
        try {
            $alumno = $this->alumnoModel->getWithUsuarioAndCursos($id);
            
            if (!$alumno) {
                $this->jsonError('Alumno no encontrado', 404);
                return;
            }

            $cursos = $this->alumnoModel->getCursos($id);

            $this->json([
                'success' => true,
                'data' => [
                    'alumno' => $alumno,
                    'cursos' => $cursos
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Error al obtener alumno: ' . $e->getMessage());
        }
    }

    // ==================== CURSOS API ====================
    
    public function getCursos()
    {
        try {
            $page = (int)($this->getQueryParams()['page'] ?? 1);
            $limit = (int)($this->getQueryParams()['limit'] ?? 10);
            $nivel = $this->getQueryParams()['nivel'] ?? '';

            $conditions = [];
            if (!empty($nivel)) {
                $conditions['nivel'] = $nivel;
            }

            $result = $this->cursoModel->paginate($page, $limit, $conditions);
            
            $this->json([
                'success' => true,
                'data' => $result['data'],
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => $result['total'],
                    'pages' => ceil($result['total'] / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Error al obtener cursos: ' . $e->getMessage());
        }
    }

    public function getCurso($id)
    {
        try {
            $curso = $this->cursoModel->getWithAlumnosDetail($id);
            
            if (!$curso) {
                $this->jsonError('Curso no encontrado', 404);
                return;
            }

            $alumnos = $this->cursoModel->getAlumnos($id);

            $this->json([
                'success' => true,
                'data' => [
                    'curso' => $curso,
                    'alumnos' => $alumnos
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Error al obtener curso: ' . $e->getMessage());
        }
    }

    // ==================== NOTICIAS API ====================
    
    public function getNoticias()
    {
        try {
            $page = (int)($this->getQueryParams()['page'] ?? 1);
            $limit = (int)($this->getQueryParams()['limit'] ?? 10);
            $tipo = $this->getQueryParams()['tipo'] ?? '';
            $usuarioId = $this->getQueryParams()['usuario_id'] ?? '';
            $alumnoId = $this->getQueryParams()['alumno_id'] ?? '';
            $cursoId = $this->getQueryParams()['curso_id'] ?? '';

            $noticias = [];

            if (!empty($usuarioId)) {
                $noticias = $this->noticiaModel->getForUsuario($usuarioId);
            } elseif (!empty($alumnoId)) {
                $noticias = $this->noticiaModel->getForAlumno($alumnoId);
            } elseif (!empty($cursoId)) {
                $noticias = $this->noticiaModel->getForCurso($cursoId);
            } elseif ($tipo === 'general') {
                $noticias = $this->noticiaModel->getGenerales();
            } else {
                $result = $this->noticiaModel->paginate($page, $limit);
                $noticias = $result['data'];
            }

            $this->json([
                'success' => true,
                'data' => $noticias,
                'pagination' => [
                    'page' => $page,
                    'limit' => $limit,
                    'total' => count($noticias),
                    'pages' => ceil(count($noticias) / $limit)
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Error al obtener noticias: ' . $e->getMessage());
        }
    }

    public function getNoticia($id)
    {
        try {
            $noticia = $this->noticiaModel->findById($id);
            
            if (!$noticia) {
                $this->jsonError('Noticia no encontrada', 404);
                return;
            }

            $usuarios = $this->noticiaModel->getUsuarios($id);
            $alumnos = $this->noticiaModel->getAlumnos($id);
            $cursos = $this->noticiaModel->getCursos($id);

            $this->json([
                'success' => true,
                'data' => [
                    'noticia' => $noticia,
                    'usuarios' => $usuarios,
                    'alumnos' => $alumnos,
                    'cursos' => $cursos
                ]
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Error al obtener noticia: ' . $e->getMessage());
        }
    }

    // ==================== DASHBOARD API ====================
    
    public function getDashboard()
    {
        try {
            $stats = [
                'usuarios' => $this->usuarioModel->count(),
                'alumnos' => $this->alumnoModel->count(),
                'cursos' => $this->cursoModel->count(),
                'noticias' => $this->noticiaModel->count()
            ];

            $this->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            $this->jsonError('Error al obtener estadísticas: ' . $e->getMessage());
        }
    }

    // ==================== HELPERS ====================
    
    private function jsonError($message, $code = 500, $errors = [])
    {
        http_response_code($code);
        $this->json([
            'success' => false,
            'error' => $message,
            'errors' => $errors
        ]);
    }

    private function validarUsuario($data, $isCreate = true)
    {
        $errores = [];

        if ($isCreate || !empty($data['nombre'])) {
            if (empty($data['nombre'])) {
                $errores[] = "El nombre es obligatorio";
            }
        }

        if ($isCreate || !empty($data['apellido'])) {
            if (empty($data['apellido'])) {
                $errores[] = "El apellido es obligatorio";
            }
        }

        if ($isCreate || !empty($data['email'])) {
            if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $errores[] = "Email no válido";
            }
        }

        if ($isCreate) {
            if (empty($data['contrasena'])) {
                $errores[] = "La contraseña es obligatoria";
            } elseif (strlen($data['contrasena']) < 6) {
                $errores[] = "La contraseña debe tener al menos 6 caracteres";
            }
        }

        return $errores;
    }
} 