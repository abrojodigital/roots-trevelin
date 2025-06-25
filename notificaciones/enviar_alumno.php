<?php
//enviar_alumno.php
require_once __DIR__ . '/enviar_onesignal_base.php';
require_once __DIR__ . '/../config/db.php';

function enviarNoticiaAAlumno($id_alumno, $titulo, $contenido) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT u.onesignal_player_id
                           FROM alumnos a
                           JOIN usuarios u ON a.id_usuario = u.id
                           WHERE a.id = ? AND u.onesignal_player_id IS NOT NULL");
    $stmt->execute([$id_alumno]);
    $playerId = $stmt->fetchColumn();

    if ($playerId) {
        return enviarOneSignal($titulo, $contenido, ['include_player_ids' => [$playerId]]);
    }
    return false;
}