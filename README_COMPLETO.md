# Sistema de Administración - Instituto de Inglés Roots

## 📋 Descripción

Sistema completo de administración para instituto de inglés desarrollado con arquitectura MVC en PHP. Incluye gestión de usuarios, alumnos, cursos, notificaciones push, reportes y API REST.

## 🚀 Características Principales

### ✅ Funcionalidades Implementadas

#### 🔐 Autenticación y Seguridad
- Sistema de login seguro con hash de contraseñas
- Control de sesiones y autorización
- Protección CSRF en formularios
- Validación de datos en servidor y cliente

#### 👥 Gestión de Usuarios
- CRUD completo de familias/usuarios
- Paginación y búsqueda avanzada
- Filtros por múltiples criterios
- Validación de datos en tiempo real

#### 👨‍🎓 Gestión de Alumnos
- Registro de alumnos con datos personales
- Asignación a familias
- Gestión de cursos por alumno
- Observaciones y notas especiales

#### 📚 Gestión de Cursos
- Creación y administración de cursos
- Diferentes niveles (Principiante, Intermedio, Avanzado)
- Asignación de alumnos a cursos
- Control de capacidad y horarios

#### 📢 Sistema de Notificaciones
- **Notificaciones Push con OneSignal**
- Envío por tipo: General, Por Curso, Por Familia, Personalizada
- Plantillas predefinidas
- Historial de notificaciones
- Estadísticas de envío

#### 📊 Reportes y Exportación
- **Generación de PDF con TCPDF**
- **Exportación a Excel con PhpSpreadsheet**
- Reportes de usuarios, alumnos, cursos y noticias
- Reportes personalizados con filtros
- Configuración de formatos

#### 🔌 API REST
- Endpoints completos para todas las entidades
- Autenticación por token
- Respuestas JSON estandarizadas
- Documentación de endpoints
- Rate limiting básico

#### 📝 Sistema de Logs
- **Logs de auditoría completos**
- Registro de acciones CRUD
- Logs de autenticación
- Logs de errores y performance
- Rotación automática de logs

#### ⚡ Optimización de Performance
- **Sistema de caché avanzado**
- Caché de consultas de base de datos
- Caché de vistas y configuraciones
- Limpieza automática de caché expirado
- Estadísticas de rendimiento

## 🏗️ Arquitectura del Sistema

### Estructura MVC
```
app/
├── controllers/     # Controladores de la aplicación
├── models/         # Modelos de datos
├── views/          # Vistas y templates
├── core/           # Núcleo del framework
└── config/         # Configuraciones
```

### Patrones de Diseño
- **Singleton** para conexión a base de datos
- **Factory** para creación de modelos
- **Observer** para sistema de logs
- **Strategy** para diferentes tipos de reportes

## 🛠️ Instalación y Configuración

### Requisitos del Sistema
- PHP >= 7.4
- MySQL >= 5.7
- Composer
- Extensión PDO
- Extensión JSON

### Instalación

1. **Clonar el repositorio**
```bash
git clone https://github.com/roots-trevelin/instituto-ingles.git
cd instituto-ingles
```

2. **Instalar dependencias**
```bash
composer install
```

3. **Configurar base de datos**
```bash
# Copiar archivo de configuración
cp config/db.example.php config/db.php

# Editar config/db.php con tus credenciales
```

4. **Configurar OneSignal**
```bash
# Editar config/onesignal.php
# Agregar tus credenciales de OneSignal
```

5. **Crear directorios necesarios**
```bash
mkdir -p cache logs uploads
chmod 755 cache logs uploads
```

6. **Configurar servidor web**
```apache
# .htaccess ya incluido para Apache
# Para Nginx, ver documentación específica
```

### Configuración de OneSignal

1. Crear cuenta en [OneSignal](https://onesignal.com)
2. Crear nueva aplicación
3. Obtener App ID y REST API Key
4. Configurar en `config/onesignal.php`

## 📚 Uso del Sistema

### 🔐 Acceso al Sistema
- URL: `http://localhost/instituto-ingles`
- Usuario por defecto: `admin@instituto.com`
- Contraseña: `admin123`

### 👥 Gestión de Usuarios
1. Ir a **Usuarios** en el menú
2. **Crear** nuevo usuario con datos completos
3. **Editar** información existente
4. **Eliminar** usuarios (con confirmación)

### 👨‍🎓 Gestión de Alumnos
1. Ir a **Alumnos** en el menú
2. **Crear** alumno asignando familia
3. **Asignar cursos** al alumno
4. **Ver detalles** completos del alumno

### 📚 Gestión de Cursos
1. Ir a **Cursos** en el menú
2. **Crear** curso con nivel y descripción
3. **Asignar alumnos** al curso
4. **Gestionar** capacidad y horarios

### 📢 Envío de Notificaciones

#### Notificación General
1. Ir a **Notificaciones**
2. Seleccionar **Enviar General**
3. Escribir título y contenido
4. Enviar a todos los usuarios

#### Notificación por Curso
1. Seleccionar **Enviar por Curso**
2. Elegir curso específico
3. Escribir mensaje
4. Enviar a familias de alumnos del curso

#### Notificación por Familia
1. Seleccionar **Enviar por Familia**
2. Elegir familia específica
3. Escribir mensaje personalizado
4. Enviar notificación

### 📊 Generación de Reportes

#### Reporte de Usuarios
1. Ir a **Reportes**
2. Seleccionar **Reporte de Familias**
3. Elegir formato (PDF/Excel)
4. Descargar reporte

#### Reporte de Alumnos
1. Seleccionar **Reporte de Alumnos**
2. Configurar filtros si es necesario
3. Generar en formato deseado
4. Descargar archivo

#### Reporte Personalizado
1. Ir a **Reporte Personalizado**
2. Configurar filtros avanzados
3. Seleccionar campos a incluir
4. Generar reporte

## 🔌 API REST

### Autenticación
```bash
# Obtener token
POST /api/auth/login
{
    "email": "admin@instituto.com",
    "password": "admin123"
}
```

### Endpoints Principales

#### Usuarios
```bash
GET    /api/usuarios          # Listar usuarios
GET    /api/usuarios/{id}     # Obtener usuario
POST   /api/usuarios          # Crear usuario
PUT    /api/usuarios/{id}     # Actualizar usuario
DELETE /api/usuarios/{id}     # Eliminar usuario
```

#### Alumnos
```bash
GET    /api/alumnos           # Listar alumnos
GET    /api/alumnos/{id}      # Obtener alumno
GET    /api/alumnos?curso_id=1 # Alumnos por curso
```

#### Cursos
```bash
GET    /api/cursos            # Listar cursos
GET    /api/cursos/{id}       # Obtener curso
GET    /api/cursos?nivel=Principiante # Cursos por nivel
```

#### Noticias
```bash
GET    /api/noticias          # Listar noticias
GET    /api/noticias/{id}     # Obtener noticia
GET    /api/noticias?tipo=general # Noticias por tipo
```

### Ejemplo de Uso
```bash
# Obtener lista de usuarios
curl -X GET "http://localhost/api/usuarios" \
     -H "Authorization: Bearer YOUR_TOKEN"

# Crear nuevo usuario
curl -X POST "http://localhost/api/usuarios" \
     -H "Content-Type: application/json" \
     -H "Authorization: Bearer YOUR_TOKEN" \
     -d '{
       "nombre": "Juan",
       "apellido": "Pérez",
       "email": "juan@example.com",
       "contrasena": "password123"
     }'
```

## 🧪 Testing

### Ejecutar Tests
```bash
# Todos los tests
composer test

# Tests específicos
composer test-unit
composer test-feature
composer test-models
composer test-controllers

# Con cobertura
composer test-coverage
```

### Estructura de Tests
```
tests/
├── Controllers/    # Tests de controladores
├── Models/         # Tests de modelos
├── Feature/        # Tests de integración
└── Unit/           # Tests unitarios
```

## 📝 Logs y Auditoría

### Tipos de Logs
- **auth.log**: Logs de autenticación
- **crud.log**: Logs de operaciones CRUD
- **errors.log**: Logs de errores
- **notifications.log**: Logs de notificaciones
- **api.log**: Logs de API
- **performance.log**: Logs de rendimiento

### Ver Logs
```bash
# Ver logs recientes
tail -f logs/auth.log

# Buscar en logs
grep "ERROR" logs/errors.log

# Limpiar logs antiguos
composer logs-clear
```

## ⚡ Optimización

### Caché
```bash
# Limpiar caché
composer cache-clear

# Ver estadísticas de caché
# Acceder a /cache/stats en el sistema
```

### Performance
- Caché de consultas frecuentes
- Optimización de consultas SQL
- Compresión de archivos estáticos
- Minificación de CSS/JS

## 🔧 Mantenimiento

### Tareas Programadas
```bash
# Limpiar caché expirado (diario)
0 2 * * * cd /path/to/project && composer cache-clear

# Limpiar logs antiguos (semanal)
0 3 * * 0 cd /path/to/project && composer logs-clear

# Backup de base de datos (diario)
0 1 * * * mysqldump -u user -p database > backup.sql
```

### Monitoreo
- Verificar logs de errores regularmente
- Monitorear uso de caché
- Revisar estadísticas de notificaciones
- Backup automático de base de datos

## 🚨 Troubleshooting

### Problemas Comunes

#### Error de Conexión a Base de Datos
```bash
# Verificar configuración en config/db.php
# Verificar que MySQL esté corriendo
# Verificar credenciales
```

#### Error de OneSignal
```bash
# Verificar configuración en config/onesignal.php
# Verificar credenciales de API
# Verificar conectividad a internet
```

#### Error de Permisos
```bash
# Verificar permisos de directorios
chmod 755 cache logs uploads
chmod 644 config/*.php
```

#### Error de Caché
```bash
# Limpiar caché manualmente
rm -rf cache/*
composer cache-clear
```

## 📈 Próximas Mejoras

### Funcionalidades Planificadas
- [ ] Dashboard con gráficos interactivos
- [ ] Sistema de mensajería interna
- [ ] Calendario de eventos
- [ ] Sistema de pagos
- [ ] App móvil nativa
- [ ] Integración con Google Calendar
- [ ] Sistema de evaluaciones
- [ ] Reportes avanzados con gráficos

### Mejoras Técnicas
- [ ] Migración a PHP 8.1+
- [ ] Implementación de WebSockets
- [ ] Cache distribuido con Redis
- [ ] Microservicios
- [ ] Docker containerization
- [ ] CI/CD pipeline

## 📞 Soporte

### Contacto
- **Email**: soporte@instituto-ingles.com
- **Teléfono**: +54 11 1234-5678
- **Horarios**: Lunes a Viernes 9:00 - 18:00

### Documentación Adicional
- [Manual de Usuario](docs/manual-usuario.md)
- [Manual Técnico](docs/manual-tecnico.md)
- [API Documentation](docs/api.md)
- [Troubleshooting Guide](docs/troubleshooting.md)

## 📄 Licencia

Este proyecto está bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

**Desarrollado con ❤️ para el Instituto de Inglés Roots** 