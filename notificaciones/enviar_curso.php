<?php
require_once __DIR__ . '/enviar_onesignal_base.php';
require_once __DIR__ . '/../config/db.php';

function enviarNoticiaACurso($id_curso, $titulo, $contenido) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT DISTINCT u.onesignal_player_id
                           FROM cursos_alumnos ca
                           JOIN alumnos a ON ca.id_alumno = a.id
                           JOIN usuarios u ON a.id_usuario = u.id
                           WHERE ca.id_curso = ? AND u.onesignal_player_id IS NOT NULL");
    $stmt->execute([$id_curso]);
    $playerIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

    if ($playerIds) {
        return enviarOneSignal($titulo, $contenido, ['include_player_ids' => $playerIds]);
    }
    return false;
}
