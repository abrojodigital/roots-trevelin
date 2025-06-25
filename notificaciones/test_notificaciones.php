<?php
require_once 'enviar_general.php';
require_once 'enviar_familia.php';
require_once 'enviar_curso.php';
require_once 'enviar_alumno.php';
require_once '../config/db.php';

function renderForm() {
    global $pdo;

    // Obtener listas
    $alumnos = $pdo->query("SELECT id, nombre FROM alumnos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    $cursos = $pdo->query("SELECT id, nombre FROM cursos ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    $usuarios = $pdo->query("SELECT id, nombre FROM usuarios ORDER BY nombre")->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <form method="POST">
        <label><strong>Título:</strong></label><br>
        <input type="text" name="titulo" required><br><br>

        <label><strong>Contenido:</strong></label><br>
        <textarea name="contenido" rows="4" cols="50" required></textarea><br><br>

        <label><strong>Destino:</strong></label><br>
        <select name="tipo" required onchange="mostrarCampo(this.value)">
            <option value="general">General</option>
            <option value="familia">Por Familia</option>
            <option value="curso">Por Curso</option>
            <option value="alumno">Por Alumno</option>
        </select><br><br>

        <div id="extraInput" style="display: none;">
            <label id="extraLabel"></label><br>
            <input type="text" id="buscador" placeholder="Buscar..." onkeyup="filtrarLista()" />
            <select name="extra_id" id="listaOpciones" size="5" required></select><br><br>
        </div>

        <input type="submit" value="Enviar Notificación">
    </form>

    <script>
    const data = {
        alumno: <?= json_encode($alumnos) ?>,
        curso: <?= json_encode($cursos) ?>,
        familia: <?= json_encode($usuarios) ?>
    };

    function mostrarCampo(tipo) {
        const div = document.getElementById('extraInput');
        const label = document.getElementById('extraLabel');
        const lista = document.getElementById('listaOpciones');
        const buscador = document.getElementById('buscador');

        if (tipo === 'general') {
            div.style.display = 'none';
        } else {
            div.style.display = 'block';
            label.textContent = tipo === 'familia' ? 'Seleccionar Familia:' :
                                tipo === 'curso' ? 'Seleccionar Curso:' : 'Seleccionar Alumno:';

            // Cargar opciones
            lista.innerHTML = '';
            data[tipo].forEach(item => {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.nombre;
                lista.appendChild(opt);
            });
        }
    }

    function filtrarLista() {
        const tipo = document.querySelector('select[name="tipo"]').value;
        const filtro = document.getElementById('buscador').value.toLowerCase();
        const lista = document.getElementById('listaOpciones');

        lista.innerHTML = '';
        data[tipo].forEach(item => {
            if (item.nombre.toLowerCase().includes(filtro)) {
                const opt = document.createElement('option');
                opt.value = item.id;
                opt.textContent = item.nombre;
                lista.appendChild(opt);
            }
        });
    }
    </script>
    <?php
}

function procesarEnvio() {
    $titulo = $_POST['titulo'];
    $contenido = $_POST['contenido'];
    $tipo = $_POST['tipo'];
    $extra_id = $_POST['extra_id'] ?? null;

    $resultado = false;

    switch ($tipo) {
        case 'general':
            $resultado = enviarNoticiaGeneral($titulo, $contenido);
            break;
        case 'familia':
            if ($extra_id) $resultado = enviarNoticiaAFamilia($extra_id, $titulo, $contenido);
            break;
        case 'curso':
            if ($extra_id) $resultado = enviarNoticiaACurso($extra_id, $titulo, $contenido);
            break;
        case 'alumno':
            if ($extra_id) $resultado = enviarNoticiaAAlumno($extra_id, $titulo, $contenido);
            break;
    }

    echo $resultado
        ? "<p style='color: green;'>✅ Notificación enviada con éxito</p>"
        : "<p style='color: red;'>❌ Error al enviar la notificación</p>";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    procesarEnvio();
}

renderForm();
