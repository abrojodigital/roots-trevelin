# 📘 Proyecto de Administración - Instituto de Inglés

Este proyecto es una aplicación web desarrollada en **PHP + MySQL + Bootstrap** para administrar un instituto de inglés. Permite gestionar usuarios (familias), alumnos, cursos y noticias, además de contar con autenticación, filtros, paginación, manejo de errores, y una API para consumir noticias desde el exterior.

---

## 📁 Estructura de Carpetas

```
/ (raíz)
├── index.php              # Página principal (redirige al login)
├── login.php              # Formulario de login de administradores
├── logout.php             # Cierre de sesión
├── registrar_admin.php    # Registro de administradores (oculto)
├── README.md              # Documentación del proyecto
│
├── config/
│   └── db.php             # Conexión PDO a MySQL
│
├── auth/
│   └── check_auth.php     # Protección de rutas por sesión
│
├── includes/
│   ├── header.php         # Encabezado y navegación Bootstrap
│   └── footer.php         # Pie de página
│
├── admin/
│   ├── index.php          # Dashboard
│   ├── usuarios/          # CRUD de usuarios (familias) con contraseña
│   ├── alumnos/           # CRUD de alumnos + vinculación a cursos
│   ├── cursos/            # CRUD de cursos + selección de alumnos
│   └── noticias/          # CRUD de noticias + relaciones intermedias
│
├── api/
│   ├── noticias_generales.php
│   ├── noticias_familias.php
│   ├── noticias_cursos.php
│   └── noticias_alumnos.php
```

---

## 🔐 Autenticación

- Solo los administradores registrados en la tabla `administradores` pueden acceder al panel `/admin/`.
- La sesión se mantiene mediante `$_SESSION['admin']`.
- Uso de `check_auth.php` en cada página protegida.
- **Usuarios (familias)** también tienen campo `contraseña` (hasheada) para posibles futuras autenticaciones o integraciones.

---

## ✨ Funcionalidades

- CRUD completo de **usuarios** (familias), **alumnos**, **cursos**, **noticias**.
- Noticias vinculadas mediante tablas intermedias:
  - `noticias_usuarios`
  - `noticias_alumnos`
  - `noticias_cursos`
- Buscador, filtros y paginación en listados.
- Módulo de dashboard en el panel principal.
- API de noticias en JSON:
  - General, por alumno, curso o familia.
- Exportación de noticias a **JSON y PDF**.

---

## 🧪 Requisitos del servidor

- PHP 8.0+
- MySQL 5.7+
- Servidor web Apache o compatible
- Extensión PDO habilitada

---

## 📦 Instalación

1. Clonar o copiar el repositorio.
2. Importar el script de base de datos (`database.sql`) en MySQL.
3. Configurar las credenciales de base de datos en `config/db.php`.
4. Crear al menos un administrador con `registrar_admin.php` (luego puede eliminarse).
5. Acceder al login desde `/login.php`.

---

## 📚 API de Noticias (ver también `README.md` en `/api/`)

Consume las noticias en formato JSON para integrarlas a sitios externos con `fetch`, AJAX, etc.

---