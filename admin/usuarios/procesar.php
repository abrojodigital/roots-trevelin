<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

function validarDatos($data)
{
    $errores = [];

    if (empty($data['nombre'])) $errores[] = "El nombre es obligatorio.";
    if (empty($data['apellido'])) $errores[] = "El apellido es obligatorio.";
    if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
        $errores[] = "Email no válido.";
    }

    return $errores;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $datos = [
        'nombre' => trim($_POST['nombre']),
        'apellido' => trim($_POST['apellido']),
        'email' => trim($_POST['email']),
        'telefono' => trim($_POST['telefono']),
        'direccion' => trim($_POST['direccion'])
    ];
    $playerId = $_POST['onesignal_player_id'] ?? null;

    $errores = validarDatos($datos);

    if (!empty($errores)) {
        $_SESSION['errores'] = $errores;
        header("Location: crear.php");
        exit;
    }

    try {
        if ($accion === 'crear') {
            // Desvincular el player_id si ya está en otro usuario
            if ($playerId) {
                $desvincular = $pdo->prepare("UPDATE usuarios SET onesignal_player_id = NULL WHERE onesignal_player_id = ?");
                $desvincular->execute([$playerId]);
            }

            $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellido, email, telefono, direccion, contrasena, onesignal_player_id, onesignal_updated_at)
                                   VALUES (?, ?, ?, ?, ?, ?, ?, NOW())");
            $hash = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
            $stmt->execute([
                $_POST['nombre'],
                $_POST['apellido'],
                $_POST['email'],
                $_POST['telefono'],
                $_POST['direccion'],
                $hash,
                $playerId
            ]);
        } elseif ($accion === 'editar') {
            $id = $_POST['id'];

            // Desvincular el player_id si ya está en otro usuario
            if ($playerId) {
                $desvincular = $pdo->prepare("UPDATE usuarios SET onesignal_player_id = NULL WHERE onesignal_player_id = ? AND id != ?");
                $desvincular->execute([$playerId, $id]);
            }

            if (!empty($_POST['contrasena'])) {
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?, apellido=?, email=?, telefono=?, direccion=?, contrasena=?, onesignal_player_id=?, onesignal_updated_at=NOW() WHERE id=?");
                $hash = password_hash($_POST['contrasena'], PASSWORD_DEFAULT);
                $stmt->execute([
                    $_POST['nombre'],
                    $_POST['apellido'],
                    $_POST['email'],
                    $_POST['telefono'],
                    $_POST['direccion'],
                    $hash,
                    $playerId,
                    $id
                ]);
            } else {
                $stmt = $pdo->prepare("UPDATE usuarios SET nombre=?, apellido=?, email=?, telefono=?, direccion=?, onesignal_player_id=?, onesignal_updated_at=NOW() WHERE id=?");
                $stmt->execute([
                    $_POST['nombre'],
                    $_POST['apellido'],
                    $_POST['email'],
                    $_POST['telefono'],
                    $_POST['direccion'],
                    $playerId,
                    $id
                ]);
            }
        }

        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['errores'] = ["Error de base de datos: " . $e->getMessage()];
        header("Location: crear.php");
        exit;
    }
}
