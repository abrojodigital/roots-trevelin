<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';
include __DIR__ . '/../../includes/header.php';

$id = $_GET['id'] ?? null;

if (!$id) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM usuarios WHERE id = ?");
$stmt->execute([$id]);
$usuario = $stmt->fetch();

if (!$usuario) {
    echo "<div class='container'><div class='alert alert-danger'>Usuario no encontrado.</div></div>";
    include __DIR__ . '/../../includes/footer.php';
    exit;
}

$errores = $_SESSION['errores'] ?? [];
unset($_SESSION['errores']);
?>

<div class="container">
    <h2>Editar Usuario</h2>

    <?php if ($errores): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errores as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="procesar.php" method="post">
        <input type="hidden" name="accion" value="editar">
        <input type="hidden" name="id" value="<?= $usuario['id'] ?>">
        <input type="hidden" name="onesignal_player_id" value="<?= htmlspecialchars($usuario['onesignal_player_id'] ?? '') ?>">

        <div class="mb-3">
            <label>Nombre:</label>
            <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($usuario['nombre']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Apellido:</label>
            <input type="text" name="apellido" class="form-control" value="<?= htmlspecialchars($usuario['apellido']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Email:</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($usuario['email']) ?>" required>
        </div>
        <div class="mb-3">
            <label>Contraseña (dejar en blanco si no se desea cambiar)</label>
            <input type="password" name="contrasena" class="form-control">
        </div>
        <div class="mb-3">
            <label>Teléfono:</label>
            <input type="text" name="telefono" class="form-control" value="<?= htmlspecialchars($usuario['telefono']) ?>">
        </div>
        <div class="mb-3">
            <label>Dirección:</label>
            <input type="text" name="direccion" class="form-control" value="<?= htmlspecialchars($usuario['direccion']) ?>">
        </div>

        <button type="submit" class="btn btn-primary">Actualizar</button>
        <a href="index.php" class="btn btn-secondary">Cancelar</a>
    </form>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>