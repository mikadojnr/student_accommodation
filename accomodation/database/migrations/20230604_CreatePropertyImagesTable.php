<?php

namespace Database\Migrations;

use App\Core\Migration;

class CreatePropertyImagesTable extends Migration
{
    /**
     * Run the migration
     *
     * @return void
     */
    public function up()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS property_images (
                id INT AUTO_INCREMENT PRIMARY KEY,
                property_id INT NOT NULL,
                image_path VARCHAR(255) NOT NULL,
                is_primary BOOLEAN NOT NULL DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    /**
     * Reverse the migration
     *
     * @return void
     */
    public function down()
    {
        $this->execute("DROP TABLE IF EXISTS property_images");
    }
}
