<?php
/**
 * Bootstrap file for the application
 * Loads all necessary components and configurations
 */

 // Start session if not already started
if (php_sapi_name() !== 'cli' && session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Set error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload classes
spl_autoload_register(function ($class) {
    // Convert namespace to file path
    $class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
    $file = BASE_PATH . DIRECTORY_SEPARATOR . $class . '.php';
    
    if (file_exists($file)) {
        require_once $file;
    }
});

// Load environment variables
(new App\Core\DotEnv(BASE_PATH . '/.env'))->load();

// Set up database connection
App\Core\Database::getInstance([
    'host' => getenv('DB_HOST'),
    'database' => getenv('DB_DATABASE'),
    'username' => getenv('DB_USERNAME'),
    'password' => getenv('DB_PASSWORD'),
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]);



// Set default timezone
date_default_timezone_set('UTC');

// Helper functions
require_once BASE_PATH . '/App/helpers.php';
