<?php
// editar.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';
include __DIR__ . '/../../includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = ?");
$stmt->execute([$id]);
$noticia = $stmt->fetch();

if (!$noticia) {
    echo "<div class='alert alert-danger'>Noticia no encontrada.</div>";
    include __DIR__ . '/../../includes/footer.php';
    exit;
}

// Cargar relaciones existentes
$alumnos_sel = $pdo->query("SELECT id_alumno FROM noticias_alumnos WHERE id_noticia = $id")->fetchAll(PDO::FETCH_COLUMN);
$usuarios_sel = $pdo->query("SELECT id_usuario FROM noticias_usuarios WHERE id_noticia = $id")->fetchAll(PDO::FETCH_COLUMN);
$cursos_sel = $pdo->query("SELECT id_curso FROM noticias_cursos WHERE id_noticia = $id")->fetchAll(PDO::FETCH_COLUMN);

// Cargar listas completas
$alumnos = $pdo->query("SELECT id, nombre, apellido FROM alumnos ORDER BY apellido, nombre")->fetchAll();
$usuarios = $pdo->query("SELECT id, nombre, apellido FROM usuarios ORDER BY apellido, nombre")->fetchAll();
$cursos = $pdo->query("SELECT id, nombre FROM cursos ORDER BY nombre")->fetchAll();
?>
<div class="container">
    <h2 class="mb-4">Editar Noticia</h2>

    <form action="procesar.php" method="post">
        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id" value="<?= $noticia['id'] ?>">

        <div class="mb-3">
            <label class="form-label">Título</label>
            <input type="text" name="titulo" class="form-control" value="<?= htmlspecialchars($noticia['titulo']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Contenido</label>
            <textarea name="contenido" class="form-control" rows="5" required><?= htmlspecialchars($noticia['contenido']) ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Fecha de Publicación</label>
            <input type="date" name="fecha_publicacion" class="form-control" value="<?= $noticia['fecha_publicacion'] ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Autor</label>
            <input type="text" name="autor" class="form-control" value="<?= htmlspecialchars($noticia['autor']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Tipo de Audiencia</label>
            <select name="tipo_audiencia" class="form-select" onchange="mostrarOpcionesRelacionadas()" id="tipo_audiencia">
                <option value="general" <?= $noticia['tipo_audiencia'] === 'general' ? 'selected' : '' ?>>General</option>
                <option value="familia" <?= $noticia['tipo_audiencia'] === 'familia' ? 'selected' : '' ?>>Familias</option>
                <option value="alumno" <?= $noticia['tipo_audiencia'] === 'alumno' ? 'selected' : '' ?>>Alumnos</option>
                <option value="curso" <?= $noticia['tipo_audiencia'] === 'curso' ? 'selected' : '' ?>>Cursos</option>
            </select>
        </div>

        <div id="audiencia_alumnos" class="mb-3 d-none">
            <label class="form-label">Seleccionar Alumnos</label>
            <?php foreach ($alumnos as $a): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="alumnos[]" value="<?= $a['id'] ?>" id="alumno<?= $a['id'] ?>" <?= in_array($a['id'], $alumnos_sel) ? 'checked' : '' ?>>
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
                    <input class="form-check-input" type="checkbox" name="usuarios[]" value="<?= $u['id'] ?>" id="usuario<?= $u['id'] ?>" <?= in_array($u['id'], $usuarios_sel) ? 'checked' : '' ?>>
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
                    <input class="form-check-input" type="checkbox" name="cursos[]" value="<?= $c['id'] ?>" id="curso<?= $c['id'] ?>" <?= in_array($c['id'], $cursos_sel) ? 'checked' : '' ?>>
                    <label class="form-check-label" for="curso<?= $c['id'] ?>">
                        <?= htmlspecialchars($c['nombre']) ?>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <button type="submit" class="btn btn-success">Actualizar</button>
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