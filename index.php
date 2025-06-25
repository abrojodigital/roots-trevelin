<?php
session_start();
if (isset($_SESSION['admin'])) {
    header("Location: admin/index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Bienvenido - Instituto de Inglés</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">
    <div class="text-center">
        <h1 class="mb-4">Instituto de Inglés</h1>
        <a href="login.php" class="btn btn-primary btn-lg">Iniciar sesión</a>
    </div>
</body>
</html>
