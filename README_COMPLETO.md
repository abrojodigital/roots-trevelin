# Instituto de Inglés Roots - Sistema de Administración

## Descripción
Sistema de administración completo para instituto de inglés desarrollado en PHP con arquitectura MVC.

## Características Principales

### 🎯 Funcionalidades Core
- **Gestión de Usuarios**: Administración completa de familias/usuarios
- **Gestión de Alumnos**: Registro y seguimiento de estudiantes
- **Gestión de Cursos**: Administración de cursos y niveles
- **Sistema de Noticias**: Publicación de noticias por audiencia
- **Notificaciones Push**: Integración con OneSignal
- **Reportes**: Generación de reportes en PDF y Excel
- **API REST**: Endpoints para aplicación móvil

### 🏗️ Arquitectura
- **MVC Pattern**: Separación clara de responsabilidades
- **Router Personalizado**: Sistema de rutas flexible
- **ORM Simple**: Capa de abstracción de base de datos
- **Sistema de Caché**: Optimización de rendimiento
- **Logging**: Sistema de logs estructurado

## Estructura del Proyecto

```
roots-trevelin/
├── app/                          # Código principal de la aplicación
│   ├── controllers/              # Controladores MVC
│   ├── core/                     # Componentes core del framework
│   ├── models/                   # Modelos de datos
│   └── views/                    # Vistas y templates
├── config/                       # Archivos de configuración
│   ├── app.php                   # Configuración principal
│   ├── database.php              # Configuración de BD
│   ├── env.php                   # Variables de entorno
│   └── onesignal.php             # Configuración OneSignal
├── public/                       # Punto de entrada público
│   ├── index.php                 # Router principal
│   ├── css/                      # Estilos CSS
│   ├── js/                       # JavaScript
│   └── img/                      # Imágenes públicas
├── storage/                      # Almacenamiento de archivos
├── tests/                        # Tests unitarios
├── uploads/                      # Archivos subidos
├── vendor/                       # Dependencias Composer
└── composer.json                 # Configuración de dependencias
```

## Instalación

### Requisitos
- PHP >= 7.4
- MySQL >= 5.7
- Composer
- Extensiones PHP: PDO, JSON

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone [url-del-repositorio]
   cd roots-trevelin
   ```

2. **Instalar dependencias**
   ```bash
   composer install
   ```

3. **Configurar base de datos**
   - Crear base de datos MySQL
   - Importar `db/database.sql`
   - Configurar credenciales en `config/env.php`

4. **Configurar OneSignal** (opcional)
   - Editar `config/onesignal.php` con tus credenciales

5. **Configurar servidor web**
   - Apuntar document root a `public/`
   - Asegurar permisos de escritura en `storage/` y `uploads/`

## Configuración

### Variables de Entorno (`config/env.php`)
```php
// Base de datos
define('DB_HOST', 'localhost');
define('DB_DATABASE', 'tu_base_de_datos');
define('DB_USERNAME', 'tu_usuario');
define('DB_PASSWORD', 'tu_password');

// OneSignal (opcional)
define('ONESIGNAL_APP_ID', 'tu_app_id');
define('ONESIGNAL_REST_API_KEY', 'tu_api_key');
```

### Base de Datos
El sistema incluye las siguientes tablas principales:
- `usuarios` - Familias/padres
- `alumnos` - Estudiantes
- `cursos` - Cursos disponibles
- `cursos_alumnos` - Relación muchos a muchos
- `noticias` - Sistema de noticias
- `administradores` - Usuarios del sistema

## Uso

### Acceso al Sistema
- URL: `http://tu-dominio/`
- Credenciales: Configurar en tabla `administradores`

### Funcionalidades Principales

#### Dashboard
- Vista general con estadísticas
- Acceso rápido a todas las secciones

#### Gestión de Usuarios
- CRUD completo de familias
- Búsqueda y filtros
- Exportación de datos

#### Gestión de Alumnos
- Registro con fotos
- Asignación a cursos
- Historial académico

#### Sistema de Noticias
- Publicación por audiencia (general, familia, curso, alumno)
- Notificaciones push automáticas
- Historial de envíos

#### API REST
Endpoints disponibles:
- `GET /api/usuarios` - Listar usuarios
- `GET /api/alumnos` - Listar alumnos
- `GET /api/cursos` - Listar cursos
- `GET /api/noticias` - Listar noticias

## Desarrollo

### Estructura MVC

#### Controladores
```php
namespace App\Controllers;

class MiController extends Controller
{
    public function index()
    {
        // Lógica del controlador
        return $this->renderWithLayout('vista', $datos);
    }
}
```

#### Modelos
```php
namespace App\Models;

class MiModelo extends Model
{
    protected $table = 'mi_tabla';
    
    // Métodos personalizados
}
```

#### Vistas
```php
<!-- app/views/mi_vista.php -->
<div class="container">
    <h1><?= $titulo ?></h1>
    <!-- Contenido de la vista -->
</div>
```

### Testing
```bash
# Ejecutar todos los tests
composer test

# Tests específicos
composer test-unit
composer test-controllers
composer test-models
```

## Mantenimiento

### Limpieza de Caché
```bash
# Limpiar caché
composer cache-clear

# Limpiar logs
composer logs-clear
```

### Logs
Los logs se almacenan en:
- `storage/framework/logs/` - Logs de aplicación
- `logs/` - Logs del sistema

## Seguridad

### Características Implementadas
- Autenticación segura con hash de contraseñas
- Protección CSRF
- Validación de entrada
- Sanitización de datos
- Control de acceso por roles

### Recomendaciones
- Usar HTTPS en producción
- Configurar firewall
- Mantener dependencias actualizadas
- Realizar backups regulares

## Soporte

### Documentación
- `INSTALACION.md` - Guía de instalación detallada
- `tests/README.md` - Documentación de testing

### Contacto
- Email: info@roots-trevelin.com
- Sitio web: [www.roots-trevelin.com]

## Licencia
MIT License - Ver archivo LICENSE para más detalles.

---

**Versión**: 2.0.0  
**Última actualización**: 2024  
**Desarrollado por**: Roots Trevelin 