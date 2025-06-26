<?php

// Configuración de la Aplicación
define('APP_NAME', 'Instituto de Inglés Roots');
define('APP_ENV', 'production');
define('APP_DEBUG', false);
define('APP_URL', 'http://localhost');

// Configuración de Base de Datos
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_DATABASE', 'u102612746_roots');
define('DB_USERNAME', 'u102612746_admin');
define('DB_PASSWORD', 'Trevelin#9203');

// Configuración de OneSignal
// IMPORTANTE: Reemplazar con tus credenciales reales de OneSignal
define('ONESIGNAL_APP_ID', 'YOUR_ONESIGNAL_APP_ID');
define('ONESIGNAL_REST_API_KEY', 'YOUR_ONESIGNAL_REST_API_KEY');
define('ONESIGNAL_USER_AUTH_KEY', 'YOUR_ONESIGNAL_USER_AUTH_KEY');

// Configuración de Redis (Opcional)
define('REDIS_HOST', '127.0.0.1');
define('REDIS_PASSWORD', null);
define('REDIS_PORT', '6379');
define('REDIS_DB', '0');
define('REDIS_CACHE_DB', '1');

// Configuración de Email
define('MAIL_MAILER', 'smtp');
define('MAIL_HOST', 'smtp.mailgun.org');
define('MAIL_PORT', '587');
define('MAIL_USERNAME', null);
define('MAIL_PASSWORD', null);
define('MAIL_ENCRYPTION', 'tls');
define('MAIL_FROM_ADDRESS', 'noreply@instituto-ingles.com');
define('MAIL_FROM_NAME', 'Instituto de Inglés Roots');

// Configuración de Logs
define('LOG_LEVEL', 'debug');

// Configuración de Seguridad
define('SESSION_SECURE_COOKIE', false); 