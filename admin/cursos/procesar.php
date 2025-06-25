<?php
require_once '../../config/db.php';
require_once '../../auth/check_auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $accion = $_POST['accion'] ?? '';
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $nivel = $_POST['nivel'] ?? '';
    $horario = $_POST['horario'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $alumnos = $_POST['alumnos'] ?? [];

    if ($accion === 'crear') {
        $stmt = $pdo->prepare("INSERT INTO cursos (nombre, descripcion, nivel, horario, precio) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$nombre, $descripcion, $nivel, $horario, $precio]);
        $cursoId = $pdo->lastInsertId();

        if (!empty($alumnos)) {
            $insertAlumnos = $pdo->prepare("INSERT INTO cursos_alumnos (id_curso, id_alumno) VALUES (?, ?)");
            foreach ($alumnos as $alumnoId) {
                $insertAlumnos->execute([$cursoId, $alumnoId]);
            }
        }

    } elseif ($accion === 'editar') {
        $id = $_POST['id'] ?? null;
        if ($id) {
            $stmt = $pdo->prepare("UPDATE cursos SET nombre = ?, descripcion = ?, nivel = ?, horario = ?, precio = ? WHERE id = ?");
            $stmt->execute([$nombre, $descripcion, $nivel, $horario, $precio, $id]);

            $pdo->prepare("DELETE FROM cursos_alumnos WHERE id_curso = ?")->execute([$id]);
            if (!empty($alumnos)) {
                $insertAlumnos = $pdo->prepare("INSERT INTO cursos_alumnos (id_curso, id_alumno) VALUES (?, ?)");
                foreach ($alumnos as $alumnoId) {
                    $insertAlumnos->execute([$id, $alumnoId]);
                }
            }
        }
    }
}

header('Location: index.php');
exit;

