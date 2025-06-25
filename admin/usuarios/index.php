<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';

$search = $_GET['buscar'] ?? '';
$sql = "SELECT * FROM usuarios WHERE nombre LIKE :buscar OR apellido LIKE :buscar ORDER BY id DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$search%"]);
$usuarios = $stmt->fetchAll();
include __DIR__ . '/../../includes/header.php';
?>

<div class="container">
    <h2 class="mb-4">Usuarios (Familias)</h2>

    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por nombre o apellido" value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-secondary">Buscar</button>
        </div>
    </form>

    <a href="crear.php" class="btn btn-success mb-3">+ Nuevo Usuario</a>

    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>ID</th><th>Nombre</th><th>Apellido</th><th>Email</th><th>Teléfono</th><th>Dirección</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($usuarios as $usuario): ?>
                <tr>
                    <td><?= $usuario['id'] ?></td>
                    <td><?= htmlspecialchars($usuario['nombre']) ?></td>
                    <td><?= htmlspecialchars($usuario['apellido']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= htmlspecialchars($usuario['telefono']) ?></td>
                    <td><?= htmlspecialchars($usuario['direccion']) ?></td>
                    <td>
                        <a href="editar.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="eliminar.php?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro que deseas eliminar este usuario?')">Eliminar</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
