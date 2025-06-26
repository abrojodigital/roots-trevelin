<?php

namespace App\Core;

class View
{
    private $viewsPath = __DIR__ . '/../views/';

    public function render($view, $data = [])
    {
        // Extraer variables para que estén disponibles en la vista
        extract($data);
        
        // Incluir el archivo de vista
        $viewFile = $this->viewsPath . $view . '.php';
        
        if (!file_exists($viewFile)) {
            throw new \Exception("Vista no encontrada: $view");
        }
        
        // Capturar el contenido de la vista
        ob_start();
        include $viewFile;
        $content = ob_get_clean();
        
        return $content;
    }

    public function renderWithLayout($view, $data = [], $layout = 'default')
    {
        $content = $this->render($view, $data);
        
        // Incluir el layout
        $layoutFile = $this->viewsPath . 'layouts/' . $layout . '.php';
        
        if (!file_exists($layoutFile)) {
            throw new \Exception("Layout no encontrado: $layout");
        }
        
        // Extraer variables para el layout
        extract($data);
        
        // Renderizar el layout con el contenido
        ob_start();
        include $layoutFile;
        return ob_get_clean();
    }

    public function partial($view, $data = [])
    {
        return $this->render('partials/' . $view, $data);
    }
} 