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
            'redis' => [
                'driver' => 'redis',
                'connection' => 'cache',
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
            'daily' => [
                'driver' => 'daily',
                'path' => storage_path('logs/roots.log'),
                'level' => env('LOG_LEVEL', 'debug'),
                'days' => 14,
            ],
        ],
    ],
    
    'mail' => [
        'default' => env('MAIL_MAILER', 'smtp'),
        'mailers' => [
            'smtp' => [
                'transport' => 'smtp',
                'host' => env('MAIL_HOST', 'smtp.mailgun.org'),
                'port' => env('MAIL_PORT', 587),
                'encryption' => env('MAIL_ENCRYPTION', 'tls'),
                'username' => env('MAIL_USERNAME'),
                'password' => env('MAIL_PASSWORD'),
                'timeout' => null,
                'local_domain' => env('MAIL_EHLO_DOMAIN'),
            ],
        ],
        'from' => [
            'address' => env('MAIL_FROM_ADDRESS', 'noreply@instituto-ingles.com'),
            'name' => env('MAIL_FROM_NAME', 'Instituto de Inglés Roots'),
        ],
    ],
    
    'filesystems' => [
        'default' => env('FILESYSTEM_DISK', 'local'),
        'disks' => [
            'local' => [
                'driver' => 'local',
                'root' => storage_path('app'),
            ],
            'public' => [
                'driver' => 'local',
                'root' => storage_path('app/public'),
                'url' => env('APP_URL').'/storage',
                'visibility' => 'public',
            ],
        ],
    ],
    
    'security' => [
        'csrf' => [
            'enabled' => true,
            'token_name' => '_token',
            'expire' => 60,
        ],
        'password' => [
            'min_length' => 8,
            'require_uppercase' => true,
            'require_lowercase' => true,
            'require_numbers' => true,
            'require_symbols' => false,
        ],
    ],
    
    'pagination' => [
        'default_per_page' => 15,
        'max_per_page' => 100,
    ],
    
    'uploads' => [
        'max_size' => 10240, // 10MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'],
        'path' => 'uploads/',
    ],
]; 