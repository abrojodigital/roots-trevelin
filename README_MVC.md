# 🏗️ Refactorización MVC - Instituto de Inglés

Este documento describe la nueva arquitectura MVC implementada para el proyecto del Instituto de Inglés.

## 📁 Nueva Estructura del Proyecto

```
roots-trevelin/
├── app/                          # Código de la aplicación
│   ├── controllers/              # Controladores
│   │   ├── AdminController.php
│   │   ├── AuthController.php
│   │   ├── UsuarioController.php
│   │   ├── AlumnoController.php
│   │   ├── CursoController.php
│   │   └── NoticiaController.php
│   ├── models/                   # Modelos
│   │   ├── Administrador.php
│   │   ├── Usuario.php
│   │   ├── Alumno.php
│   │   ├── Curso.php
│   │   └── Noticia.php
│   ├── views/                    # Vistas
│   │   ├── layouts/
│   │   │   └── default.php
│   │   ├── auth/
│   │   │   ├── welcome.php
│   │   │   └── login.php
│   │   ├── admin/
│   │   │   └── dashboard.php
│   │   ├── usuarios/
│   │   ├── alumnos/
│   │   ├── cursos/
│   │   ├── noticias/
│   │   └── partials/
│   └── core/                     # Núcleo del framework
│       ├── Controller.php
│       ├── Model.php
│       ├── View.php
│       ├── Router.php
│       └── Database.php
├── public/                       # Archivos públicos
│   ├── index.php                 # Punto de entrada
│   ├── .htaccess                 # Configuración Apache
│   ├── css/
│   │   └── style.css
│   ├── js/
│   │   └── app.js
│   └── img/
├── config/                       # Configuración (mantenido)
├── api/                          # API (mantenido)
├── notificaciones/               # Notificaciones (mantenido)
└── README_MVC.md                 # Esta documentación
```

## 🔧 Características de la Nueva Arquitectura

### **1. Separación de Responsabilidades**
- **Modelos**: Manejan la lógica de negocio y acceso a datos
- **Vistas**: Presentan la información al usuario
- **Controladores**: Coordinan entre modelos y vistas

### **2. Sistema de Rutas**
- Rutas RESTful con parámetros dinámicos
- Soporte para métodos GET, POST, PUT, DELETE
- Manejo automático de rutas 404

### **3. Base de Datos**
- Patrón Singleton para conexión PDO
- Modelos con métodos CRUD genéricos
- Consultas preparadas para seguridad

### **4. Sistema de Vistas**
- Layouts reutilizables
- Partials para componentes comunes
- Extracción automática de variables

### **5. Autenticación**
- Middleware de autenticación en controladores
- Manejo de sesiones centralizado
- Protección de rutas administrativas

## 🚀 Cómo Usar la Nueva Estructura

### **Acceso a la Aplicación**
```
http://localhost/roots-trevelin/public/
```

### **Rutas Principales**
- `/` - Página de bienvenida
- `/login` - Formulario de login
- `/admin/dashboard` - Panel de administración
- `/usuarios` - Gestión de usuarios
- `/alumnos` - Gestión de alumnos
- `/cursos` - Gestión de cursos
- `/noticias` - Gestión de noticias

### **Crear un Nuevo Controlador**
```php
<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\TuModelo;

class TuController extends Controller
{
    private $modelo;

    public function __construct()
    {
        parent::__construct();
        $this->modelo = new TuModelo();
    }

    public function index()
    {
        $datos = $this->modelo->findAll();
        return $this->renderWithLayout('tu-vista/index', ['datos' => $datos]);
    }
}
```

### **Crear un Nuevo Modelo**
```php
<?php
namespace App\Models;

use App\Core\Model;

class TuModelo extends Model
{
    protected $table = 'tu_tabla';

    public function metodoPersonalizado()
    {
        // Lógica específica del modelo
    }
}
```

### **Crear una Nueva Vista**
```php
<!-- app/views/tu-vista/index.php -->
<h1>Mi Vista</h1>
<?php foreach ($datos as $item): ?>
    <p><?= htmlspecialchars($item['nombre']) ?></p>
<?php endforeach; ?>
```

## 🔄 Migración desde la Estructura Anterior

### **Archivos Mantenidos**
- `config/` - Configuración de base de datos
- `api/` - Endpoints de API
- `notificaciones/` - Sistema de notificaciones
- `assets/` - Recursos estáticos

### **Archivos Migrados**
- Lógica de autenticación → `AuthController`
- CRUD de usuarios → `UsuarioController` + `Usuario` model
- CRUD de alumnos → `AlumnoController` + `Alumno` model
- CRUD de cursos → `CursoController` + `Curso` model
- CRUD de noticias → `NoticiaController` + `Noticia` model

### **Beneficios de la Migración**
1. **Código más limpio** - Separación clara de responsabilidades
2. **Reutilización** - Métodos comunes en clases base
3. **Mantenibilidad** - Cambios centralizados
4. **Escalabilidad** - Fácil agregar nuevas funcionalidades
5. **Seguridad** - Validación centralizada
6. **Testing** - Estructura más fácil de testear

## 🛠️ Configuración del Servidor

### **Apache (.htaccess)**
El archivo `.htaccess` en `public/` redirige todas las peticiones al `index.php`.

### **Configuración de Base de Datos**
La configuración se mantiene en `config/db.php` y se usa a través de la clase `Database`.

## 📝 Próximos Pasos

1. **Completar Vistas**: Crear todas las vistas faltantes para usuarios, alumnos, cursos y noticias
2. **Implementar Validación**: Agregar validación más robusta en los controladores
3. **Agregar Paginación**: Implementar paginación en los listados
4. **Mejorar UX**: Agregar confirmaciones JavaScript y feedback visual
5. **Testing**: Implementar tests unitarios
6. **Documentación**: Completar documentación de API

## 🔍 Estructura de Base de Datos

La estructura de base de datos se mantiene igual que en la versión anterior:

- `administradores` - Administradores del sistema
- `usuarios` - Familias/usuarios
- `alumnos` - Alumnos del instituto
- `cursos` - Cursos disponibles
- `noticias` - Noticias del instituto
- `alumnos_cursos` - Relación muchos a muchos
- `noticias_usuarios` - Noticias por usuario
- `noticias_alumnos` - Noticias por alumno
- `noticias_cursos` - Noticias por curso

## 🎯 Ventajas de la Nueva Arquitectura

1. **Organización**: Código bien estructurado y fácil de navegar
2. **Mantenimiento**: Cambios centralizados y fáciles de implementar
3. **Escalabilidad**: Fácil agregar nuevas funcionalidades
4. **Reutilización**: Métodos comunes en clases base
5. **Seguridad**: Validación y sanitización centralizada
6. **Performance**: Optimización de consultas y carga de recursos
7. **Testing**: Estructura más fácil de testear
8. **Documentación**: Código más autodocumentado

---

**Nota**: Esta refactorización mantiene toda la funcionalidad existente mientras mejora significativamente la estructura y mantenibilidad del código. 