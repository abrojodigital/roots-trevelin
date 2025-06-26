<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Usuario;

class UsuarioController extends Controller
{
    private $usuarioModel;

    public function __construct()
    {
        parent::__construct();
        
        // Verificar autenticación
        if (!$this->getSession('admin')) {
            $this->redirect('/login');
        }
        
        $this->usuarioModel = new Usuario();
    }

    public function index()
    {
        $search = $this->getQueryParams()['buscar'] ?? '';
        
        if (!empty($search)) {
            $usuarios = $this->usuarioModel->searchByName($search);
        } else {
            $usuarios = $this->usuarioModel->findAll();
        }

        return $this->renderWithLayout('usuarios/index', [
            'usuarios' => $usuarios,
            'search' => $search
        ]);
    }

    public function create()
    {
        $errores = $this->getSession('errores', []);
        $this->unsetSession('errores');

        return $this->renderWithLayout('usuarios/create', [
            'errores' => $errores
        ]);
    }

    public function store()
    {
        if (!$this->isPost()) {
            $this->redirect('/usuarios');
        }

        $data = $this->getPostData();
        $errores = $this->validarDatos($data);

        if (!empty($errores)) {
            $this->setSession('errores', $errores);
            $this->redirect('/usuarios/create');
        }

        try {
            // Desvincular player_id si ya está en otro usuario
            if (!empty($data['onesignal_player_id'])) {
                $this->usuarioModel->unlinkOneSignalPlayerId($data['onesignal_player_id']);
            }

            $this->usuarioModel->createWithPassword($data);
            $this->setSession('success', 'Usuario creado exitosamente');
            $this->redirect('/usuarios');
        } catch (\Exception $e) {
            $this->setSession('errores', ['Error de base de datos: ' . $e->getMessage()]);
            $this->redirect('/usuarios/create');
        }
    }

    public function edit($id)
    {
        $usuario = $this->usuarioModel->findById($id);
        
        if (!$usuario) {
            $this->redirect('/usuarios');
        }

        $errores = $this->getSession('errores', []);
        $this->unsetSession('errores');

        return $this->renderWithLayout('usuarios/edit', [
            'usuario' => $usuario,
            'errores' => $errores
        ]);
    }

    public function update($id)
    {
        if (!$this->isPost()) {
            $this->redirect('/usuarios');
        }

        $data = $this->getPostData();
        $errores = $this->validarDatos($data);

        if (!empty($errores)) {
            $this->setSession('errores', $errores);
            $this->redirect("/usuarios/edit/{$id}");
        }

        try {
            // Desvincular player_id si ya está en otro usuario
            if (!empty($data['onesignal_player_id'])) {
                $this->usuarioModel->unlinkOneSignalPlayerId($data['onesignal_player_id']);
            }

            $this->usuarioModel->updateWithPassword($id, $data);
            $this->setSession('success', 'Usuario actualizado exitosamente');
            $this->redirect('/usuarios');
        } catch (\Exception $e) {
            $this->setSession('errores', ['Error de base de datos: ' . $e->getMessage()]);
            $this->redirect("/usuarios/edit/{$id}");
        }
    }

    public function delete($id)
    {
        try {
            $this->usuarioModel->delete($id);
            $this->setSession('success', 'Usuario eliminado exitosamente');
        } catch (\Exception $e) {
            $this->setSession('error', 'Error al eliminar usuario');
        }
        
        $this->redirect('/usuarios');
    }

    private function validarDatos($data)
    {
        $errores = [];

        if (empty($data['nombre'])) {
            $errores[] = "El nombre es obligatorio.";
        }
        if (empty($data['apellido'])) {
            $errores[] = "El apellido es obligatorio.";
        }
        if (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errores[] = "Email no válido.";
        }

        return $errores;
    }
} 