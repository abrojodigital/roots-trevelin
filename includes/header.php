<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/config.php';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Instituto de Inglés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="<?= BASE_URL ?>/admin/index.php">Roots - Instituto de Inglés Trevelin</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/usuarios/index.php">Usuarios</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/alumnos/index.php">Alumnos</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/cursos/index.php">Cursos</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/noticias/index.php">Noticias</a></li>
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/noticias/ver.php">Ver Noticias</a></li>
      </ul>
      <?php if (isset($_SESSION['admin'])): ?>
        <span class="navbar-text me-2">Hola, <?= htmlspecialchars($_SESSION['admin']['nombre']) ?></span>
        <a href="<?= BASE_URL ?>/logout.php" class="btn btn-outline-light btn-sm">Salir</a>
      <?php endif; ?>
    </div>
  </div>
</nav>
