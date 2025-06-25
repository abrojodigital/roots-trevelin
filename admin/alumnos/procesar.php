<?php
session_start();
require_once __DIR__ . '/../../config/db.php';

function subirFoto($file) {
    // Directorio de destino para las imágenes (asegúrate de que exista y tenga permisos de escritura)
    $destino = __DIR__ . '/../../assets/img/alumnos/';
    if (!is_dir($destino)) {
        mkdir($destino, 0755, true);
    }
    
    // Validar si hubo error en la subida
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    
    // Generar un nombre único para el archivo y preservar la extensión
    $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
    $nombreArchivo = uniqid('alumno_', true) . '.' . strtolower($extension);
    
    // Mover el archivo a la carpeta destino
    if (move_uploaded_file($file['tmp_name'], $destino . $nombreArchivo)) {
        return $nombreArchivo;
    }
    
    return null;
}

$accion = $_POST['accion'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = $_POST['nombre'] ?? '';
    $apellido = $_POST['apellido'] ?? '';
    $fecha_nacimiento = $_POST['fecha_nacimiento'] ?? null;
    $email = $_POST['email'] ?? null;
    $telefono = $_POST['telefono'] ?? null;
    $id_usuario = $_POST['id_usuario'] !== '' ? $_POST['id_usuario'] : null;
    
    // Procesar la foto si se subió
    $foto = null;
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] !== UPLOAD_ERR_NO_FILE) {
        $foto = subirFoto($_FILES['foto']);
    }
    
    if ($accion === 'crear') {
        $stmt = $pdo->prepare("INSERT INTO alumnos (nombre, apellido, fecha_nacimiento, email, telefono, id_usuario, foto) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $email, $telefono, $id_usuario, $foto]);
        header("Location: index.php?mensaje=Alumno creado");
        exit;
    } elseif ($accion === 'editar') {
        $id = $_POST['id'] ?? null;
        if ($id) {
            if ($foto) {
                // Si se subió nueva foto, se actualiza el campo
                $stmt = $pdo->prepare("UPDATE alumnos SET nombre=?, apellido=?, fecha_nacimiento=?, email=?, telefono=?, id_usuario=?, foto=? WHERE id=?");
                $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $email, $telefono, $id_usuario, $foto, $id]);
            } else {
                // Si no se subió foto, se mantiene la existente
                $stmt = $pdo->prepare("UPDATE alumnos SET nombre=?, apellido=?, fecha_nacimiento=?, email=?, telefono=?, id_usuario=? WHERE id=?");
                $stmt->execute([$nombre, $apellido, $fecha_nacimiento, $email, $telefono, $id_usuario, $id]);
            }
            header("Location: index.php?mensaje=Alumno actualizado");
            exit;
        }
    }
}

header("Location: index.php?error=proceso");
exit;
