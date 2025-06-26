<?php

namespace App\Models;

use App\Core\Model;

class Curso extends Model
{
    protected $table = 'cursos';

    public function getWithAlumnos()
    {
        $sql = "SELECT c.*, COUNT(ac.alumno_id) as total_alumnos
                FROM {$this->table} c
                LEFT JOIN alumnos_cursos ac ON c.id = ac.curso_id
                GROUP BY c.id
                ORDER BY c.id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getWithAlumnosDetail($id)
    {
        $sql = "SELECT c.*, GROUP_CONCAT(a.nombre SEPARATOR ', ') as alumnos
                FROM {$this->table} c
                LEFT JOIN alumnos_cursos ac ON c.id = ac.curso_id
                LEFT JOIN alumnos a ON ac.alumno_id = a.id
                WHERE c.id = ?
                GROUP BY c.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function searchByName($searchTerm)
    {
        return $this->search($searchTerm, ['nombre']);
    }

    public function getAlumnos($cursoId)
    {
        $sql = "SELECT a.* FROM alumnos a
                INNER JOIN alumnos_cursos ac ON a.id = ac.alumno_id
                WHERE ac.curso_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cursoId]);
        return $stmt->fetchAll();
    }

    public function addAlumno($cursoId, $alumnoId)
    {
        $sql = "INSERT INTO alumnos_cursos (curso_id, alumno_id) VALUES (?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cursoId, $alumnoId]);
    }

    public function removeAlumno($cursoId, $alumnoId)
    {
        $sql = "DELETE FROM alumnos_cursos WHERE curso_id = ? AND alumno_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$cursoId, $alumnoId]);
    }

    public function getAvailableAlumnos($cursoId)
    {
        $sql = "SELECT a.* FROM alumnos a
                WHERE a.id NOT IN (
                    SELECT ac.alumno_id FROM alumnos_cursos ac WHERE ac.curso_id = ?
                )";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$cursoId]);
        return $stmt->fetchAll();
    }
} 