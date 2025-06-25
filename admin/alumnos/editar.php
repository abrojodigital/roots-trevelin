<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';
include __DIR__ . '/../../includes/header.php';

$id = $_GET['id'] ?? null;
if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM alumnos WHERE id = ?");
$stmt->execute([$id]);
$alumno = $stmt->fetch();

if (!$alumno) {
    echo "<div class='container'><div class='alert alert-danger'>Alumno no encontrado</div></div>";
    include __DIR__ . '/../../includes/footer.php';
    exit;
}

$usuarios = $pdo->query("SELECT id, nombre, apellido FROM usuarios ORDER BY apellido, nombre")->fetchAll();
?>
<div class="container mt-4">
    <h2>Editar Alumno</h2>
    <form action="procesar.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id" value="<?= $alumno['id'] ?>">
        
        <div class="mb-3">
            <label for="nombre" class="form-label">Nombre</label>
            <input type="text" name="nombre" id="nombre" class="form-control" value="<?= htmlspecialchars($alumno['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="apellido" class="form-label">Apellido</label>
            <input type="text" name="apellido" id="apellido" class="form-control" value="<?= htmlspecialchars($alumno['apellido']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
            <input type="date" name="fecha_nacimiento" id="fecha_nacimiento" class="form-control" value="<?= $alumno['fecha_nacimiento'] ?>">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($alumno['email']) ?>">
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Teléfono</label>
            <input type="text" name="telefono" id="telefono" class="form-control" value="<?= htmlspecialchars($alumno['telefono']) ?>">
        </div>
        <div class="mb-3">
            <label for="id_usuario" class="form-label">Usuario (Familia)</label>
            <select name="id_usuario" id="id_usuario" class="form-select">
                <option value="">-- Seleccionar --</option>
                <?php foreach ($usuarios as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= $u['id'] == $alumno['id_usuario'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u['apellido'] . ', ' . $u['nombre']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="foto" class="form-label">Foto del Alumno</label>
            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
            <?php if (!empty($alumno['foto'])): ?>
                <div class="mt-2">
                    <img src="<?= BASE_URL ?>/assets/img/alumnos/<?= htmlspecialchars($alumno['foto']) ?>" alt="Foto del alumno" style="max-width: 150px;">
                </div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>
<?php include __DIR__ . '/../../includes/footer.php'; ?>
