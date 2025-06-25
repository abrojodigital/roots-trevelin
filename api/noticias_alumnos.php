<?php
require_once '../config/db.php';
header('Content-Type: application/json');

$id_alumno = $_GET['id_alumno'] ?? null;

if (!$id_alumno) {
    echo json_encode(['status' => 'error', 'message' => 'Falta id_alumno']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT n.id, n.titulo, n.contenido, n.fecha_publicacion, n.autor
        FROM noticias n
        INNER JOIN noticias_alumnos na ON n.id = na.id_noticia
        WHERE na.id_alumno = ?
        ORDER BY n.fecha_publicacion DESC
    ");
    $stmt->execute([$id_alumno]);
    echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
