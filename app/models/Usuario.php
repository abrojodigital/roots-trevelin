<?php

namespace App\Models;

use App\Core\Model;

class Usuario extends Model
{
    protected $table = 'usuarios';

    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }

    public function createWithPassword($data)
    {
        if (isset($data['contrasena'])) {
            $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);
        }
        return $this->create($data);
    }

    public function updateWithPassword($id, $data)
    {
        if (isset($data['contrasena']) && !empty($data['contrasena'])) {
            $data['contrasena'] = password_hash($data['contrasena'], PASSWORD_DEFAULT);
        } else {
            unset($data['contrasena']);
        }
        return $this->update($id, $data);
    }

    public function searchByName($searchTerm)
    {
        return $this->search($searchTerm, ['nombre', 'apellido']);
    }

    public function updateOneSignalPlayerId($id, $playerId)
    {
        $data = [
            'onesignal_player_id' => $playerId,
            'onesignal_updated_at' => date('Y-m-d H:i:s')
        ];
        return $this->update($id, $data);
    }

    public function unlinkOneSignalPlayerId($playerId)
    {
        $sql = "UPDATE {$this->table} SET onesignal_player_id = NULL WHERE onesignal_player_id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$playerId]);
    }

    public function getWithAlumnos($id)
    {
        $sql = "SELECT u.*, GROUP_CONCAT(a.nombre SEPARATOR ', ') as alumnos
                FROM {$this->table} u
                LEFT JOIN alumnos a ON u.id = a.usuario_id
                WHERE u.id = ?
                GROUP BY u.id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
} 