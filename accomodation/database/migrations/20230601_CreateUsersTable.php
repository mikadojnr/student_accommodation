<?php

namespace Database\Migrations;

use App\Core\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migration
     *
     * @return void
     */
    public function up()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                role ENUM('student', 'landlord', 'admin') NOT NULL DEFAULT 'student',
                email_verified_at TIMESTAMP NULL,
                verification_status ENUM('unverified', 'pending', 'verified') NOT NULL DEFAULT 'unverified',
                two_factor_secret VARCHAR(255) NULL,
                two_factor_enabled BOOLEAN NOT NULL DEFAULT 0,
                remember_token VARCHAR(100) NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
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
        $this->execute("DROP TABLE IF EXISTS users");
    }
}
