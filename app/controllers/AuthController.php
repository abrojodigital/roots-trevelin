<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Administrador;

class AuthController extends Controller
{
    private $adminModel;

    public function __construct()
    {
        parent::__construct();
        $this->adminModel = new Administrador();
    }

    public function login()
    {
        if ($this->isPost()) {
            $email = $this->getPostData()['email'] ?? '';
            $password = $this->getPostData()['password'] ?? '';

            $admin = $this->adminModel->authenticate($email, $password);

            if ($admin) {
                $this->setSession('admin', $admin);
                $this->redirect('/admin/dashboard');
            } else {
                $this->setSession('error', 'Credenciales inválidas');
                $this->redirect('/login');
            }
        }

        $error = $this->getSession('error');
        $this->unsetSession('error');

        return $this->renderWithLayout('auth/login', [
            'error' => $error
        ]);
    }

    public function logout()
    {
        session_destroy();
        $this->redirect('/');
    }

    public function index()
    {
        if ($this->getSession('admin')) {
            $this->redirect('/admin/dashboard');
        }
        
        return $this->renderWithLayout('auth/welcome');
    }

    public function welcome()
    {
        if ($this->getSession('admin')) {
            $this->redirect('/dashboard');
        }
        
        return $this->renderWithLayout('auth/welcome');
    }

    public function showLogin()
    {
        if ($this->getSession('admin')) {
            $this->redirect('/dashboard');
        }
        
        $error = $this->getSession('error');
        $this->unsetSession('error');
        
        return $this->renderWithLayout('auth/login', [
            'error' => $error
        ]);
    }
} 