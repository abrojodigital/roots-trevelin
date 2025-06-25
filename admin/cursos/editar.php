<?php
require_once '../../config/db.php';
require_once '../../auth/check_auth.php';
require_once '../../includes/header.php';

if (!isset($_GET['id'])) {
    echo "ID de curso no especificado.";
    exit;
}

$id = $_GET['id'];

$stmt = $pdo->prepare("SELECT * FROM cursos WHERE id = ?");
$stmt->execute([$id]);
$curso = $stmt->fetch();

if (!$curso) {
    echo "Curso no encontrado.";
    exit;
}

// Obtener alumnos disponibles y seleccionados
$alumnosStmt = $pdo->query("SELECT id, nombre, apellido FROM alumnos ORDER BY apellido, nombre");
$alumnos = $alumnosStmt->fetchAll();

$seleccionadosStmt = $pdo->prepare("SELECT id_alumno FROM cursos_alumnos WHERE id_curso = ?");
$seleccionadosStmt->execute([$id]);
$alumnosSeleccionados = $seleccionadosStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<div class="container mt-5">
    <h2>Editar Curso</h2>
    <form action="procesar.php" method="POST">
        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id" value="<?= $curso['id'] ?>">

        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($curso['nombre']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="descripcion" class="form-label">Descripción</label>
            <textarea name="descripcion" id="descripcion" class="form-control"><?= htmlspecialchars($curso['descripcion']) ?></textarea>
        </div>

        <div class="mb-3">
            <label for="nivel" class="form-label">Nivel</label>
            <input type="text" name="nivel" id="nivel" class="form-control" value="<?= htmlspecialchars($curso['nivel']) ?>">
        </div>

        <div class="mb-3">
            <label for="horario" class="form-label">Horario</label>
            <input type="text" name="horario" id="horario" class="form-control" value="<?= htmlspecialchars($curso['horario']) ?>">
        </div>

        <div class="mb-3">
            <label for="precio" class="form-label">Precio</label>
            <input type="number" step="0.01" name="precio" id="precio" class="form-control" value="<?= htmlspecialchars($curso['precio']) ?>">
        </div>

        <div class="mb-3">
            <label class="form-label">Seleccionar Alumnos</label>
            <div class="border rounded p-2" style="max-height: 300px; overflow-y: scroll;">
                <?php foreach ($alumnos as $alumno): ?>
                    <div class="form-check">
                        <input
                            class="form-check-input"
                            type="checkbox"
                            name="alumnos[]"
                            value="<?= $alumno['id'] ?>"
                            id="alumno<?= $alumno['id'] ?>"
                            <?= in_array($alumno['id'], $alumnosSeleccionados) ? 'checked' : '' ?>
                        >
                        <label class="form-check-label" for="alumno<?= $alumno['id'] ?>">
                            <?= htmlspecialchars($alumno['apellido'] . ', ' . $alumno['nombre']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Actualizar Curso</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require_once '../../includes/footer.php'; ?>