# 🧪 Tests Unitarios - Instituto de Inglés

Este directorio contiene los tests unitarios y de integración para el sistema del Instituto de Inglés.

## 📁 Estructura de Tests

```
tests/
├── TestCase.php              # Clase base para todos los tests
├── README.md                 # Esta documentación
├── Unit/                     # Tests unitarios
├── Feature/                  # Tests de características
├── Models/                   # Tests de modelos
│   ├── UsuarioTest.php
│   ├── AlumnoTest.php
│   ├── CursoTest.php
│   ├── NoticiaTest.php
│   └── AdministradorTest.php
└── Controllers/              # Tests de controladores
    ├── AuthControllerTest.php
    ├── AdminControllerTest.php
    ├── UsuarioControllerTest.php
    ├── AlumnoControllerTest.php
    ├── CursoControllerTest.php
    └── NoticiaControllerTest.php
```

## 🚀 Ejecutar Tests

### **Instalar dependencias:**
```bash
composer install
```

### **Ejecutar todos los tests:**
```bash
composer test
# o
./vendor/bin/phpunit
```

### **Ejecutar tests específicos:**
```bash
# Tests unitarios
composer test-unit

# Tests de características
composer test-feature

# Tests de modelos
composer test-models

# Tests de controladores
composer test-controllers
```

### **Generar reporte de cobertura:**
```bash
composer test-coverage
```

## 📋 Tipos de Tests

### **1. Tests Unitarios (`Unit/`)**
- Tests de funciones individuales
- Tests de métodos de clases
- Tests de lógica de negocio aislada

### **2. Tests de Características (`Feature/`)**
- Tests de flujos completos
- Tests de integración entre componentes
- Tests de endpoints de API

### **3. Tests de Modelos (`Models/`)**
- Tests de CRUD operations
- Tests de relaciones entre modelos
- Tests de validaciones de datos

### **4. Tests de Controladores (`Controllers/`)**
- Tests de autenticación
- Tests de autorización
- Tests de respuestas HTTP
- Tests de redirecciones

## 🛠️ Configuración

### **Base de Datos de Prueba**
Los tests usan una base de datos separada para pruebas:
- Base de datos: `test_database`
- Transacciones automáticas (rollback después de cada test)
- Datos de prueba creados automáticamente

### **Variables de Entorno**
```env
APP_ENV=testing
DB_DATABASE=test_database
```

## 📝 Escribir Tests

### **Estructura de un Test:**
```php
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
    }

    public function testCreateWithPassword()
    {
        $data = [
            'nombre' => 'Test',
            'email' => 'test@example.com',
            'contrasena' => 'password123'
        ];

        $id = $this->usuarioModel->createWithPassword($data);
        
        $this->assertIsInt($id);
        $this->assertGreaterThan(0, $id);
    }
}
```

### **Métodos de Assertion Comunes:**
- `assertIsArray()` - Verificar que es un array
- `assertIsString()` - Verificar que es un string
- `assertIsInt()` - Verificar que es un entero
- `assertTrue()` / `assertFalse()` - Verificar booleanos
- `assertEquals()` - Verificar igualdad
- `assertGreaterThan()` - Verificar que es mayor que
- `assertStringContainsString()` - Verificar contenido de string
- `assertArrayHasKey()` - Verificar que array tiene clave

### **Métodos Helper:**
- `createTestData()` - Crear datos de prueba
- `cleanTestData()` - Limpiar datos de prueba
- `post($url, $data)` - Simular petición POST
- `get($url)` - Simular petición GET

## 🔍 Cobertura de Tests

### **Modelos (100% objetivo):**
- ✅ Usuario - CRUD, validaciones, relaciones
- ✅ Alumno - CRUD, asignaciones de cursos
- ✅ Curso - CRUD, asignaciones de alumnos
- ✅ Noticia - CRUD, asignaciones múltiples
- ✅ Administrador - Autenticación

### **Controladores (90% objetivo):**
- ✅ AuthController - Login, logout, sesiones
- ✅ AdminController - Dashboard
- ✅ UsuarioController - CRUD completo
- ✅ AlumnoController - CRUD con relaciones
- ✅ CursoController - CRUD con relaciones
- ✅ NoticiaController - CRUD complejo

### **Core Framework (95% objetivo):**
- ✅ Database - Singleton, conexión
- ✅ Model - Métodos base CRUD
- ✅ Controller - Métodos helper
- ✅ View - Renderizado
- ✅ Router - Enrutamiento

## 📊 Métricas de Calidad

### **Cobertura de Código:**
```bash
# Generar reporte HTML
composer test-coverage

# Ver en navegador
open coverage/index.html
```

### **Estándares de Calidad:**
- Mínimo 80% de cobertura de código
- Todos los métodos públicos deben tener tests
- Tests deben ser independientes
- Tests deben ser rápidos (< 1 segundo cada uno)

## 🐛 Debugging Tests

### **Ver output detallado:**
```bash
./vendor/bin/phpunit --verbose
```

### **Ejecutar test específico:**
```bash
./vendor/bin/phpunit --filter testFindAll
```

### **Ejecutar con debug:**
```bash
./vendor/bin/phpunit --debug
```

## 🔄 CI/CD Integration

### **GitHub Actions (ejemplo):**
```yaml
name: Tests
on: [push, pull_request]
jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - uses: shivammathur/setup-php@v2
        with:
          php-version: '8.0'
      - run: composer install
      - run: composer test
```

## 📚 Recursos Adicionales

- [PHPUnit Documentation](https://phpunit.de/documentation.html)
- [PHP Testing Best Practices](https://phpunit.de/best-practices.html)
- [Test-Driven Development](https://en.wikipedia.org/wiki/Test-driven_development)

---

**Nota**: Los tests son esenciales para mantener la calidad del código y asegurar que las nuevas funcionalidades no rompan el código existente. 