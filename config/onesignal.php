<?php

return [
    'app_id' => env('ONESIGNAL_APP_ID', 'YOUR_ONESIGNAL_APP_ID'),
    'rest_api_key' => env('ONESIGNAL_REST_API_KEY', 'YOUR_ONESIGNAL_REST_API_KEY'),
    'user_auth_key' => env('ONESIGNAL_USER_AUTH_KEY', 'YOUR_ONESIGNAL_USER_AUTH_KEY'),
    
    'defaults' => [
        'language' => 'es',
        'timezone' => 'America/Argentina/Buenos_Aires',
        'url' => 'https://onesignal.com/api/v1/notifications'
    ],
    
    'notification_types' => [
        'general' => [
            'name' => 'General',
            'description' => 'Para todos los usuarios',
            'icon' => 'bi-globe',
            'color' => 'primary'
        ],
        'curso' => [
            'name' => 'Por Curso',
            'description' => 'Para alumnos de un curso específico',
            'icon' => 'bi-book',
            'color' => 'success'
        ],
        'familia' => [
            'name' => 'Por Familia',
            'description' => 'Para una familia específica',
            'icon' => 'bi-people',
            'color' => 'warning'
        ],
        'personalizada' => [
            'name' => 'Personalizada',
            'description' => 'Seleccionar destinatarios específicos',
            'icon' => 'bi-person',
            'color' => 'info'
        ]
    ],
    
    'templates' => [
        'welcome' => [
            'title' => '¡Bienvenido al Instituto de Inglés!',
            'content' => 'Gracias por registrarte. Estamos emocionados de tenerte con nosotros.'
        ],
        'course_start' => [
            'title' => 'Inicio de Curso',
            'content' => 'Tu curso comienza mañana. ¡No olvides traer tu material!'
        ],
        'reminder' => [
            'title' => 'Recordatorio de Clase',
            'content' => 'Tienes clase en 1 hora. ¡Nos vemos pronto!'
        ],
        'announcement' => [
            'title' => 'Anuncio Importante',
            'content' => 'Tenemos un anuncio importante para ti.'
        ]
    ]
]; 