<?php
require_once '../config/db.php';
header('Content-Type: application/json');

// Determinar el método de solicitud
$method = $_SERVER['REQUEST_METHOD'];

// Obtener el id_usuario según el método
if ($method === 'GET') {
    $id_usuario = $_GET['id_usuario'] ?? null;
} elseif ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $id_usuario = $input['id_usuario'] ?? null;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Método no permitido']);
    http_response_code(405);
    exit;
}

// Validación básica
if (!$id_usuario || !is_numeric($id_usuario)) {
    echo json_encode(['status' => 'error', 'message' => 'id_usuario es requerido y debe ser numérico']);
    exit;
}

try {
    $stmt = $pdo->prepare("
        SELECT n.id, n.titulo, n.contenido, n.fecha_publicacion, n.autor
        FROM noticias n
        INNER JOIN noticias_usuarios nu ON n.id = nu.id_noticia
        WHERE nu.id_usuario = ?
        ORDER BY n.fecha_publicacion DESC
    ");
    $stmt->execute([$id_usuario]);
    echo json_encode(['status' => 'success', 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al obtener datos']);
    // Opcional: loguear el error
    // error_log($e->getMessage());
}