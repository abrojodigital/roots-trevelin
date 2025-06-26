<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controllers\AuthController;
use App\Controllers\DashboardController;
use App\Controllers\UsuarioController;
use App\Controllers\AlumnoController;
use App\Controllers\CursoController;
use App\Controllers\NoticiaController;
use App\Controllers\ApiController;
use App\Controllers\ReporteController;
use App\Controllers\NotificacionController;

// Configurar manejo de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Iniciar sesión
session_start();

// Crear instancia del router
$router = new Router();

// ==================== RUTAS DE AUTENTICACIÓN ====================
$router->get('/', [AuthController::class, 'welcome']);
$router->get('/login', [AuthController::class, 'showLogin']);
$router->post('/login', [AuthController::class, 'login']);
$router->get('/logout', [AuthController::class, 'logout']);

// ==================== RUTAS DEL DASHBOARD ====================
$router->get('/dashboard', [DashboardController::class, 'index']);

// ==================== RUTAS DE USUARIOS ====================
$router->get('/usuarios', [UsuarioController::class, 'index']);
$router->get('/usuarios/create', [UsuarioController::class, 'create']);
$router->post('/usuarios', [UsuarioController::class, 'store']);
$router->get('/usuarios/edit/{id}', [UsuarioController::class, 'edit']);
$router->post('/usuarios/{id}', [UsuarioController::class, 'update']);
$router->get('/usuarios/delete/{id}', [UsuarioController::class, 'delete']);
$router->get('/usuarios/view/{id}', [UsuarioController::class, 'view']);

// ==================== RUTAS DE ALUMNOS ====================
$router->get('/alumnos', [AlumnoController::class, 'index']);
$router->get('/alumnos/create', [AlumnoController::class, 'create']);
$router->post('/alumnos', [AlumnoController::class, 'store']);
$router->get('/alumnos/edit/{id}', [AlumnoController::class, 'edit']);
$router->post('/alumnos/{id}', [AlumnoController::class, 'update']);
$router->get('/alumnos/delete/{id}', [AlumnoController::class, 'delete']);
$router->get('/alumnos/view/{id}', [AlumnoController::class, 'view']);

// ==================== RUTAS DE CURSOS ====================
$router->get('/cursos', [CursoController::class, 'index']);
$router->get('/cursos/create', [CursoController::class, 'create']);
$router->post('/cursos', [CursoController::class, 'store']);
$router->get('/cursos/edit/{id}', [CursoController::class, 'edit']);
$router->post('/cursos/{id}', [CursoController::class, 'update']);
$router->get('/cursos/delete/{id}', [CursoController::class, 'delete']);
$router->get('/cursos/view/{id}', [CursoController::class, 'view']);

// ==================== RUTAS DE NOTICIAS ====================
$router->get('/noticias', [NoticiaController::class, 'index']);
$router->get('/noticias/create', [NoticiaController::class, 'create']);
$router->post('/noticias', [NoticiaController::class, 'store']);
$router->get('/noticias/edit/{id}', [NoticiaController::class, 'edit']);
$router->post('/noticias/{id}', [NoticiaController::class, 'update']);
$router->get('/noticias/delete/{id}', [NoticiaController::class, 'delete']);
$router->get('/noticias/view/{id}', [NoticiaController::class, 'view']);

// ==================== RUTAS DE API REST ====================
$router->get('/api/usuarios', [ApiController::class, 'getUsuarios']);
$router->get('/api/usuarios/{id}', [ApiController::class, 'getUsuario']);
$router->post('/api/usuarios', [ApiController::class, 'createUsuario']);
$router->post('/api/usuarios/{id}', [ApiController::class, 'updateUsuario']);
$router->get('/api/usuarios/delete/{id}', [ApiController::class, 'deleteUsuario']);

$router->get('/api/alumnos', [ApiController::class, 'getAlumnos']);
$router->get('/api/alumnos/{id}', [ApiController::class, 'getAlumno']);

$router->get('/api/cursos', [ApiController::class, 'getCursos']);
$router->get('/api/cursos/{id}', [ApiController::class, 'getCurso']);

$router->get('/api/noticias', [ApiController::class, 'getNoticias']);
$router->get('/api/noticias/{id}', [ApiController::class, 'getNoticia']);

$router->get('/api/dashboard', [ApiController::class, 'getDashboard']);

// ==================== RUTAS DE REPORTES ====================
$router->get('/reportes', [ReporteController::class, 'index']);
$router->get('/reportes/usuarios/{formato?}', [ReporteController::class, 'usuarios']);
$router->get('/reportes/alumnos/{formato?}', [ReporteController::class, 'alumnos']);
$router->get('/reportes/cursos/{formato?}', [ReporteController::class, 'cursos']);
$router->get('/reportes/noticias/{formato?}', [ReporteController::class, 'noticias']);
$router->get('/reportes/alumno/{id}/{formato?}', [ReporteController::class, 'alumnoDetalle']);

// ==================== RUTAS DE NOTIFICACIONES ====================
$router->get('/notificaciones', [NotificacionController::class, 'index']);
$router->get('/notificaciones/create', [NotificacionController::class, 'create']);
$router->post('/notificaciones', [NotificacionController::class, 'store']);
$router->post('/notificaciones/general', [NotificacionController::class, 'enviarGeneral']);
$router->post('/notificaciones/curso', [NotificacionController::class, 'enviarPorCurso']);
$router->post('/notificaciones/familia', [NotificacionController::class, 'enviarPorFamilia']);
$router->get('/notificaciones/historial', [NotificacionController::class, 'historial']);
$router->get('/notificaciones/estadisticas', [NotificacionController::class, 'estadisticas']);

// ==================== RUTAS DE MANTENIMIENTO ====================
$router->get('/logs', function() {
    // Vista de logs del sistema
    echo "Sistema de Logs";
});

$router->get('/cache/clear', function() {
    // Limpiar caché
    $cache = \App\Core\Cache::getInstance();
    $cache->clear();
    header('Location: /dashboard');
});

// ==================== MANEJADOR DE ERRORES ====================
$router->setNotFoundHandler(function() {
    http_response_code(404);
    echo '<h1>404 - Página no encontrada</h1>';
    echo '<p>La página que buscas no existe.</p>';
    echo '<a href="/dashboard">Volver al Dashboard</a>';
});

// Ejecutar el router
$router->dispatch(); 