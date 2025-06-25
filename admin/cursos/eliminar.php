<?php
// cursos/eliminar.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';

$id = $_GET['id'] ?? null;
if ($id) {
    $pdo->prepare("DELETE FROM cursos_alumnos WHERE id_curso = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM cursos WHERE id = ?")->execute([$id]);
}

header('Location: index.php?mensaje=Curso eliminado');
exit;
