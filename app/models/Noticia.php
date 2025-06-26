<?php

namespace App\Models;

use App\Core\Model;

class Noticia extends Model
{
    protected $table = 'noticias';

    public function getWithRelations()
    {
        $sql = "SELECT n.*, 
                       COUNT(DISTINCT nu.usuario_id) as total_usuarios,
                       COUNT(DISTINCT na.alumno_id) as total_alumnos,
                       COUNT(DISTINCT nc.curso_id) as total_cursos
                FROM {$this->table} n
                LEFT JOIN noticias_usuarios nu ON n.id = nu.noticia_id
                LEFT JOIN noticias_alumnos na ON n.id = na.noticia_id
                LEFT JOIN noticias_cursos nc ON n.id = nc.noticia_id
                GROUP BY n.id
                ORDER BY n.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function searchByTitle($searchTerm)
    {
        return $this->search($searchTerm, ['titulo', 'contenido']);
    }

    public function assignToUsuario($noticiaId, $usuarioId)
    {
        $sql = "INSERT INTO noticias_usuarios (noticia_id, usuario_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$noticiaId, $usuarioId]);
    }

    public function assignToAlumno($noticiaId, $alumnoId)
    {
        $sql = "INSERT INTO noticias_alumnos (noticia_id, alumno_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$noticiaId, $alumnoId]);
    }

    public function assignToCurso($noticiaId, $cursoId)
    {
        $sql = "INSERT INTO noticias_cursos (noticia_id, curso_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$noticiaId, $cursoId]);
    }

    public function removeFromUsuario($noticiaId, $usuarioId)
    {
        $sql = "DELETE FROM noticias_usuarios WHERE noticia_id = ? AND usuario_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$noticiaId, $usuarioId]);
    }

    public function removeFromAlumno($noticiaId, $alumnoId)
    {
        $sql = "DELETE FROM noticias_alumnos WHERE noticia_id = ? AND alumno_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$noticiaId, $alumnoId]);
    }

    public function removeFromCurso($noticiaId, $cursoId)
    {
        $sql = "DELETE FROM noticias_cursos WHERE noticia_id = ? AND curso_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$noticiaId, $cursoId]);
    }

    public function getUsuarios($noticiaId)
    {
        $sql = "SELECT u.* FROM usuarios u
                INNER JOIN noticias_usuarios nu ON u.id = nu.usuario_id
                WHERE nu.noticia_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$noticiaId]);
        return $stmt->fetchAll();
    }

    public function getAlumnos($noticiaId)
    {
        $sql = "SELECT a.* FROM alumnos a
                INNER JOIN noticias_alumnos na ON a.id = na.alumno_id
                WHERE na.noticia_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$noticiaId]);
        return $stmt->fetchAll();
    }

    public function getCursos($noticiaId)
    {
        $sql = "SELECT c.* FROM cursos c
                INNER JOIN noticias_cursos nc ON c.id = nc.curso_id
                WHERE nc.noticia_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$noticiaId]);
        return $stmt->fetchAll();
    }

    public function getForUsuario($usuarioId)
    {
        $sql = "SELECT DISTINCT n.* FROM noticias n
                INNER JOIN noticias_usuarios nu ON n.id = nu.noticia_id
                WHERE nu.usuario_id = ?
                ORDER BY n.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$usuarioId]);
        return $stmt->fetchAll();
    }

    public function getForAlumno($alumnoId)
    {
        $sql = "SELECT DISTINCT n.* FROM noticias n
                INNER JOIN noticias_alumnos na ON n.id = na.noticia_id
                WHERE na.alumno_id = ?
                ORDER BY n.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$alumnoId]);
        return $stmt->fetchAll();
    }

    public function getForCurso($cursoId)
    {
        $sql = "SELECT DISTINCT n.* FROM noticias n
                INNER JOIN noticias_cursos nc ON n.id = nc.noticia_id
                WHERE nc.curso_id = ?
                ORDER BY n.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cursoId]);
        return $stmt->fetchAll();
    }

    public function getGenerales()
    {
        $sql = "SELECT n.* FROM noticias n
                WHERE n.id NOT IN (
                    SELECT DISTINCT noticia_id FROM noticias_usuarios
                    UNION
                    SELECT DISTINCT noticia_id FROM noticias_alumnos
                    UNION
                    SELECT DISTINCT noticia_id FROM noticias_cursos
                )
                ORDER BY n.fecha_creacion DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }
} 