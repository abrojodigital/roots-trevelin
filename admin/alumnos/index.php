<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';

// Manejo de búsqueda
$buscar = $_GET['buscar'] ?? '';
$params = [];
$sql = "SELECT a.*, u.nombre AS nombre_usuario, u.apellido AS apellido_usuario 
        FROM alumnos a 
        LEFT JOIN usuarios u ON a.id_usuario = u.id";

if ($buscar) {
    $sql .= " WHERE a.nombre LIKE ? OR a.apellido LIKE ?";
    $params[] = "%$buscar%";
    $params[] = "%$buscar%";
}

$sql .= " ORDER BY a.apellido ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$alumnos = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<div class="container mt-4">
    <h2>Listado de Alumnos</h2>

    <form method="GET" class="mb-3 d-flex" role="search">
        <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por nombre o apellido" value="<?= htmlspecialchars($buscar) ?>">
        <button type="submit" class="btn btn-outline-primary">Buscar</button>
    </form>

    <a href="crear.php" class="btn btn-success mb-3">Agregar Alumno</a>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['mensaje']) ?>
        </div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Email</th>
                <th>Teléfono</th>
                <th>Usuario (Familia)</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($alumnos as $alumno): ?>
                <tr>
                    <td><?= htmlspecialchars($alumno['nombre']) ?></td>
                    <td><?= htmlspecialchars($alumno['apellido']) ?></td>
                    <td><?= htmlspecialchars($alumno['email']) ?></td>
                    <td><?= htmlspecialchars($alumno['telefono']) ?></td>
                    <td><?= htmlspecialchars($alumno['nombre_usuario'] . ' ' . $alumno['apellido_usuario']) ?></td>
                    <td>
                        <a href="cursos.php?id=<?= $alumno['id'] ?>" class="btn btn-sm btn-info">Cursos</a>
                        <a href="editar.php?id=<?= $alumno['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar.php?id=<?= $alumno['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de eliminar este alumno?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>