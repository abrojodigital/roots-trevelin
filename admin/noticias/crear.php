<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';
include __DIR__ . '/../../includes/header.php';

// Cargar listas para checkboxes
$alumnos = $pdo->query("SELECT id, nombre, apellido FROM alumnos ORDER BY apellido, nombre")->fetchAll();
$usuarios = $pdo->query("SELECT id, nombre, apellido FROM usuarios ORDER BY apellido, nombre")->fetchAll();
$cursos = $pdo->query("SELECT id, nombre FROM cursos ORDER BY nombre")->fetchAll();
?>

<div class="container">
    <h2 class="mb-4">Nueva Noticia</h2>

    <form action="procesar.php" method="post">
        <input type="hidden" name="accion" value="crear">

        <div class="mb-3">
            <label for="titulo" class="form-label">Título</label>
            <input type="text" name="titulo" id="titulo" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="contenido" class="form-label">Contenido</label>
            <textarea name="contenido" id="contenido" class="form-control" rows="5" required></textarea>
        </div>

        <div class="mb-3">
            <label for="fecha_publicacion" class="form-label">Fecha de Publicación</label>
            <input type="date" name="fecha_publicacion" id="fecha_publicacion" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="autor" class="form-label">Autor</label>
            <input type="text" name="autor" id="autor" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="tipo_audiencia" class="form-label">Tipo de Audiencia</label>
            <select name="tipo_audiencia" id="tipo_audiencia" class="form-select" required onchange="mostrarOpcionesRelacionadas()">
                <option value="general">General</option>
                <option value="familia">Familias</option>
                <option value="alumno">Alumnos</option>
                <option value="curso">Cursos</option>
            </select>
        </div>

        <div id="audiencia_alumnos" class="mb-3 d-none">
            <label class="form-label">Seleccionar Alumnos</label>
            <?php foreach ($alumnos as $a): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="alumnos[]" value="<?= $a['id'] ?>" id="alumno<?= $a['id'] ?>">
                    <label class="form-check-label" for="alumno<?= $a['id'] ?>">
                        <?= htmlspecialchars($a['apellido'] . ', ' . $a['nombre']) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="audiencia_usuarios" class="mb-3 d-none">
            <label class="form-label">Seleccionar Usuarios (Familias)</label>
            <?php foreach ($usuarios as $u): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="usuarios[]" value="<?= $u['id'] ?>" id="usuario<?= $u['id'] ?>">
                    <label class="form-check-label" for="usuario<?= $u['id'] ?>">
                        <?= htmlspecialchars($u['apellido'] . ', ' . $u['nombre']) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="audiencia_cursos" class="mb-3 d-none">
            <label class="form-label">Seleccionar Cursos</label>
            <?php foreach ($cursos as $c): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="cursos[]" value="<?= $c['id'] ?>" id="curso<?= $c['id'] ?>">
                    <label class="form-check-label" for="curso<?= $c['id'] ?>">
                        <?= htmlspecialchars($c['nombre']) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn btn-primary">Guardar Noticia</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<script>
function mostrarOpcionesRelacionadas() {
    const tipo = document.getElementById('tipo_audiencia').value;
    document.getElementById('audiencia_alumnos').classList.add('d-none');
    document.getElementById('audiencia_usuarios').classList.add('d-none');
    document.getElementById('audiencia_cursos').classList.add('d-none');

    if (tipo === 'alumno') {
        document.getElementById('audiencia_alumnos').classList.remove('d-none');
    } else if (tipo === 'familia') {
        document.getElementById('audiencia_usuarios').classList.remove('d-none');
    } else if (tipo === 'curso') {
        document.getElementById('audiencia_cursos').classList.remove('d-none');
    }
}

document.addEventListener('DOMContentLoaded', mostrarOpcionesRelacionadas);
</script>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
