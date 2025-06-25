<?php
// admin/index.php (Dashboard principal)
require_once __DIR__ . '/../config/db.php';
require_once __DIR__ . '/../auth/check_auth.php';
require_once __DIR__ . '/../includes/header.php';

// Contar registros para resumen rápido
$totalUsuarios = $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$totalAlumnos = $pdo->query("SELECT COUNT(*) FROM alumnos")->fetchColumn();
$totalCursos = $pdo->query("SELECT COUNT(*) FROM cursos")->fetchColumn();
$totalNoticias = $pdo->query("SELECT COUNT(*) FROM noticias")->fetchColumn();
?>

<div class="container mt-5">
    <h2 class="mb-4">Panel de Administración</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Usuarios</h5>
                    <p class="card-text fs-4"><?= $totalUsuarios ?></p>
                    <a href="usuarios/index.php" class="btn btn-light btn-sm">Ver más</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Alumnos</h5>
                    <p class="card-text fs-4"><?= $totalAlumnos ?></p>
                    <a href="alumnos/index.php" class="btn btn-light btn-sm">Ver más</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3">
                <div class="card-body">
                    <h5 class="card-title">Cursos</h5>
                    <p class="card-text fs-4"><?= $totalCursos ?></p>
                    <a href="cursos/index.php" class="btn btn-light btn-sm">Ver más</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Noticias</h5>
                    <p class="card-text fs-4"><?= $totalNoticias ?></p>
                    <a href="noticias/index.php" class="btn btn-light btn-sm">Ver más</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>

