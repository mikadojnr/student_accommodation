<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', __DIR__);

// Load helper functions (needed for env())
require_once BASE_PATH . '/App/helpers.php';

// Load DotEnv class
require_once BASE_PATH . '/App/Core/DotEnv.php';

// Load Database class
require_once BASE_PATH . '/App/Core/Database.php';

use App\Core\DotEnv;
use App\Core\Database;

try {
    // Load environment variables
    DotEnv::load(BASE_PATH);
    
    // Try database connection
    $db = Database::getInstance();
    echo "Database connection successful!\n";
    
    // Get the PDO connection
    $connection = $db->getConnection();
    
    // Check if tables exist
    $tables = ['users', 'properties', 'verifications', 'property_images', 'messages', 'saved_properties'];
    foreach ($tables as $table) {
        $query = "SHOW TABLES LIKE '$table'";
        $result = $connection->query($query);
        echo "$table table exists: " . ($result->rowCount() > 0 ? "Yes" : "No") . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}