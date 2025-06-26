# 🚀 Guía de Instalación - Sistema MVC

## 📋 Requisitos Previos

### Software Necesario
- **XAMPP** (Apache + MySQL + PHP 8.2+)
- **Composer** (Gestor de dependencias PHP)
- **Git** (Control de versiones)

### Extensiones PHP Requeridas
- PDO
- PDO_MySQL
- JSON
- cURL
- ZIP (para Composer)
- GD (para reportes PDF)

## 🔧 Pasos de Instalación

### 1. Clonar el Repositorio
```bash
git clone https://github.com/roots-trevelin/instituto-ingles.git
cd instituto-ingles
```

### 2. Instalar Dependencias
```bash
# Si Composer no está en PATH, usar:
C:\xampp\php\php.exe composer.phar install --ignore-platform-reqs
```

### 3. Configurar Base de Datos

#### Opción A: Usar Base de Datos Existente
La configuración ya está lista en `config/db.php`:
- Host: localhost
- Base de datos: u102612746_roots
- Usuario: u102612746_admin
- Contraseña: Trevelin#9203

#### Opción B: Crear Nueva Base de Datos
1. Abrir phpMyAdmin
2. Crear nueva base de datos
3. Editar `config/db.php` con tus credenciales

### 4. Configurar OneSignal (Opcional)

#### Crear Cuenta OneSignal
1. Ir a [OneSignal.com](https://onesignal.com)
2. Crear cuenta gratuita
3. Crear nueva aplicación
4. Obtener credenciales

#### Configurar Credenciales
Editar `config/onesignal.php`:
```php
'app_id' => 'TU_APP_ID_REAL',
'rest_api_key' => 'TU_REST_API_KEY_REAL',
'user_auth_key' => 'TU_USER_AUTH_KEY_REAL',
```

### 5. Crear Directorios Necesarios
```bash
# Los directorios se crean automáticamente, pero puedes verificarlos:
mkdir -p cache logs storage/framework/sessions storage/framework/cache/data storage/app/public uploads
```

### 6. Configurar Permisos (Linux/Mac)
```bash
chmod 755 cache logs storage uploads
chmod 644 config/*.php
```

### 7. Configurar Servidor Web

#### Apache (.htaccess ya incluido)
El archivo `.htaccess` en `public/` ya está configurado.

#### Nginx
```nginx
server {
    listen 80;
    server_name localhost;
    root /path/to/instituto-ingles/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🎯 Verificación de Instalación

### 1. Acceder al Sistema
- URL: `http://localhost/instituto-ingles/public/`
- Usuario: `admin@instituto.com`
- Contraseña: `admin123`

### 2. Verificar Funcionalidades
- ✅ Dashboard principal
- ✅ Gestión de usuarios
- ✅ Gestión de alumnos
- ✅ Gestión de cursos
- ✅ Sistema de notificaciones
- ✅ Generación de reportes
- ✅ API REST

### 3. Ejecutar Tests
```bash
C:\xampp\php\php.exe composer.phar test
```

## 🔧 Configuración Avanzada

### Variables de Entorno
Editar `config/env.php` para personalizar:
- Configuración de email
- Configuración de Redis
- Configuración de logs
- Configuración de seguridad

### Configuración de Caché
El sistema incluye caché automático. Para limpiar:
```bash
C:\xampp\php\php.exe composer.phar cache-clear
```

### Configuración de Logs
Los logs se guardan en `logs/`:
- `auth.log` - Autenticación
- `crud.log` - Operaciones CRUD
- `errors.log` - Errores
- `notifications.log` - Notificaciones
- `api.log` - API REST

## 🚨 Solución de Problemas

### Error: "Composer no encontrado"
```bash
# Instalar Composer manualmente
C:\xampp\php\php.exe -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
C:\xampp\php\php.exe composer-setup.php
```

### Error: "Extension GD no encontrada"
```bash
# Instalar con --ignore-platform-reqs
C:\xampp\php\php.exe composer.phar install --ignore-platform-reqs
```

### Error: "Base de datos no encontrada"
1. Verificar que MySQL esté corriendo
2. Verificar credenciales en `config/db.php`
3. Crear base de datos si no existe

### Error: "Permisos denegados"
```bash
# En Windows, ejecutar como administrador
# En Linux/Mac:
chmod 755 cache logs storage uploads
```

### Error: "OneSignal no funciona"
1. Verificar credenciales en `config/onesignal.php`
2. Verificar conectividad a internet
3. Verificar que la aplicación OneSignal esté activa

## 📞 Soporte

### Contacto
- **Email**: soporte@instituto-ingles.com
- **Teléfono**: +54 11 1234-5678

### Documentación Adicional
- [Manual de Usuario](README_COMPLETO.md)
- [API Documentation](docs/api.md)
- [Troubleshooting](docs/troubleshooting.md)

## 🎉 ¡Instalación Completada!

Tu sistema está listo para usar. Accede a:
`http://localhost/instituto-ingles/public/`

### Próximos Pasos
1. Cambiar contraseña del administrador
2. Configurar datos del instituto
3. Crear usuarios y alumnos
4. Configurar cursos
5. Probar notificaciones push
6. Generar reportes de prueba

---

**¡Disfruta usando el Sistema de Administración del Instituto de Inglés Roots!** 🎓 