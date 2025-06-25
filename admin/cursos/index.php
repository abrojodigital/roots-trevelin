<?php
// cursos/index.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';

// Búsqueda
$buscar = $_GET['buscar'] ?? '';
$params = [];
$sql = "SELECT * FROM cursos";

if ($buscar) {
    $sql .= " WHERE nombre LIKE ? OR nivel LIKE ?";
    $params[] = "%$buscar%";
    $params[] = "%$buscar%";
}

$sql .= " ORDER BY nombre ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$cursos = $stmt->fetchAll();

require_once __DIR__ . '/../../includes/header.php';
?>
<div class="container mt-4">
    <h2>Listado de Cursos</h2>

    <form method="GET" class="mb-3 d-flex">
        <input type="text" name="buscar" class="form-control me-2" placeholder="Buscar por nombre o nivel" value="<?= htmlspecialchars($buscar) ?>">
        <button type="submit" class="btn btn-outline-primary">Buscar</button>
    </form>

    <a href="crear.php" class="btn btn-success mb-3">Agregar Curso</a>

    <?php if (isset($_GET['mensaje'])): ?>
        <div class="alert alert-success">
            <?= htmlspecialchars($_GET['mensaje']) ?>
        </div>
    <?php endif; ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Nivel</th>
                <th>Horario</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cursos as $curso): ?>
                <tr>
                    <td><?= htmlspecialchars($curso['nombre']) ?></td>
                    <td><?= htmlspecialchars($curso['nivel']) ?></td>
                    <td><?= htmlspecialchars($curso['horario']) ?></td>
                    <td>$<?= number_format($curso['precio'], 2) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $curso['id'] ?>" class="btn btn-warning btn-sm">Editar</a>
                        <a href="eliminar.php?id=<?= $curso['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar este curso?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php require_once __DIR__ . '/../../includes/footer.php'; ?>
