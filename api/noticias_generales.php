<?php
require_once '../config/db.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->prepare("SELECT id, titulo, contenido, fecha_publicacion, autor FROM noticias WHERE tipo_audiencia = 'general' ORDER BY fecha_publicacion DESC");
    $stmt->execute();
    $noticias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'data' => $noticias
    ], JSON_UNESCAPED_UNICODE);

} catch (PDOException $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener las noticias: ' . $e->getMessage()
    ]);
}
