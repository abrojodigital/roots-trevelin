<?php

namespace Tests\Models;

use Tests\TestCase;
use App\Models\Usuario;

class UsuarioTest extends TestCase
{
    private $usuarioModel;

    protected function setUp(): void
    {
        parent::setUp();
        $this->usuarioModel = new Usuario();
        $this->createTestData();
    }

    public function testFindAll()
    {
        $usuarios = $this->usuarioModel->findAll();
        
        $this->assertIsArray($usuarios);
        $this->assertGreaterThan(0, count($usuarios));
        $this->assertArrayHasKey('nombre', $usuarios[0]);
        $this->assertArrayHasKey('email', $usuarios[0]);
    }

    public function testFindById()
    {
        $usuario = $this->usuarioModel->findById(1);
        
        $this->assertIsArray($usuario);
        $this->assertEquals('Juan', $usuario['nombre']);
        $this->assertEquals('Pérez', $usuario['apellido']);
        $this->assertEquals('juan@test.com', $usuario['email']);
    }

    public function testFindByEmail()
    {
        $usuario = $this->usuarioModel->findByEmail('juan@test.com');
        
        $this->assertIsArray($usuario);
        $this->assertEquals('Juan', $usuario['nombre']);
        $this->assertEquals('juan@test.com', $usuario['email']);
    }

    public function testCreateWithPassword()
    {
        $data = [
            'nombre' => 'Ana',
            'apellido' => 'García',
            'email' => 'ana@test.com',
            'telefono' => '987654321',
            'direccion' => 'Calle Nueva 456',
            'contrasena' => 'password123'
        ];

        $id = $this->usuarioModel->createWithPassword($data);
        
        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);

        $usuario = $this->usuarioModel->findById($id);
        $this->assertEquals('Ana', $usuario['nombre']);
        $this->assertEquals('ana@test.com', $usuario['email']);
        $this->assertTrue(password_verify('password123', $usuario['contrasena']));
    }

    public function testUpdateWithPassword()
    {
        $data = [
            'nombre' => 'Juan Actualizado',
            'apellido' => 'Pérez',
            'email' => 'juan.actualizado@test.com',
            'contrasena' => 'newpassword123'
        ];

        $result = $this->usuarioModel->updateWithPassword(1, $data);
        
        $this->assertTrue($result);

        $usuario = $this->usuarioModel->findById(1);
        $this->assertEquals('Juan Actualizado', $usuario['nombre']);
        $this->assertEquals('juan.actualizado@test.com', $usuario['email']);
        $this->assertTrue(password_verify('newpassword123', $usuario['contrasena']));
    }

    public function testUpdateWithoutPassword()
    {
        $data = [
            'nombre' => 'Juan Sin Password',
            'apellido' => 'Pérez',
            'email' => 'juan.sinpassword@test.com'
        ];

        $result = $this->usuarioModel->updateWithPassword(1, $data);
        
        $this->assertTrue($result);

        $usuario = $this->usuarioModel->findById(1);
        $this->assertEquals('Juan Sin Password', $usuario['nombre']);
        // La contraseña original debe mantenerse
        $this->assertTrue(password_verify('password123', $usuario['contrasena']));
    }

    public function testSearchByName()
    {
        $usuarios = $this->usuarioModel->searchByName('Juan');
        
        $this->assertIsArray($usuarios);
        $this->assertGreaterThan(0, count($usuarios));
        $this->assertEquals('Juan', $usuarios[0]['nombre']);
    }

    public function testSearchByApellido()
    {
        $usuarios = $this->usuarioModel->searchByName('Pérez');
        
        $this->assertIsArray($usuarios);
        $this->assertGreaterThan(0, count($usuarios));
        $this->assertEquals('Pérez', $usuarios[0]['apellido']);
    }

    public function testDelete()
    {
        $result = $this->usuarioModel->delete(1);
        
        $this->assertTrue($result);

        $usuario = $this->usuarioModel->findById(1);
        $this->assertFalse($usuario);
    }

    public function testCount()
    {
        $count = $this->usuarioModel->count();
        
        $this->assertIsInt($count);
        $this->assertGreaterThan(0, $count);
    }

    public function testPaginate()
    {
        $result = $this->usuarioModel->paginate(1, 5);
        
        $this->assertIsArray($result);
        $this->assertArrayHasKey('data', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertArrayHasKey('page', $result);
        $this->assertArrayHasKey('perPage', $result);
        $this->assertArrayHasKey('totalPages', $result);
        
        $this->assertIsArray($result['data']);
        $this->assertLessThanOrEqual(5, count($result['data']));
        $this->assertEquals(1, $result['page']);
        $this->assertEquals(5, $result['perPage']);
    }

    public function testUpdateOneSignalPlayerId()
    {
        $playerId = 'test-player-id-123';
        $result = $this->usuarioModel->updateOneSignalPlayerId(1, $playerId);
        
        $this->assertTrue($result);

        $usuario = $this->usuarioModel->findById(1);
        $this->assertEquals($playerId, $usuario['onesignal_player_id']);
    }

    public function testUnlinkOneSignalPlayerId()
    {
        // Primero asignar un player ID
        $this->usuarioModel->updateOneSignalPlayerId(1, 'test-player-id-123');
        
        // Luego desvincularlo
        $result = $this->usuarioModel->unlinkOneSignalPlayerId('test-player-id-123');
        
        $this->assertTrue($result);

        $usuario = $this->usuarioModel->findById(1);
        $this->assertNull($usuario['onesignal_player_id']);
    }

    public function testGetWithAlumnos()
    {
        $usuario = $this->usuarioModel->getWithAlumnos(1);
        
        $this->assertIsArray($usuario);
        $this->assertEquals('Juan', $usuario['nombre']);
        $this->assertStringContainsString('María', $usuario['alumnos']);
    }
} 