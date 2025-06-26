<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\ApiController;
use App\Models\Usuario;
use App\Models\Alumno;
use App\Models\Curso;
use App\Models\Noticia;

class ApiControllerTest extends TestCase
{
    private $apiController;
    private $usuarioModel;
    private $alumnoModel;
    private $cursoModel;
    private $noticiaModel;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->apiController = new ApiController();
        $this->usuarioModel = new Usuario();
        $this->alumnoModel = new Alumno();
        $this->cursoModel = new Curso();
        $this->noticiaModel = new Noticia();
    }

    public function testGetUsuariosReturnsValidResponse()
    {
        // Simular datos de prueba
        $usuarios = [
            [
                'id' => 1,
                'nombre' => 'Juan',
                'apellido' => 'Pérez',
                'email' => 'juan@example.com'
            ]
        ];

        // Mock del modelo
        $this->usuarioModel->shouldReceive('paginate')
            ->once()
            ->andReturn([
                'data' => $usuarios,
                'total' => 1,
                'page' => 1,
                'limit' => 10
            ]);

        // Ejecutar método
        $response = $this->apiController->getUsuarios();

        // Verificar respuesta
        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertArrayHasKey('pagination', $response);
    }

    public function testGetUsuarioReturnsValidResponse()
    {
        $usuario = [
            'id' => 1,
            'nombre' => 'Juan',
            'apellido' => 'Pérez',
            'email' => 'juan@example.com'
        ];

        $this->usuarioModel->shouldReceive('getWithAlumnos')
            ->once()
            ->with(1)
            ->andReturn($usuario);

        $response = $this->apiController->getUsuario(1);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
    }

    public function testGetUsuarioReturns404ForNonExistentUser()
    {
        $this->usuarioModel->shouldReceive('getWithAlumnos')
            ->once()
            ->with(999)
            ->andReturn(null);

        $response = $this->apiController->getUsuario(999);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('error', $response);
    }

    public function testCreateUsuarioWithValidData()
    {
        $data = [
            'nombre' => 'María',
            'apellido' => 'García',
            'email' => 'maria@example.com',
            'contrasena' => 'password123'
        ];

        $this->usuarioModel->shouldReceive('createWithPassword')
            ->once()
            ->with($data)
            ->andReturn(2);

        $response = $this->apiController->createUsuario();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
    }

    public function testCreateUsuarioWithInvalidData()
    {
        $data = [
            'nombre' => '',
            'apellido' => '',
            'email' => 'invalid-email'
        ];

        $response = $this->apiController->createUsuario();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('errors', $response);
    }

    public function testGetAlumnosReturnsValidResponse()
    {
        $alumnos = [
            [
                'id' => 1,
                'nombre' => 'Ana',
                'apellido' => 'López',
                'edad' => 10
            ]
        ];

        $this->alumnoModel->shouldReceive('paginate')
            ->once()
            ->andReturn([
                'data' => $alumnos,
                'total' => 1,
                'page' => 1,
                'limit' => 10
            ]);

        $response = $this->apiController->getAlumnos();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
    }

    public function testGetAlumnosByCurso()
    {
        $alumnos = [
            [
                'id' => 1,
                'nombre' => 'Ana',
                'apellido' => 'López',
                'edad' => 10
            ]
        ];

        $this->alumnoModel->shouldReceive('getByCurso')
            ->once()
            ->with(1)
            ->andReturn($alumnos);

        $response = $this->apiController->getAlumnos();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
    }

    public function testGetCursosReturnsValidResponse()
    {
        $cursos = [
            [
                'id' => 1,
                'nombre' => 'Inglés Básico',
                'nivel' => 'Principiante'
            ]
        ];

        $this->cursoModel->shouldReceive('paginate')
            ->once()
            ->andReturn([
                'data' => $cursos,
                'total' => 1,
                'page' => 1,
                'limit' => 10
            ]);

        $response = $this->apiController->getCursos();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
    }

    public function testGetNoticiasReturnsValidResponse()
    {
        $noticias = [
            [
                'id' => 1,
                'titulo' => 'Anuncio Importante',
                'contenido' => 'Contenido del anuncio'
            ]
        ];

        $this->noticiaModel->shouldReceive('paginate')
            ->once()
            ->andReturn([
                'data' => $noticias,
                'total' => 1,
                'page' => 1,
                'limit' => 10
            ]);

        $response = $this->apiController->getNoticias();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
    }

    public function testGetDashboardReturnsValidResponse()
    {
        $stats = [
            'usuarios' => 10,
            'alumnos' => 25,
            'cursos' => 5,
            'noticias' => 15
        ];

        $this->usuarioModel->shouldReceive('count')->andReturn(10);
        $this->alumnoModel->shouldReceive('count')->andReturn(25);
        $this->cursoModel->shouldReceive('count')->andReturn(5);
        $this->noticiaModel->shouldReceive('count')->andReturn(15);

        $response = $this->apiController->getDashboard();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
        $this->assertArrayHasKey('data', $response);
        $this->assertEquals($stats, $response['data']);
    }

    public function testApiHeadersAreSet()
    {
        // Verificar que los headers de API se establecen correctamente
        $this->apiController->getUsuarios();
        
        $headers = headers_list();
        $contentType = false;
        $cors = false;
        
        foreach ($headers as $header) {
            if (strpos($header, 'Content-Type: application/json') !== false) {
                $contentType = true;
            }
            if (strpos($header, 'Access-Control-Allow-Origin') !== false) {
                $cors = true;
            }
        }
        
        $this->assertTrue($contentType, 'Content-Type header should be set');
        $this->assertTrue($cors, 'CORS headers should be set');
    }

    public function testErrorHandling()
    {
        $this->usuarioModel->shouldReceive('paginate')
            ->once()
            ->andThrow(new \Exception('Database error'));

        $response = $this->apiController->getUsuarios();

        $this->assertIsArray($response);
        $this->assertArrayHasKey('success', $response);
        $this->assertFalse($response['success']);
        $this->assertArrayHasKey('error', $response);
        $this->assertStringContainsString('Database error', $response['error']);
    }
} 