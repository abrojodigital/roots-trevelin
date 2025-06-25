<?php
require_once '../../auth/check_auth.php';
?>

<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Noticias - Instituto de Inglés</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container py-5">
    <h1 class="mb-4">Noticias por Audiencia</h1>

    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <label for="tipo" class="form-label">Tipo de audiencia:</label>
        <select id="tipo" class="form-select">
          <option value="generales">Generales</option>
          <option value="familias">Familias</option>
          <option value="cursos">Cursos</option>
          <option value="alumnos">Alumnos</option>
        </select>
      </div>
      <div class="col-md-4 d-none" id="inputContainer">
        <label for="id" class="form-label">ID:</label>
        <input type="number" id="id" class="form-control" placeholder="Ingresá un ID">
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button class="btn btn-primary w-100" onclick="cargarNoticias()">Ver Noticias</button>
      </div>
    </div>

    <div class="d-flex gap-2 mb-3">
      <button class="btn btn-success" onclick="exportarJSON()">Exportar a JSON</button>
      <button class="btn btn-danger" onclick="exportarPDF()">Exportar a PDF</button>
    </div>

    <div id="resultado" class="row gy-3"></div>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
  <script>
    const tipoSelect = document.getElementById('tipo');
    const inputContainer = document.getElementById('inputContainer');
    const idInput = document.getElementById('id');
    const resultado = document.getElementById('resultado');
    let noticiasActuales = [];

    tipoSelect.addEventListener('change', () => {
      inputContainer.classList.toggle('d-none', tipoSelect.value === 'generales');
    });

    function cargarNoticias() {
      const tipo = tipoSelect.value;
      const id = idInput.value.trim();
      let url = `../../api/noticias_${tipo}.php`;

      if (tipo !== 'generales') {
        if (!id) {
          alert('Debés ingresar un ID válido.');
          return;
        }
        url += `?id_${tipo.slice(0, -1)}=${id}`;
      }

      fetch(url)
        .then(res => res.json())
        .then(data => {
          resultado.innerHTML = '';
          noticiasActuales = [];

          if (data.status === 'success' && data.data.length > 0) {
            noticiasActuales = data.data;

            data.data.forEach(noticia => {
              resultado.innerHTML += `
                <div class="col-md-6">
                  <div class="card shadow-sm h-100">
                    <div class="card-body">
                      <h5 class="card-title">${noticia.titulo}</h5>
                      <h6 class="card-subtitle mb-2 text-muted">${noticia.fecha_publicacion} – ${noticia.autor}</h6>
                      <p class="card-text">${noticia.contenido}</p>
                    </div>
                  </div>
                </div>
              `;
            });
          } else {
            resultado.innerHTML = `<div class="alert alert-warning">No se encontraron noticias.</div>`;
          }
        })
        .catch(error => {
          console.error(error);
          resultado.innerHTML = `<div class="alert alert-danger">Error al cargar noticias.</div>`;
        });
    }

    function exportarJSON() {
      if (!noticiasActuales.length) {
        alert('No hay noticias cargadas.');
        return;
      }
      const blob = new Blob([JSON.stringify(noticiasActuales, null, 2)], { type: "application/json" });
      const url = URL.createObjectURL(blob);
      const a = document.createElement("a");
      a.href = url;
      a.download = "noticias.json";
      a.click();
      URL.revokeObjectURL(url);
    }

    function exportarPDF() {
      if (!noticiasActuales.length) {
        alert('No hay noticias cargadas.');
        return;
      }

      const { jsPDF } = window.jspdf;
      const doc = new jsPDF();
      let y = 10;

      noticiasActuales.forEach((n, i) => {
        doc.setFontSize(14);
        doc.text(n.titulo, 10, y);
        y += 6;
        doc.setFontSize(10);
        doc.text(`${n.fecha_publicacion} - ${n.autor}`, 10, y);
        y += 6;

        const splitTexto = doc.splitTextToSize(n.contenido, 180);
        splitTexto.forEach(linea => {
          if (y > 280) {
            doc.addPage();
            y = 10;
          }
          doc.text(linea, 10, y);
          y += 5;
        });

        y += 10;
        if (y > 280 && i < noticiasActuales.length - 1) {
          doc.addPage();
          y = 10;
        }
      });

      doc.save('noticias.pdf');
    }
  </script>
</body>
</html>
