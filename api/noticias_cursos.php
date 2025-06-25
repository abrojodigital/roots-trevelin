<?php
require_once '../config/db.php';
header('Content-Type: application/json');

$id_curso = $_GET['id_curso'] ?? null;

if (!$id_curso) {
    echo json_encode(['status' => 'error', 'message' => 'Falta id_curso']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT n.id, n.titulo, n.contenido, n.fecha_publicacion, n.autor
        FROM noticias n
        INNER JOIN noticias_cursos nc ON n.id = nc.id_noticia
        WHERE nc.id_curso = ?
        ORDER BY n.fecha_publicacion DESC
    ");
    $stmt->execute([$id_curso]);
    echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}
