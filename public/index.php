<?php

// Iniciar sesión
session_start();

// Configurar autoloader
spl_autoload_register(function ($class) {
    // Convertir namespace a ruta de archivo
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/../app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Configuración de la aplicación
define('BASE_URL', '');
define('APP_ROOT', __DIR__ . '/../');

// Crear router
$router = new App\Core\Router();

// Rutas de autenticación
$router->get('/', 'AuthController@index');
$router->get('/login', 'AuthController@login');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');

// Rutas del dashboard
$router->get('/admin/dashboard', 'AdminController@dashboard');

// Rutas de usuarios
$router->get('/usuarios', 'UsuarioController@index');
$router->get('/usuarios/create', 'UsuarioController@create');
$router->post('/usuarios', 'UsuarioController@store');
$router->get('/usuarios/edit/{id}', 'UsuarioController@edit');
$router->post('/usuarios/{id}', 'UsuarioController@update');
$router->get('/usuarios/delete/{id}', 'UsuarioController@delete');

// Rutas de alumnos
$router->get('/alumnos', 'AlumnoController@index');
$router->get('/alumnos/create', 'AlumnoController@create');
$router->post('/alumnos', 'AlumnoController@store');
$router->get('/alumnos/edit/{id}', 'AlumnoController@edit');
$router->post('/alumnos/{id}', 'AlumnoController@update');
$router->get('/alumnos/delete/{id}', 'AlumnoController@delete');

// Rutas de cursos
$router->get('/cursos', 'CursoController@index');
$router->get('/cursos/create', 'CursoController@create');
$router->post('/cursos', 'CursoController@store');
$router->get('/cursos/edit/{id}', 'CursoController@edit');
$router->post('/cursos/{id}', 'CursoController@update');
$router->get('/cursos/delete/{id}', 'CursoController@delete');

// Rutas de noticias
$router->get('/noticias', 'NoticiaController@index');
$router->get('/noticias/create', 'NoticiaController@create');
$router->post('/noticias', 'NoticiaController@store');
$router->get('/noticias/edit/{id}', 'NoticiaController@edit');
$router->post('/noticias/{id}', 'NoticiaController@update');
$router->get('/noticias/delete/{id}', 'NoticiaController@delete');

// Ruta 404
$router->notFound(function() {
    http_response_code(404);
    echo '<h1>404 - Página no encontrada</h1>';
    echo '<p>La página que buscas no existe.</p>';
    echo '<a href="/">Volver al inicio</a>';
});

// Resolver la ruta
$router->resolve(); 