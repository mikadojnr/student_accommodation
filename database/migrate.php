<?php

define('BASE_PATH', dirname(__DIR__));

// Load your bootstrap file that loads .env and core classes (DotEnv, Database, etc)
require_once BASE_PATH . '/bootstrap/app.php';
require_once BASE_PATH . '/App/Core/Migration.php';

use App\Core\Database;

// Get the PDO connection
$db = Database::getInstance()->getConnection();

// Create the migrations table if it doesn't exist
$db->exec("
    CREATE TABLE IF NOT EXISTS migrations (
        id INT AUTO_INCREMENT PRIMARY KEY,
        migration VARCHAR(255) NOT NULL,
        batch INT NOT NULL
    )
");

// Determine if we're running a rollback or migration (default is migrate)
$mode = $argv[1] ?? 'migrate';

if ($mode === 'rollback') {
    echo "🔄 Rolling back last migration batch...\n";

    // Get the latest batch number
    $stmt = $db->query("SELECT MAX(batch) as batch FROM migrations");
    $latestBatch = $stmt->fetch()['batch'];

    if (!$latestBatch) {
        echo "⚠️  No migrations to rollback.\n";
        exit;
    }

    // Get migrations in the latest batch ordered newest last for rollback in reverse
    $stmt = $db->prepare("SELECT migration FROM migrations WHERE batch = ? ORDER BY id DESC");
    $stmt->execute([$latestBatch]);
    $migrations = $stmt->fetchAll(\PDO::FETCH_COLUMN);

    foreach ($migrations as $migrationName) {
        $filePath = BASE_PATH . "/database/migrations/{$migrationName}.php";

        if (!file_exists($filePath)) {
            echo "❌ File {$migrationName}.php not found.\n";
            continue;
        }

        require_once $filePath;
        $fullClass = "Database\\Migrations\\{$migrationName}";

        if (!class_exists($fullClass)) {
            echo "❌ Class {$fullClass} not found.\n";
            continue;
        }

        echo "🧨 Rolling back: {$migrationName}\n";
        $migration = new $fullClass();
        $migration->down();

        $stmt = $db->prepare("DELETE FROM migrations WHERE migration = ?");
        $stmt->execute([$migrationName]);

        echo "✅ Rolled back: {$migrationName}\n";
    }

    echo "✅ Rollback of batch {$latestBatch} completed.\n";
    exit;
}

// Otherwise: run new migrations
echo "🚀 Running migrations...\n";

// Get all migration files in migrations directory
$migrationFiles = glob(BASE_PATH . '/database/migrations/*.php');

// Get the latest batch number and increment for this run
$stmt = $db->query("SELECT MAX(batch) as batch FROM migrations");
$lastBatch = $stmt->fetch()['batch'] ?? 0;
$currentBatch = $lastBatch + 1;

// Get all migrations already run
$stmt = $db->query("SELECT migration FROM migrations");
$ranMigrations = $stmt->fetchAll(\PDO::FETCH_COLUMN);

foreach ($migrationFiles as $file) {
    require_once $file;

    $className = pathinfo($file, PATHINFO_FILENAME);
    $fullClassName = "Database\\Migrations\\{$className}";

    if (!in_array($className, $ranMigrations)) {
        echo "➡️  Running migration: {$className}\n";

        if (!class_exists($fullClassName)) {
            echo "❌ Migration class {$fullClassName} not found.\n";
            continue;
        }

        $migration = new $fullClassName();
        $migration->up();

        $stmt = $db->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
        $stmt->execute([$className, $currentBatch]);

        echo "✅ Migration {$className} completed.\n";
    } else {
        echo "⚠️  Migration {$className} already ran.\n";
    }
}

echo "✅ All migrations completed.\n";
