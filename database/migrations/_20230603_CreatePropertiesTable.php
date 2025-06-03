<?php

namespace Database\Migrations;

use App\Core\Migration;

class _20230603_CreatePropertiesTable extends Migration
{
    /**
     * Run the migration
     *
     * @return void
     */
    public function up()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS properties (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                title VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                property_type ENUM('apartment', 'house', 'studio', 'shared_room', 'private_room') NOT NULL,
                address VARCHAR(255) NOT NULL,
                city VARCHAR(100) NOT NULL,
                state VARCHAR(100) NOT NULL,
                zip_code VARCHAR(20) NOT NULL,
                latitude DECIMAL(10, 8) NULL,
                longitude DECIMAL(11, 8) NULL,
                price DECIMAL(10, 2) NOT NULL,
                deposit DECIMAL(10, 2) NOT NULL,
                bedrooms INT NOT NULL,
                bathrooms INT NOT NULL,
                size DECIMAL(10, 2) NULL,
                is_furnished BOOLEAN NOT NULL DEFAULT 0,
                available_from DATE NOT NULL,
                lease_term ENUM('short_term', 'semester', 'academic_year', 'yearly') NOT NULL,
                amenities JSON NULL,
                safety_features JSON NULL,
                verification_status ENUM('unverified', 'pending', 'verified') NOT NULL DEFAULT 'unverified',
                status ENUM('active', 'inactive', 'rented', 'flagged') NOT NULL DEFAULT 'active',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
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
        $this->execute("DROP TABLE IF EXISTS properties");
    }
}
