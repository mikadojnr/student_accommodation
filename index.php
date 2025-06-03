<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Debug mode
define('DEBUG', true);

// Add error and exception handlers for debugging
if (DEBUG) {
    set_error_handler(function($errno, $errstr, $errfile, $errline) {
        $error_type = match($errno) {
            E_ERROR => 'Error',
            E_WARNING => 'Warning',
            E_PARSE => 'Parse Error',
            E_NOTICE => 'Notice',
            E_CORE_ERROR => 'Core Error',
            E_CORE_WARNING => 'Core Warning',
            E_COMPILE_ERROR => 'Compile Error',
            E_COMPILE_WARNING => 'Compile Warning',
            E_USER_ERROR => 'User Error',
            E_USER_WARNING => 'User Warning',
            E_USER_NOTICE => 'User Notice',
            default => "Unknown Error ($errno)"
        };
        
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 10px; border: 1px solid #f5c6cb; border-radius: 4px;'>";
        echo "<h3 style='margin-top: 0;'>$error_type</h3>";
        echo "<p><strong>Message:</strong> $errstr</p>";
        echo "<p><strong>File:</strong> $errfile</p>";
        echo "<p><strong>Line:</strong> $errline</p>";
        echo "</div>";
        
        // Log the error
        error_log("$error_type: $errstr in $errfile on line $errline");
        
        // Don't execute PHP internal error handler
        return true;
    });

    set_exception_handler(function($e) {
        echo "<div style='background: #f8d7da; color: #721c24; padding: 10px; margin: 10px; border: 1px solid #f5c6cb; border-radius: 4px;'>";
        echo "<h3 style='margin-top: 0;'>Uncaught Exception</h3>";
        echo "<p><strong>Message:</strong> " . $e->getMessage() . "</p>";
        echo "<p><strong>File:</strong> " . $e->getFile() . "</p>";
        echo "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
        echo "<p><strong>Trace:</strong></p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
        echo "</div>";
        
        // Log the exception
        error_log("Uncaught Exception: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine());
    });
}

// Start session
session_start();

// Define base path
define('BASE_PATH', __DIR__);

// Load helper functions (needed for env() and logActivity())
require_once BASE_PATH . '/App/helpers.php';

// Load environment variables
require_once BASE_PATH . '/App/Core/DotEnv.php';
use App\Core\DotEnv;

try {
    DotEnv::load(BASE_PATH);
    
    // Debug environment variables
    logActivity("Environment variables loaded: " . json_encode([
        'DB_HOST' => env('DB_HOST', 'localhost'),
        'DB_NAME' => env('DB_NAME', 'student_accommodation'),
        'DB_USERNAME' => env('DB_USERNAME', 'root'),
        'DB_PASSWORD' => env('DB_PASSWORD', '')
    ]), 'debug');
} catch (\Exception $e) {
    logActivity("DotEnv error: " . $e->getMessage(), 'error');
    if (isDevelopment()) {
        die("Failed to load .env file: " . htmlspecialchars($e->getMessage()));
    } else {
        http_response_code(500);
        include BASE_PATH . '/resources/views/errors/500.php';
        exit;
    }
}

// Autoloader
spl_autoload_register(function ($class) {
    $file = BASE_PATH . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Initialize database
use App\Core\Database;
try {
    Database::getInstance();
} catch (\Exception $e) {
    logActivity("Database error: " . $e->getMessage(), 'error');
    if (isDevelopment()) {
        echo '<h1>Database Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        http_response_code(500);
        include BASE_PATH . '/resources/views/errors/500.php';
    }
    exit;
}

// Initialize router
use App\Core\Router;
$router = new Router();

// Define routes
$router->get('/', 'HomeController@index');
$router->get('/properties', 'PropertyController@index');
$router->get('/properties/create', 'PropertyController@create');
$router->post('/properties', 'PropertyController@store');
$router->get('/properties/{id}', 'PropertyController@show');
$router->get('/properties/{id}/edit', 'PropertyController@edit');
$router->put('/properties/{id}', 'PropertyController@update');
$router->delete('/properties/{id}', 'PropertyController@destroy');

// Authentication routes
$router->get('/login', 'AuthController@showLogin');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@showRegister');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');

// Dashboard routes
$router->get('/dashboard', 'DashboardController@index');
$router->get('/dashboard/profile', 'DashboardController@profile');
$router->post('/dashboard/profile', 'DashboardController@updateProfile');
$router->get('/dashboard/saved', 'DashboardController@savedProperties');
$router->get('/dashboard/messages', 'DashboardController@messages');
$router->get('/dashboard/security', 'DashboardController@security');

// Verification routes
$router->get('/verification', 'VerificationController@index');
$router->post('/verification/upload-document', 'VerificationController@uploadDocument');
$router->get('/verification/biometric', 'VerificationController@biometric');
$router->post('/verification/process-biometric', 'VerificationController@processBiometric');

// API routes
$router->post('/api/save-property', 'ApiController@saveProperty');
$router->post('/api/messages', 'ApiController@sendMessage');
$router->post('/api/report-property', 'ApiController@reportProperty');
$router->get('/api/messages/{conversationId}', 'ApiController@getMessages');

// Static pages
$router->get('/how-it-works', 'HomeController@howItWorks');
$router->get('/safety', 'HomeController@safety');
$router->get('/help', 'HomeController@help');
$router->get('/contact', 'HomeController@contact');
$router->get('/privacy', 'HomeController@privacy');
$router->get('/terms', 'HomeController@terms');

// Error handling
try {
    $router->dispatch();
} catch (\Exception $e) {
    // Log the error
    logActivity('Error: ' . $e->getMessage(), 'error');
    
    // Show error page
    if (isDevelopment()) {
        echo '<h1>Error</h1>';
        echo '<p>' . htmlspecialchars($e->getMessage()) . '</p>';
        echo '<pre>' . htmlspecialchars($e->getTraceAsString()) . '</pre>';
    } else {
        http_response_code(500);
        include BASE_PATH . '/resources/views/errors/500.php';
    }
}