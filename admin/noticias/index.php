<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';
include __DIR__ . '/../../includes/header.php';

$search = $_GET['buscar'] ?? '';
$sql = "SELECT * FROM noticias WHERE titulo LIKE :buscar OR contenido LIKE :buscar ORDER BY fecha_publicacion DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute(['buscar' => "%$search%"]);
$noticias = $stmt->fetchAll();
?>

<div class="container">
    <h2 class="mb-4">Noticias</h2>

    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="buscar" class="form-control" placeholder="Buscar por título o contenido" value="<?= htmlspecialchars($search) ?>">
            <button class="btn btn-outline-secondary">Buscar</button>
        </div>
    </form>

    <a href="crear.php" class="btn btn-success mb-3">+ Nueva Noticia</a>

    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>ID</th><th>Título</th><th>Fecha</th><th>Autor</th><th>Audiencia</th><th>Notificada</th><th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($noticias as $noticia): ?>
                <tr>
                    <td><?= $noticia['id'] ?></td>
                    <td><?= htmlspecialchars($noticia['titulo']) ?></td>
                    <td><?= htmlspecialchars($noticia['fecha_publicacion']) ?></td>
                    <td><?= htmlspecialchars($noticia['autor']) ?></td>
                    <td><?= htmlspecialchars($noticia['tipo_audiencia']) ?></td>
                    <td class="text-center">
                        <?php if ($noticia['notificada'] == 1): ?>
                            <span class="text-success" title="Notificada">&#10004;</span>
                        <?php else: ?>
                            <span class="text-muted" title="No notificada">&#10008;</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="editar.php?id=<?= $noticia['id'] ?>" class="btn btn-sm btn-warning">Editar</a>
                        <a href="eliminar.php?id=<?= $noticia['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta noticia?')">Eliminar</a>
                        <?php if ($noticia['notificada'] != 1): ?>
                            <a href="notificar.php?id=<?= $noticia['id'] ?>" class="btn btn-sm btn-info" onclick="return confirm('¿Notificar esta noticia?')">Notificar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/../../includes/footer.php'; ?>
