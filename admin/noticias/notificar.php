<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../notificaciones/enviar_general.php';
require_once __DIR__ . '/../../notificaciones/enviar_familia.php';
require_once __DIR__ . '/../../notificaciones/enviar_curso.php';
require_once __DIR__ . '/../../notificaciones/enviar_alumno.php';

if (!isset($_GET['id'])) {
    header('Location: index.php?error=No se especificó ID');
    exit;
}

$id_noticia = $_GET['id'];

// Recupera la noticia
$stmt = $pdo->prepare("SELECT * FROM noticias WHERE id = ?");
$stmt->execute([$id_noticia]);
$noticia = $stmt->fetch();

if (!$noticia) {
    header('Location: index.php?error=Noticia no encontrada');
    exit;
}

$titulo = $noticia['titulo'];
$contenido = $noticia['contenido'];
$tipo_audiencia = $noticia['tipo_audiencia'];

$notificacionEnviada = false;

switch ($tipo_audiencia) {
    case 'general':
        $notificacionEnviada = enviarNoticiaGeneral($titulo, $contenido);
        break;
    case 'familia':
        // Recupera los IDs de usuarios asociados a la noticia
        $stmt = $pdo->prepare("SELECT id_usuario FROM noticias_usuarios WHERE id_noticia = ?");
        $stmt->execute([$id_noticia]);
        $usuarios = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (!empty($usuarios)) {
            foreach ($usuarios as $id_usuario) {
                if (enviarNoticiaAFamilia($id_usuario, $titulo, $contenido)) {
                    $notificacionEnviada = true;
                }
            }
        }
        break;
    case 'curso':
        // Recupera los IDs de cursos asociados
        $stmt = $pdo->prepare("SELECT id_curso FROM noticias_cursos WHERE id_noticia = ?");
        $stmt->execute([$id_noticia]);
        $cursos = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (!empty($cursos)) {
            foreach ($cursos as $id_curso) {
                if (enviarNoticiaACurso($id_curso, $titulo, $contenido)) {
                    $notificacionEnviada = true;
                }
            }
        }
        break;
    case 'alumno':
        // Recupera los IDs de alumnos asociados
        $stmt = $pdo->prepare("SELECT id_alumno FROM noticias_alumnos WHERE id_noticia = ?");
        $stmt->execute([$id_noticia]);
        $alumnos = $stmt->fetchAll(PDO::FETCH_COLUMN);
        if (!empty($alumnos)) {
            foreach ($alumnos as $id_alumno) {
                if (enviarNoticiaAAlumno($id_alumno, $titulo, $contenido)) {
                    $notificacionEnviada = true;
                }
            }
        }
        break;
}

// Si se envió al menos una notificación, marca la noticia como notificada
if ($notificacionEnviada) {
    $stmt = $pdo->prepare("UPDATE noticias SET notificada = 1 WHERE id = ?");
    $stmt->execute([$id_noticia]);
    header('Location: index.php?mensaje=Notificaci\u00f3n enviada');
} else {
    header('Location: index.php?mensaje=No se pudo enviar la notificaci\u00f3n');
}
exit;
