<?php
// cursos/crear.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../includes/header.php';

// Obtener alumnos para posible vinculación
$alumnos = $pdo->query("SELECT id, nombre, apellido FROM alumnos ORDER BY apellido")->fetchAll();
?>
<div class="container mt-4">
    <h2>Agregar Curso</h2>
    <form method="POST" action="procesar.php">
        <input type="hidden" name="accion" value="crear">

        <div class="mb-3">
            <label class="form-label">Nombre</label>
            <input type="text" name="nombre" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Descripción</label>
            <textarea name="descripcion" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Nivel</label>
            <input type="text" name="nivel" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Horario</label>
            <input type="text" name="horario" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Precio</label>
            <input type="number" name="precio" step="0.01" class="form-control">
        </div>

        <!-- <div class="mb-3">
            <label class="form-label">Asignar Alumnos - Ctrl + clic para multiselección</label>
            <select name="alumnos[]" class="form-select" multiple style="height: 300px;">
                <?php foreach ($alumnos as $alumno): ?>
                    <option value="<?= $alumno['id'] ?>"
                        <?= in_array($alumno['id'], $alumnosSeleccionados ?? []) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($alumno['apellido'] . ', ' . $alumno['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div> -->
        <div class="mb-3">
            <label class="form-label">Seleccionar Alumnos</label>
            <div class="border rounded p-2" style="max-height: 300px; overflow-y: scroll;">
                <?php foreach ($alumnos as $alumno): ?>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="alumnos[]" value="<?= $alumno['id'] ?>" id="alumno<?= $alumno['id'] ?>">
                        <label class="form-check-label" for="alumno<?= $alumno['id'] ?>">
                            <?= htmlspecialchars($alumno['apellido'] . ', ' . $alumno['nombre']) ?>
                        </label>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Guardar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php require_once __DIR__ . '/../../includes/footer.php'; ?>