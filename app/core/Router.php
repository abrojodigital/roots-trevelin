<?php

namespace App\Core;

class Router
{
    private $routes = [];
    private $notFoundCallback;

    public function get($path, $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post($path, $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    public function put($path, $callback)
    {
        $this->routes['PUT'][$path] = $callback;
    }

    public function delete($path, $callback)
    {
        $this->routes['DELETE'][$path] = $callback;
    }

    public function notFound($callback)
    {
        $this->notFoundCallback = $callback;
    }

    public function resolve()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
        // Remover la base URL si existe
        $basePath = dirname($_SERVER['SCRIPT_NAME']);
        if ($basePath !== '/') {
            $path = str_replace($basePath, '', $path);
        }
        
        // Si la ruta está vacía, usar '/'
        if (empty($path)) {
            $path = '/';
        }

        // Buscar la ruta exacta
        if (isset($this->routes[$method][$path])) {
            $callback = $this->routes[$method][$path];
            return $this->executeCallback($callback);
        }

        // Buscar rutas con parámetros
        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            $pattern = $this->convertRouteToRegex($route);
            if (preg_match($pattern, $path, $matches)) {
                array_shift($matches); // Remover el match completo
                return $this->executeCallback($callback, $matches);
            }
        }

        // Ruta no encontrada
        if ($this->notFoundCallback) {
            return $this->executeCallback($this->notFoundCallback);
        }

        http_response_code(404);
        echo "404 - Página no encontrada";
    }

    private function convertRouteToRegex($route)
    {
        // Convertir parámetros como {id} a regex
        $pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
        return '#^' . $pattern . '$#';
    }

    private function executeCallback($callback, $params = [])
    {
        if (is_callable($callback)) {
            return call_user_func_array($callback, $params);
        }

        if (is_string($callback)) {
            $parts = explode('@', $callback);
            if (count($parts) === 2) {
                $controllerClass = 'App\\Controllers\\' . $parts[0];
                $method = $parts[1];

                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    if (method_exists($controller, $method)) {
                        return call_user_func_array([$controller, $method], $params);
                    }
                }
            }
        }

        throw new \Exception("Callback inválido");
    }
} 