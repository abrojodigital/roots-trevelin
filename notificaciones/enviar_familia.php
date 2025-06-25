<?php
require_once __DIR__ . '/enviar_onesignal_base.php';
require_once __DIR__ . '/../config/db.php';

function enviarNoticiaAFamilia($id_usuario, $titulo, $contenido) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT onesignal_player_id FROM usuarios WHERE id = ?");
    $stmt->execute([$id_usuario]);
    $playerId = $stmt->fetchColumn();

    if ($playerId) {
        return enviarOneSignal($titulo, $contenido, ['include_player_ids' => [$playerId]]);
    }
    return false;
}