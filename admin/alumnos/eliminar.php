<?php
// alumnos/eliminar.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// Verificar si el alumno existe
$stmt = $pdo->prepare("SELECT id FROM alumnos WHERE id = ?");
$stmt->execute([$id]);
$alumno = $stmt->fetch();

if (!$alumno) {
    header("Location: index.php?error=notfound");
    exit;
}

// Eliminar el alumno
$stmt = $pdo->prepare("DELETE FROM alumnos WHERE id = ?");
$stmt->execute([$id]);

header("Location: index.php?mensaje=eliminado");
exit;
