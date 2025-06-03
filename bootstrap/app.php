<?php

if (!defined('BASE_PATH')) {
    define('BASE_PATH', dirname(__DIR__));
}

require_once BASE_PATH . '/App/Core/DotEnv.php';
require_once BASE_PATH . '/App/Core/Database.php';


// Optional: Composer autoload (only if you're using it)
// $autoload = BASE_PATH . '/vendor/autoload.php';
// if (file_exists($autoload)) {
//     require_once $autoload;
// }

// Define env() function
if (!function_exists('env')) {
    function env($key, $default = null)
    {
        return $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;
    }
}

// Load .env
require_once BASE_PATH . '/App/Core/DotEnv.php';
\App\Core\DotEnv::load(BASE_PATH);

// Error reporting
if (env('APP_ENV') === 'development') {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Timezone
date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

// Session configuration
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', env('APP_ENV') === 'production' ? 1 : 0);
ini_set('session.use_strict_mode', 1);

// Security headers
if (env('APP_ENV') === 'production') {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: DENY');
    header('X-XSS-Protection: 1; mode=block');
    header('Strict-Transport-Security: max-age=31536000; includeSubDomains');
}

// Create necessary directories
$directories = [
    BASE_PATH . '/public/uploads',
    BASE_PATH . '/public/uploads/properties',
    BASE_PATH . '/public/uploads/documents',
    BASE_PATH . '/public/uploads/biometric',
    BASE_PATH . '/logs'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Upload limits
ini_set('upload_max_filesize', '5M');
ini_set('post_max_size', '50M');
ini_set('max_file_uploads', 10);
