<?php
require_once '../config/db.php';
header('Content-Type: application/json');

// Obtener JSON del cuerpo
$input = json_decode(file_get_contents("php://input"), true);
$id_usuario = $input['id_usuario'] ?? null;
$player_id = $input['player_id'] ?? null;

if (!$id_usuario || !$player_id) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos']);
    exit;
}

try {
    // Desvincular este player_id de cualquier otro usuario
    $desvincular = $pdo->prepare("UPDATE usuarios SET onesignal_player_id = NULL WHERE onesignal_player_id = ?");
    $desvincular->execute([$player_id]);

    // Actualizar el usuario actual con el nuevo player_id
    $stmt = $pdo->prepare("UPDATE usuarios SET onesignal_player_id = ?, onesignal_updated_at = NOW() WHERE id = ?");
    $stmt->execute([$player_id, $id_usuario]);

    echo json_encode(['status' => 'success', 'message' => 'Token actualizado']);
} catch (PDOException $e) {
    error_log('Error actualizando OneSignal: ' . $e->getMessage());
    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el token']);
}
