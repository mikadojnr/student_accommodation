<?php

namespace Database\Migrations;

use App\Core\Migration;

class _20230602_CreateVerificationsTable extends Migration
{
    /**
     * Run the migration
     *
     * @return void
     */
    public function up()
    {
        $this->execute("
            CREATE TABLE IF NOT EXISTS verifications (
                id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NOT NULL,
                provider ENUM('jumio', 'id.me', 'onfido', 'internal') NOT NULL,
                provider_verification_id VARCHAR(255) NULL,
                document_type ENUM('passport', 'id_card', 'driving_license', 'student_id') NULL,
                document_path VARCHAR(255) NULL,
                selfie_path VARCHAR(255) NULL,
                status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending',
                rejection_reason TEXT NULL,
                verification_data JSON NULL,
                expires_at TIMESTAMP NULL,
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
        $this->execute("DROP TABLE IF EXISTS verifications");
    }
}
