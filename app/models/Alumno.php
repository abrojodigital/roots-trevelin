<?php

namespace App\Models;

use App\Core\Model;

class Alumno extends Model
{
    protected $table = 'alumnos';

    public function getWithUsuario()
    {
        $sql = "SELECT a.*, u.nombre as usuario_nombre, u.apellido as usuario_apellido
                FROM {$this->table} a
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                ORDER BY a.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getWithUsuarioAndCursos($id)
    {
        $sql = "SELECT a.*, u.nombre as usuario_nombre, u.apellido as usuario_apellido,
                       GROUP_CONCAT(c.nombre SEPARATOR ', ') as cursos
                FROM {$this->table} a
                LEFT JOIN usuarios u ON a.usuario_id = u.id
                LEFT JOIN alumnos_cursos ac ON a.id = ac.alumno_id
                LEFT JOIN cursos c ON ac.curso_id = c.id
                WHERE a.id = ?
                GROUP BY a.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function searchByName($searchTerm)
    {
        return $this->search($searchTerm, ['nombre', 'apellido']);
    }

    public function getByUsuario($usuarioId)
    {
        return $this->findWhere(['usuario_id' => $usuarioId]);
    }

    public function assignToCurso($alumnoId, $cursoId)
    {
        $sql = "INSERT INTO alumnos_cursos (alumno_id, curso_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$alumnoId, $cursoId]);
    }

    public function removeFromCurso($alumnoId, $cursoId)
    {
        $sql = "DELETE FROM alumnos_cursos WHERE alumno_id = ? AND curso_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$alumnoId, $cursoId]);
    }

    public function getCursos($alumnoId)
    {
        $sql = "SELECT c.* FROM cursos c
                INNER JOIN alumnos_cursos ac ON c.id = ac.curso_id
                WHERE ac.alumno_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$alumnoId]);
        return $stmt->fetchAll();
    }
} 