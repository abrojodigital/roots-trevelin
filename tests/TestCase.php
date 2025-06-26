<?php

namespace Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;
use App\Core\Database;

abstract class TestCase extends BaseTestCase
{
    protected $db;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Configurar base de datos de prueba
        $this->db = Database::getInstance()->getConnection();
        
        // Iniciar transacción para rollback después de cada test
        $this->db->beginTransaction();
    }

    protected function tearDown(): void
    {
        // Rollback de la transacción para limpiar datos de prueba
        $this->db->rollBack();
        
        parent::tearDown();
    }

    /**
     * Crear datos de prueba
     */
    protected function createTestData()
    {
        // Crear administrador de prueba
        $stmt = $this->db->prepare("
            INSERT INTO administradores (nombre, email, contrasena) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            'Admin Test',
            'admin@test.com',
            password_hash('password123', PASSWORD_DEFAULT)
        ]);

        // Crear usuario de prueba
        $stmt = $this->db->prepare("
            INSERT INTO usuarios (nombre, apellido, email, telefono, direccion, contrasena) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            'Juan',
            'Pérez',
            'juan@test.com',
            '123456789',
            'Calle Test 123',
            password_hash('password123', PASSWORD_DEFAULT)
        ]);

        // Crear alumno de prueba
        $stmt = $this->db->prepare("
            INSERT INTO alumnos (nombre, apellido, edad, usuario_id) 
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([
            'María',
            'Pérez',
            15,
            1
        ]);

        // Crear curso de prueba
        $stmt = $this->db->prepare("
            INSERT INTO cursos (nombre, nivel, descripcion) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            'Inglés Básico',
            'Principiante',
            'Curso básico de inglés'
        ]);

        // Crear noticia de prueba
        $stmt = $this->db->prepare("
            INSERT INTO noticias (titulo, contenido, fecha_creacion) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([
            'Noticia de Prueba',
            'Contenido de prueba',
            date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Limpiar datos de prueba
     */
    protected function cleanTestData()
    {
        $tables = ['noticias_cursos', 'noticias_alumnos', 'noticias_usuarios', 
                   'alumnos_cursos', 'noticias', 'cursos', 'alumnos', 'usuarios', 'administradores'];
        
        foreach ($tables as $table) {
            $this->db->exec("DELETE FROM $table");
        }
    }

    /**
     * Simular petición POST
     */
    protected function post($url, $data = [])
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = $data;
        $_SERVER['REQUEST_URI'] = $url;
        
        // Capturar output
        ob_start();
        include __DIR__ . '/../public/index.php';
        $output = ob_get_clean();
        
        return $output;
    }

    /**
     * Simular petición GET
     */
    protected function get($url)
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_GET = [];
        $_SERVER['REQUEST_URI'] = $url;
        
        // Capturar output
        ob_start();
        include __DIR__ . '/../public/index.php';
        $output = ob_get_clean();
        
        return $output;
    }

    /**
     * Assert que la respuesta contiene texto específico
     */
    protected function assertResponseContains($response, $text)
    {
        $this->assertStringContainsString($text, $response);
    }

    /**
     * Assert que la respuesta no contiene texto específico
     */
    protected function assertResponseNotContains($response, $text)
    {
        $this->assertStringNotContainsString($text, $response);
    }

    /**
     * Assert que la respuesta es JSON válido
     */
    protected function assertJsonResponse($response)
    {
        $this->assertIsString($response);
        $this->assertNotFalse(json_decode($response));
    }
} 