<?php

namespace Tests\Controllers;

use Tests\TestCase;
use App\Controllers\AuthController;
use App\Models\Administrador;

class AuthControllerTest extends TestCase
{
    private $authController;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authController = new AuthController();
        $this->createTestData();
    }

    public function testLoginWithValidCredentials()
    {
        // Simular datos de login válidos
        $_POST = [
            'email' => 'admin@test.com',
            'password' => 'password123'
        ];
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Capturar la redirección
        $this->expectOutputRegex('/Location: \/admin\/dashboard/');
        
        $this->authController->login();
    }

    public function testLoginWithInvalidCredentials()
    {
        // Simular datos de login inválidos
        $_POST = [
            'email' => 'admin@test.com',
            'password' => 'wrongpassword'
        ];
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Capturar la redirección
        $this->expectOutputRegex('/Location: \/login/');
        
        $this->authController->login();
    }

    public function testLoginWithEmptyCredentials()
    {
        // Simular datos de login vacíos
        $_POST = [
            'email' => '',
            'password' => ''
        ];
        $_SERVER['REQUEST_METHOD'] = 'POST';

        // Capturar la redirección
        $this->expectOutputRegex('/Location: \/login/');
        
        $this->authController->login();
    }

    public function testLoginFormDisplay()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        
        // Simular sesión con error
        $_SESSION['error'] = 'Credenciales inválidas';
        
        $response = $this->authController->login();
        
        $this->assertStringContainsString('Iniciar Sesión', $response);
        $this->assertStringContainsString('Credenciales inválidas', $response);
        $this->assertStringContainsString('form', $response);
        $this->assertStringContainsString('email', $response);
        $this->assertStringContainsString('password', $response);
    }

    public function testLogout()
    {
        // Simular sesión activa
        $_SESSION['admin'] = ['id' => 1, 'nombre' => 'Admin Test'];
        
        // Capturar la redirección
        $this->expectOutputRegex('/Location: \//');
        
        $this->authController->logout();
        
        // Verificar que la sesión se destruyó
        $this->assertArrayNotHasKey('admin', $_SESSION);
    }

    public function testIndexWithActiveSession()
    {
        // Simular sesión activa
        $_SESSION['admin'] = ['id' => 1, 'nombre' => 'Admin Test'];
        
        // Capturar la redirección
        $this->expectOutputRegex('/Location: \/admin\/dashboard/');
        
        $this->authController->index();
    }

    public function testIndexWithoutSession()
    {
        // Asegurar que no hay sesión
        unset($_SESSION['admin']);
        
        $response = $this->authController->index();
        
        $this->assertStringContainsString('Instituto de Inglés Roots', $response);
        $this->assertStringContainsString('Iniciar sesión', $response);
    }

    public function testAuthenticationFlow()
    {
        // Test 1: Acceso sin autenticación
        unset($_SESSION['admin']);
        
        $response = $this->authController->index();
        $this->assertStringContainsString('Iniciar sesión', $response);

        // Test 2: Login exitoso
        $_POST = [
            'email' => 'admin@test.com',
            'password' => 'password123'
        ];
        $_SERVER['REQUEST_METHOD'] = 'POST';
        
        $this->expectOutputRegex('/Location: \/admin\/dashboard/');
        $this->authController->login();

        // Test 3: Verificar que la sesión se estableció
        $this->assertArrayHasKey('admin', $_SESSION);
        $this->assertEquals('admin@test.com', $_SESSION['admin']['email']);

        // Test 4: Logout
        $this->expectOutputRegex('/Location: \//');
        $this->authController->logout();

        // Test 5: Verificar que la sesión se destruyó
        $this->assertArrayNotHasKey('admin', $_SESSION);
    }

    public function testSessionManagement()
    {
        // Test setSession
        $this->authController->setSession('test_key', 'test_value');
        $this->assertEquals('test_value', $_SESSION['test_key']);

        // Test getSession
        $value = $this->authController->getSession('test_key');
        $this->assertEquals('test_value', $value);

        // Test getSession with default
        $defaultValue = $this->authController->getSession('non_existent_key', 'default');
        $this->assertEquals('default', $defaultValue);

        // Test unsetSession
        $this->authController->unsetSession('test_key');
        $this->assertArrayNotHasKey('test_key', $_SESSION);
    }

    public function testRequestMethodDetection()
    {
        // Test isPost
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertTrue($this->authController->isPost());

        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertFalse($this->authController->isPost());

        // Test isGet
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $this->assertTrue($this->authController->isGet());

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $this->assertFalse($this->authController->isGet());
    }

    public function testDataRetrieval()
    {
        // Test getPostData
        $_POST = ['test' => 'value', 'another' => 'data'];
        $postData = $this->authController->getPostData();
        $this->assertEquals(['test' => 'value', 'another' => 'data'], $postData);

        // Test getQueryParams
        $_GET = ['param1' => 'value1', 'param2' => 'value2'];
        $queryParams = $this->authController->getQueryParams();
        $this->assertEquals(['param1' => 'value1', 'param2' => 'value2'], $queryParams);
    }
} 