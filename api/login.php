<?php
// login.php
require_once '../config/db.php';
require_once '../auth/check_auth_optional.php'; // No obligatorio pero se puede usar si hay sesión
header('Content-Type: application/json');

$input = json_decode(file_get_contents("php://input"), true);
$email = $input['email'] ?? '';
$contrasena = $input['contrasena'] ?? '';
$player_id = $input['token'] ?? null;

if (!$email || !$contrasena) {
    echo json_encode(['status' => 'error', 'message' => 'Faltan credenciales']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id, nombre, apellido, email, contrasena FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario || !password_verify($contrasena, $usuario['contrasena'])) {
        echo json_encode(['status' => 'error', 'message' => 'Credenciales inválidas']);
        exit;
    }

    if ($player_id) {
        // Desasociar el player_id de cualquier otro usuario que lo tenga
        $stmt = $pdo->prepare("UPDATE usuarios SET onesignal_player_id = NULL WHERE onesignal_player_id = ? AND id != ?");
        $stmt->execute([$player_id, $usuario['id']]);

        // Asignar el player_id al usuario actual
        $stmt = $pdo->prepare("UPDATE usuarios SET onesignal_player_id = ?, onesignal_updated_at = NOW() WHERE id = ?");
        $stmt->execute([$player_id, $usuario['id']]);
    }

    // Obtener alumnos vinculados
    $stmt = $pdo->prepare("
        SELECT a.id AS id_alumno, c.id AS id_curso
        FROM alumnos a
        INNER JOIN cursos_alumnos ca ON a.id = ca.id_alumno
        INNER JOIN cursos c ON c.id = ca.id_curso
        WHERE a.id_usuario = ?
    ");
    $stmt->execute([$usuario['id']]);
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'user' => [
            'id_usuario' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'apellido' => $usuario['apellido'],
            'email' => $usuario['email'],
            'alumnos' => $alumnos
        ]
    ], JSON_UNESCAPED_UNICODE);
} catch (PDOException $e) {
    error_log("[login.php] DB Error: " . $e->getMessage(), 3, '../logs/error.log');
    echo json_encode(['status' => 'error', 'message' => 'Error de servidor']);
}
