<?php

namespace App\Core;

class Config
{
    private static $config = [];
    
    public static function load()
    {
        // Cargar configuración de entorno
        if (file_exists(__DIR__ . '/../../config/env.php')) {
            require_once __DIR__ . '/../../config/env.php';
        }
        
        // Cargar configuración de base de datos
        if (file_exists(__DIR__ . '/../../config/database.php')) {
            self::$config['database'] = require __DIR__ . '/../../config/database.php';
        }
        
        // Cargar configuración de OneSignal
        if (file_exists(__DIR__ . '/../../config/onesignal.php')) {
            self::$config['onesignal'] = require __DIR__ . '/../../config/onesignal.php';
        }
        
        // Cargar configuración de la aplicación
        if (file_exists(__DIR__ . '/../../config/app.php')) {
            self::$config['app'] = require __DIR__ . '/../../config/app.php';
        }
    }
    
    public static function get($key, $default = null)
    {
        $keys = explode('.', $key);
        $config = self::$config;
        
        foreach ($keys as $segment) {
            if (!isset($config[$segment])) {
                return $default;
            }
            $config = $config[$segment];
        }
        
        return $config;
    }
    
    public static function set($key, $value)
    {
        $keys = explode('.', $key);
        $config = &self::$config;
        
        foreach ($keys as $segment) {
            if (!isset($config[$segment])) {
                $config[$segment] = [];
            }
            $config = &$config[$segment];
        }
        
        $config = $value;
    }
    
    public static function has($key)
    {
        return self::get($key) !== null;
    }
    
    public static function all()
    {
        return self::$config;
    }
    
    public static function env($key, $default = null)
    {
        return defined($key) ? constant($key) : $default;
    }
} 