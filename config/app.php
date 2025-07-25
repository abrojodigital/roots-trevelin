<?php

return [
    'name' => 'Instituto de Inglés Roots',
    'version' => '2.0.0',
    'environment' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'America/Argentina/Buenos_Aires',
    'locale' => 'es',
    'fallback_locale' => 'en',
    
    'providers' => [
        // Core providers
        'App\Core\Database',
        'App\Core\Cache',
        'App\Core\Logger',
    ],
    
    'middleware' => [
        'auth' => 'App\Middleware\AuthMiddleware',
        'admin' => 'App\Middleware\AdminMiddleware',
        'api' => 'App\Middleware\ApiMiddleware',
    ],
    
    'session' => [
        'driver' => 'file',
        'lifetime' => 120,
        'expire_on_close' => false,
        'encrypt' => false,
        'files' => storage_path('framework/sessions'),
        'connection' => null,
        'table' => 'sessions',
        'store' => null,
        'lottery' => [2, 100],
        'cookie' => 'roots_session',
        'path' => '/',
        'domain' => null,
        'secure' => false,
        'http_only' => true,
        'same_site' => 'lax',
    ],
    
    'cache' => [
        'default' => 'file',
        'stores' => [
            'file' => [
                'driver' => 'file',
                'path' => storage_path('framework/cache/data'),
            ],
        ],
        'prefix' => 'roots_cache',
    ],
    
    'logging' => [
        'default' => 'stack',
        'channels' => [
            'stack' => [
                'driver' => 'stack',
                'channels' => ['single'],
                'ignore_exceptions' => false,
            ],
            'single' => [
                'driver' => 'single',
                'path' => storage_path('logs/roots.log'),
                'level' => env('LOG_LEVEL', 'debug'),
            ],
        ],
    ],
    
    'uploads' => [
        'max_size' => 10240, // 10MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
        'path' => 'uploads/',
    ],
]; 