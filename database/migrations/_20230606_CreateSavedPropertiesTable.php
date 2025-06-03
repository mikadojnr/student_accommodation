<?php

namespace Database\Migrations;

use App\Core\Migration;

class _20230606_CreateSavedPropertiesTable extends Migration
{
    public function up()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS saved_properties (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                property_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE,
                UNIQUE KEY unique_save (user_id, property_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS saved_properties");
    }
}
