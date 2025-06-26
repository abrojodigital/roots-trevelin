<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Noticia;
use App\Models\Usuario;
use App\Models\Alumno;
use App\Models\Curso;

class NotificacionController extends Controller
{
    private $noticiaModel;
    private $usuarioModel;
    private $alumnoModel;
    private $cursoModel;
    
    // Configuración OneSignal
    private $appId = 'YOUR_ONESIGNAL_APP_ID';
    private $restApiKey = 'YOUR_ONESIGNAL_REST_API_KEY';

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
        $notificaciones = $this->noticiaModel->getRecent(10);
        
        return $this->renderWithLayout('notificaciones/index', [
            'notificaciones' => $notificaciones
        ]);
    }

    public function create()
    {
        $usuarios = $this->usuarioModel->findAll();
        $alumnos = $this->alumnoModel->findAll();
        $cursos = $this->cursoModel->findAll();
        
        return $this->renderWithLayout('notificaciones/create', [
            'usuarios' => $usuarios,
            'alumnos' => $alumnos,
            'cursos' => $cursos
        ]);
    }

    public function store()
    {
        try {
            $data = $this->getPostData();
            $errores = $this->validarNotificacion($data);

            if (!empty($errores)) {
                $this->setSession('errores', $errores);
                $this->setSession('old_data', $data);
                $this->redirect('/notificaciones/create');
                return;
            }

            // Crear noticia
            $noticiaId = $this->noticiaModel->create($data);

            // Enviar notificación push
            $this->enviarNotificacionPush($data, $noticiaId);

            $this->setSession('success', 'Notificación enviada exitosamente');
            $this->redirect('/notificaciones');
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al enviar notificación: ' . $e->getMessage());
            $this->redirect('/notificaciones/create');
        }
    }

    public function enviarGeneral()
    {
        try {
            $data = $this->getPostData();
            
            if (empty($data['titulo']) || empty($data['contenido'])) {
                $this->setSession('error', 'Título y contenido son obligatorios');
                $this->redirect('/notificaciones/create');
                return;
            }

            // Crear noticia general
            $noticiaData = [
                'titulo' => $data['titulo'],
                'contenido' => $data['contenido'],
                'tipo' => 'general',
                'admin_id' => $this->getSession('admin')['id']
            ];

            $noticiaId = $this->noticiaModel->create($noticiaData);

            // Enviar a todos los usuarios
            $this->enviarNotificacionGeneral($data, $noticiaId);

            $this->setSession('success', 'Notificación general enviada exitosamente');
            $this->redirect('/notificaciones');
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al enviar notificación: ' . $e->getMessage());
            $this->redirect('/notificaciones/create');
        }
    }

    public function enviarPorCurso()
    {
        try {
            $data = $this->getPostData();
            
            if (empty($data['titulo']) || empty($data['contenido']) || empty($data['curso_id'])) {
                $this->setSession('error', 'Título, contenido y curso son obligatorios');
                $this->redirect('/notificaciones/create');
                return;
            }

            // Obtener alumnos del curso
            $alumnos = $this->alumnoModel->getByCurso($data['curso_id']);
            
            if (empty($alumnos)) {
                $this->setSession('error', 'No hay alumnos en este curso');
                $this->redirect('/notificaciones/create');
                return;
            }

            // Crear noticia
            $noticiaData = [
                'titulo' => $data['titulo'],
                'contenido' => $data['contenido'],
                'tipo' => 'curso',
                'admin_id' => $this->getSession('admin')['id']
            ];

            $noticiaId = $this->noticiaModel->create($noticiaData);

            // Asociar con curso
            $this->noticiaModel->asociarConCurso($noticiaId, $data['curso_id']);

            // Enviar notificación a familias de alumnos del curso
            $this->enviarNotificacionPorCurso($data, $alumnos, $noticiaId);

            $this->setSession('success', 'Notificación enviada al curso exitosamente');
            $this->redirect('/notificaciones');
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al enviar notificación: ' . $e->getMessage());
            $this->redirect('/notificaciones/create');
        }
    }

    public function enviarPorFamilia()
    {
        try {
            $data = $this->getPostData();
            
            if (empty($data['titulo']) || empty($data['contenido']) || empty($data['usuario_id'])) {
                $this->setSession('error', 'Título, contenido y familia son obligatorios');
                $this->redirect('/notificaciones/create');
                return;
            }

            // Crear noticia
            $noticiaData = [
                'titulo' => $data['titulo'],
                'contenido' => $data['contenido'],
                'tipo' => 'familia',
                'admin_id' => $this->getSession('admin')['id']
            ];

            $noticiaId = $this->noticiaModel->create($noticiaData);

            // Asociar con usuario
            $this->noticiaModel->asociarConUsuario($noticiaId, $data['usuario_id']);

            // Enviar notificación a la familia específica
            $this->enviarNotificacionPorFamilia($data, $data['usuario_id'], $noticiaId);

            $this->setSession('success', 'Notificación enviada a la familia exitosamente');
            $this->redirect('/notificaciones');
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al enviar notificación: ' . $e->getMessage());
            $this->redirect('/notificaciones/create');
        }
    }

    public function historial()
    {
        $page = (int)($this->getQueryParams()['page'] ?? 1);
        $limit = 20;
        
        $notificaciones = $this->noticiaModel->paginate($page, $limit);
        
        return $this->renderWithLayout('notificaciones/historial', [
            'notificaciones' => $notificaciones
        ]);
    }

    public function estadisticas()
    {
        $stats = [
            'total_notificaciones' => $this->noticiaModel->count(),
            'notificaciones_hoy' => $this->noticiaModel->countToday(),
            'notificaciones_semana' => $this->noticiaModel->countThisWeek(),
            'notificaciones_mes' => $this->noticiaModel->countThisMonth(),
            'por_tipo' => $this->noticiaModel->getStatsByType()
        ];
        
        return $this->renderWithLayout('notificaciones/estadisticas', [
            'stats' => $stats
        ]);
    }

    // ==================== MÉTODOS PRIVADOS ====================
    
    private function enviarNotificacionPush($data, $noticiaId)
    {
        $url = 'https://onesignal.com/api/v1/notifications';
        
        $fields = [
            'app_id' => $this->appId,
            'included_segments' => ['All'],
            'headings' => ['en' => $data['titulo']],
            'contents' => ['en' => $data['contenido']],
            'data' => [
                'noticia_id' => $noticiaId,
                'tipo' => $data['tipo'] ?? 'general'
            ]
        ];

        // Filtrar por usuarios específicos
        if (!empty($data['usuarios'])) {
            $fields['include_external_user_ids'] = $data['usuarios'];
        }

        // Filtrar por alumnos específicos
        if (!empty($data['alumnos'])) {
            $alumnos = $this->alumnoModel->getByIds($data['alumnos']);
            $usuarioIds = array_column($alumnos, 'usuario_id');
            $fields['include_external_user_ids'] = array_merge(
                $fields['include_external_user_ids'] ?? [], 
                $usuarioIds
            );
        }

        $this->enviarOneSignal($url, $fields);
    }

    private function enviarNotificacionGeneral($data, $noticiaId)
    {
        $url = 'https://onesignal.com/api/v1/notifications';
        
        $fields = [
            'app_id' => $this->appId,
            'included_segments' => ['All'],
            'headings' => ['en' => $data['titulo']],
            'contents' => ['en' => $data['contenido']],
            'data' => [
                'noticia_id' => $noticiaId,
                'tipo' => 'general'
            ]
        ];

        $this->enviarOneSignal($url, $fields);
    }

    private function enviarNotificacionPorCurso($data, $alumnos, $noticiaId)
    {
        if (empty($alumnos)) return;

        $url = 'https://onesignal.com/api/v1/notifications';
        
        // Obtener IDs de usuarios de los alumnos
        $usuarioIds = array_unique(array_column($alumnos, 'usuario_id'));
        
        $fields = [
            'app_id' => $this->appId,
            'include_external_user_ids' => $usuarioIds,
            'headings' => ['en' => $data['titulo']],
            'contents' => ['en' => $data['contenido']],
            'data' => [
                'noticia_id' => $noticiaId,
                'tipo' => 'curso',
                'curso_id' => $data['curso_id']
            ]
        ];

        $this->enviarOneSignal($url, $fields);
    }

    private function enviarNotificacionPorFamilia($data, $usuarioId, $noticiaId)
    {
        $url = 'https://onesignal.com/api/v1/notifications';
        
        $fields = [
            'app_id' => $this->appId,
            'include_external_user_ids' => [$usuarioId],
            'headings' => ['en' => $data['titulo']],
            'contents' => ['en' => $data['contenido']],
            'data' => [
                'noticia_id' => $noticiaId,
                'tipo' => 'familia',
                'usuario_id' => $usuarioId
            ]
        ];

        $this->enviarOneSignal($url, $fields);
    }

    private function enviarOneSignal($url, $fields)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json; charset=utf-8',
            'Authorization: Basic ' . $this->restApiKey
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            throw new \Exception('Error al enviar notificación: ' . $response);
        }

        // Registrar en logs
        $this->logNotificacion($fields, $response);
    }

    private function validarNotificacion($data)
    {
        $errores = [];

        if (empty($data['titulo'])) {
            $errores[] = "El título es obligatorio";
        }

        if (empty($data['contenido'])) {
            $errores[] = "El contenido es obligatorio";
        }

        if (empty($data['tipo'])) {
            $errores[] = "El tipo de notificación es obligatorio";
        }

        return $errores;
    }

    private function logNotificacion($fields, $response)
    {
        $logData = [
            'fecha' => date('Y-m-d H:i:s'),
            'tipo' => $fields['data']['tipo'] ?? 'general',
            'titulo' => $fields['headings']['en'],
            'contenido' => $fields['contents']['en'],
            'destinatarios' => count($fields['include_external_user_ids'] ?? []),
            'response' => $response
        ];

        $logFile = __DIR__ . '/../../logs/notificaciones.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        file_put_contents($logFile, json_encode($logData) . "\n", FILE_APPEND | LOCK_EX);
    }
} 