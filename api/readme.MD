# API de Noticias - Instituto de Inglés

Este proyecto incluye un sistema de gestión de noticias con una API en PHP que genera datos en formato JSON. Está diseñado para ser consumido desde interfaces web con JavaScript (usando `fetch`) o herramientas externas.

---

## 🔍 Endpoints Disponibles

| Endpoint                                 | Descripción                           |
| ---------------------------------------- | ------------------------------------- |
| `/api/noticias_generales.php`            | Noticias para todos                   |
| `/api/noticias_familias.php`             | Noticias dirigidas a usuarios         |
| `/api/noticias_cursos.php?id_curso=ID`   | Noticias para un curso en particular  |
| `/api/noticias_alumnos.php?id_alumno=ID` | Noticias para un alumno en particular |

---

## ✅ Ejemplo 1: Obtener todas las noticias generales

```html
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Noticias Generales</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-4">
  <h2>Noticias Generales</h2>
  <table class="table table-bordered table-striped" id="tablaNoticias">
    <thead>
      <tr>
        <th>Título</th>
        <th>Contenido</th>
        <th>Fecha</th>
        <th>Autor</th>
      </tr>
    </thead>
    <tbody></tbody>
  </table>
</div>

<script>
fetch('/api/noticias_generales.php')
  .then(response => {
    if (!response.ok) throw new Error('Error al obtener las noticias');
    return response.json();
  })
  .then(data => {
    const tbody = document.querySelector('#tablaNoticias tbody');
    data.forEach(noticia => {
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td>${noticia.titulo}</td>
        <td>${noticia.contenido}</td>
        <td>${noticia.fecha_publicacion}</td>
        <td>${noticia.autor}</td>
      `;
      tbody.appendChild(tr);
    });
  })
  .catch(error => {
    console.error('Hubo un error:', error);
    alert("Error cargando noticias");
  });
</script>
</body>
</html>
```

---

## ✅ Ejemplo 2: Noticias por curso

```js
const idCurso = 3;
fetch(`/api/noticias_cursos.php?id_curso=${idCurso}`)
  .then(res => res.json())
  .then(noticias => {
    console.table(noticias);
  });
```

---

## ✅ Ejemplo 3: Noticias por alumno

```js
const idAlumno = 5;
fetch(`/api/noticias_alumnos.php?id_alumno=${idAlumno}`)
  .then(res => res.json())
  .then(noticias => {
    noticias.forEach(n => console.log(n.titulo, n.fecha_publicacion));
  });
```

---

## 🛡️ Buenas prácticas

- Validar siempre los parámetros de entrada con `filter_input` o similares.
- Escapar HTML al mostrar contenido usando `htmlspecialchars()`.
- Proteger la carpeta `/api/` si es necesario mediante autenticación o token.
- Documentar el uso esperado de cada endpoint.

---

¡Este sistema puede integrarse fácilmente a un panel de administración o una página web pública!
