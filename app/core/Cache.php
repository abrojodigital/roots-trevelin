<?php

namespace App\Core;

class Cache
{
    private static $instance = null;
    private $cacheDir;
    private $defaultTtl = 3600; // 1 hora por defecto
    
    private function __construct()
    {
        $this->cacheDir = __DIR__ . '/../../cache/';
        
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }
    
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Obtener valor del caché
     */
    public function get($key)
    {
        $filename = $this->getCacheFilename($key);
        
        if (!file_exists($filename)) {
            return null;
        }
        
        $data = file_get_contents($filename);
        $cached = json_decode($data, true);
        
        if (!$cached) {
            return null;
        }
        
        // Verificar si ha expirado
        if (time() > $cached['expires']) {
            $this->delete($key);
            return null;
        }
        
        return $cached['data'];
    }
    
    /**
     * Guardar valor en caché
     */
    public function set($key, $data, $ttl = null)
    {
        $ttl = $ttl ?? $this->defaultTtl;
        $filename = $this->getCacheFilename($key);
        
        $cached = [
            'data' => $data,
            'expires' => time() + $ttl,
            'created' => time()
        ];
        
        $content = json_encode($cached);
        
        return file_put_contents($filename, $content, LOCK_EX) !== false;
    }
    
    /**
     * Eliminar valor del caché
     */
    public function delete($key)
    {
        $filename = $this->getCacheFilename($key);
        
        if (file_exists($filename)) {
            return unlink($filename);
        }
        
        return true;
    }
    
    /**
     * Verificar si existe en caché
     */
    public function exists($key)
    {
        return $this->get($key) !== null;
    }
    
    /**
     * Obtener o establecer valor (método helper)
     */
    public function remember($key, $callback, $ttl = null)
    {
        $value = $this->get($key);
        
        if ($value !== null) {
            return $value;
        }
        
        $value = $callback();
        $this->set($key, $value, $ttl);
        
        return $value;
    }
    
    /**
     * Limpiar todo el caché
     */
    public function clear()
    {
        $files = glob($this->cacheDir . '*.cache');
        
        foreach ($files as $file) {
            unlink($file);
        }
        
        return true;
    }
    
    /**
     * Limpiar caché expirado
     */
    public function clearExpired()
    {
        $files = glob($this->cacheDir . '*.cache');
        $cleared = 0;
        
        foreach ($files as $file) {
            $data = file_get_contents($file);
            $cached = json_decode($data, true);
            
            if ($cached && time() > $cached['expires']) {
                unlink($file);
                $cleared++;
            }
        }
        
        return $cleared;
    }
    
    /**
     * Obtener estadísticas del caché
     */
    public function getStats()
    {
        $files = glob($this->cacheDir . '*.cache');
        $totalFiles = count($files);
        $totalSize = 0;
        $expiredFiles = 0;
        $validFiles = 0;
        
        foreach ($files as $file) {
            $totalSize += filesize($file);
            
            $data = file_get_contents($file);
            $cached = json_decode($data, true);
            
            if ($cached && time() > $cached['expires']) {
                $expiredFiles++;
            } else {
                $validFiles++;
            }
        }
        
        return [
            'total_files' => $totalFiles,
            'valid_files' => $validFiles,
            'expired_files' => $expiredFiles,
            'total_size' => $totalSize,
            'cache_dir' => $this->cacheDir
        ];
    }
    
    /**
     * Obtener nombre de archivo de caché
     */
    private function getCacheFilename($key)
    {
        return $this->cacheDir . md5($key) . '.cache';
    }
    
    /**
     * Caché para consultas de base de datos
     */
    public function query($sql, $params = [], $ttl = null)
    {
        $key = 'query_' . md5($sql . serialize($params));
        
        return $this->remember($key, function() use ($sql, $params) {
            $db = Database::getInstance();
            return $db->query($sql, $params);
        }, $ttl);
    }
    
    /**
     * Caché para modelos
     */
    public function model($model, $method, $params = [], $ttl = null)
    {
        $key = 'model_' . $model . '_' . $method . '_' . md5(serialize($params));
        
        return $this->remember($key, function() use ($model, $method, $params) {
            $modelInstance = new $model();
            return call_user_func_array([$modelInstance, $method], $params);
        }, $ttl);
    }
    
    /**
     * Invalidar caché por patrón
     */
    public function invalidatePattern($pattern)
    {
        $files = glob($this->cacheDir . '*' . $pattern . '*.cache');
        
        foreach ($files as $file) {
            unlink($file);
        }
        
        return count($files);
    }
    
    /**
     * Caché para vistas
     */
    public function view($view, $data = [], $ttl = null)
    {
        $key = 'view_' . md5($view . serialize($data));
        
        return $this->remember($key, function() use ($view, $data) {
            ob_start();
            extract($data);
            include __DIR__ . '/../views/' . $view . '.php';
            return ob_get_clean();
        }, $ttl);
    }
    
    /**
     * Caché para API responses
     */
    public function api($endpoint, $params = [], $ttl = null)
    {
        $key = 'api_' . md5($endpoint . serialize($params));
        
        return $this->remember($key, function() use ($endpoint, $params) {
            // Simular respuesta de API
            return [
                'endpoint' => $endpoint,
                'params' => $params,
                'cached_at' => date('Y-m-d H:i:s')
            ];
        }, $ttl);
    }
    
    /**
     * Caché para estadísticas
     */
    public function stats($type, $params = [], $ttl = null)
    {
        $key = 'stats_' . $type . '_' . md5(serialize($params));
        
        return $this->remember($key, function() use ($type, $params) {
            // Aquí se calcularían las estadísticas reales
            return [
                'type' => $type,
                'params' => $params,
                'calculated_at' => date('Y-m-d H:i:s')
            ];
        }, $ttl);
    }
    
    /**
     * Caché para configuraciones
     */
    public function config($key, $ttl = null)
    {
        $cacheKey = 'config_' . $key;
        
        return $this->remember($cacheKey, function() use ($key) {
            // Cargar configuración desde archivo o base de datos
            $configFile = __DIR__ . '/../../config/' . $key . '.php';
            
            if (file_exists($configFile)) {
                return include $configFile;
            }
            
            return null;
        }, $ttl);
    }
    
    /**
     * Caché para sesiones
     */
    public function session($key, $data, $ttl = null)
    {
        $cacheKey = 'session_' . session_id() . '_' . $key;
        return $this->set($cacheKey, $data, $ttl);
    }
    
    public function getSession($key)
    {
        $cacheKey = 'session_' . session_id() . '_' . $key;
        return $this->get($cacheKey);
    }
    
    /**
     * Caché para archivos
     */
    public function file($filepath, $ttl = null)
    {
        $key = 'file_' . md5($filepath);
        
        return $this->remember($key, function() use ($filepath) {
            if (file_exists($filepath)) {
                return file_get_contents($filepath);
            }
            return null;
        }, $ttl);
    }
    
    /**
     * Caché para URLs externas
     */
    public function url($url, $ttl = null)
    {
        $key = 'url_' . md5($url);
        
        return $this->remember($key, function() use ($url) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            $response = curl_exec($ch);
            curl_close($ch);
            
            return $response;
        }, $ttl);
    }
} 