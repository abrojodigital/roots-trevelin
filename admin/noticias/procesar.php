<?php
require_once __DIR__ . '/../../config/db.php';
require_once __DIR__ . '/../../auth/check_auth.php';
require_once __DIR__ . '/../../notificaciones/enviar_general.php';
require_once __DIR__ . '/../../notificaciones/enviar_familia.php';
require_once __DIR__ . '/../../notificaciones/enviar_curso.php';
require_once __DIR__ . '/../../notificaciones/enviar_alumno.php';

/**
 * Función de logging: guarda errores en un archivo de log.
 */
function log_error($message)
{
    $logFile = __DIR__ . '/../../logs/notificaciones.log';
    $date = date('Y-m-d H:i:s');
    file_put_contents($logFile, "[$date] $message\n", FILE_APPEND);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['accion'] === 'crear') {
    $titulo = $_POST['titulo'] ?? '';
    $contenido = $_POST['contenido'] ?? '';
    $fecha_publicacion = $_POST['fecha_publicacion'] ?? date('Y-m-d');
    $autor = $_POST['autor'] ?? 'Admin';
    $tipo_audiencia = $_POST['tipo_audiencia'] ?? 'general';

    try {
        // Inicia la transacción para insertar la noticia y sus relaciones
        $pdo->beginTransaction();

        // Inserta la noticia
        $stmt = $pdo->prepare("INSERT INTO noticias (titulo, contenido, fecha_publicacion, autor, tipo_audiencia) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$titulo, $contenido, $fecha_publicacion, $autor, $tipo_audiencia]);
        $id_noticia = $pdo->lastInsertId();

        // Inserta las relaciones según el tipo de audiencia
        switch ($tipo_audiencia) {
            case 'alumno':
                if (!empty($_POST['alumnos'])) {
                    $stmt = $pdo->prepare("INSERT INTO noticias_alumnos (id_noticia, id_alumno) VALUES (?, ?)");
                    foreach ($_POST['alumnos'] as $id_alumno) {
                        $stmt->execute([$id_noticia, $id_alumno]);
                    }
                }
                break;
            case 'familia':
                if (!empty($_POST['usuarios'])) {
                    $stmt = $pdo->prepare("INSERT INTO noticias_usuarios (id_noticia, id_usuario) VALUES (?, ?)");
                    foreach ($_POST['usuarios'] as $id_usuario) {
                        $stmt->execute([$id_noticia, $id_usuario]);
                    }
                }
                break;
            case 'curso':
                if (!empty($_POST['cursos'])) {
                    $stmt = $pdo->prepare("INSERT INTO noticias_cursos (id_noticia, id_curso) VALUES (?, ?)");
                    foreach ($_POST['cursos'] as $id_curso) {
                        $stmt->execute([$id_noticia, $id_curso]);
                    }
                }
                break;
                // Para 'general' no es necesaria inserción en tablas intermedias.
        }

        // Se commitea la transacción antes de enviar notificaciones
        $pdo->commit();

        // Enviar respuesta al navegador
        header('Location: index.php?mensaje=creada');

        // Si fastcgi_finish_request() está disponible, se utiliza para finalizar la respuesta
        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        // Ahora se realizan las notificaciones de forma "asincrónica"
        $notificacionEnviada = false;
        try {
            switch ($tipo_audiencia) {
                case 'general':
                    $notificacionEnviada = enviarNoticiaGeneral($titulo, $contenido);
                    break;
                case 'familia':
                    foreach ($_POST['usuarios'] ?? [] as $id_usuario) {
                        if (enviarNoticiaAFamilia($id_usuario, $titulo, $contenido)) {
                            $notificacionEnviada = true;
                        }
                    }
                    break;
                case 'curso':
                    foreach ($_POST['cursos'] ?? [] as $id_curso) {
                        if (enviarNoticiaACurso($id_curso, $titulo, $contenido)) {
                            $notificacionEnviada = true;
                        }
                    }
                    break;
                case 'alumno':
                    foreach ($_POST['alumnos'] ?? [] as $id_alumno) {
                        if (enviarNoticiaAAlumno($id_alumno, $titulo, $contenido)) {
                            $notificacionEnviada = true;
                        }
                    }
                    break;
            }
        } catch (Exception $e) {
            log_error("Error en envío de notificaciones para noticia $id_noticia: " . $e->getMessage());
        }

        // Si la notificación fue enviada, marca la noticia como notificada
        if ($notificacionEnviada) {
            try {
                $stmt = $pdo->prepare("UPDATE noticias SET notificada = 1 WHERE id = ?");
                $stmt->execute([$id_noticia]);
            } catch (Exception $e) {
                log_error("Error al actualizar notificada en noticia $id_noticia: " . $e->getMessage());
            }
        }

        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<div class='alert alert-danger'>Error al guardar la noticia: " . htmlspecialchars($e->getMessage()) . "</div>";
    }
} else {
    header('Location: index.php');
    exit;
}
