<?php

namespace App\Models;

use App\Core\Model;

class Administrador extends Model
{
    protected $table = 'administradores';

    public function findByEmail($email)
    {
        return $this->findBy('email', $email);
    }

    public function authenticate($email, $password)
    {
        $admin = $this->findByEmail($email);
        
        if ($admin && password_verify($password, $admin['contrasena'])) {
            return $admin;
        }
        
        return false;
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
} 