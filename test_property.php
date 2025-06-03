<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

define('BASE_PATH', __DIR__);

require_once BASE_PATH . '/App/helpers.php';
require_once BASE_PATH . '/App/Core/DotEnv.php';
require_once BASE_PATH . '/App/Core/Database.php';
require_once BASE_PATH . '/App/Models/Property.php';

use App\Core\DotEnv;
use App\Models\Property;

try {
    // Load environment variables
    DotEnv::load(BASE_PATH);
    
    // Test Property methods
    echo "Testing Property::getFeatured(6)...\n";
    $featured = Property::getFeatured(6);
    var_dump($featured);
    
    echo "\nTesting Property::getRecentlyAdded(6)...\n";
    $recent = Property::getRecentlyAdded(6);
    var_dump($recent);
    
    echo "\nTesting Property::getStatistics()...\n";
    $stats = Property::getStatistics();
    var_dump($stats);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "In file: " . $e->getFile() . " on line " . $e->getLine() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}