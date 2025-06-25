<?php
// eliminar.php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';

$id = $_GET['id'] ?? null;
if ($id) {
    // Eliminar relaciones primero
    $pdo->prepare("DELETE FROM noticias_alumnos WHERE id_noticia = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM noticias_usuarios WHERE id_noticia = ?")->execute([$id]);
    $pdo->prepare("DELETE FROM noticias_cursos WHERE id_noticia = ?")->execute([$id]);
    // Luego eliminar la noticia principal
    $pdo->prepare("DELETE FROM noticias WHERE id = ?")->execute([$id]);
}
header("Location: index.php");
exit;
