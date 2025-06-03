<?php

namespace Database\Migrations;

use App\Core\Migration;

class _20230609_UpdateVerificationsTable extends Migration
{
    public function up()
    {
        $this->execute("
            ALTER TABLE verifications 
            ADD COLUMN identity_status ENUM('pending', 'verified', 'rejected') DEFAULT 'pending' AFTER user_id,
            ADD COLUMN address_status ENUM('pending', 'verified', 'rejected') NULL AFTER identity_status,
            ADD COLUMN student_status ENUM('pending', 'verified', 'rejected') NULL AFTER address_status,
            ADD COLUMN biometric_status ENUM('pending', 'verified', 'rejected') NULL AFTER student_status,
            ADD COLUMN identity_document_path VARCHAR(255) NULL AFTER biometric_status,
            ADD COLUMN address_document_path VARCHAR(255) NULL AFTER identity_document_path,
            ADD COLUMN student_document_path VARCHAR(255) NULL AFTER address_document_path,
            ADD COLUMN biometric_image_path VARCHAR(255) NULL AFTER student_document_path,
            ADD COLUMN identity_rejection_reason TEXT NULL AFTER biometric_image_path,
            ADD COLUMN address_rejection_reason TEXT NULL AFTER identity_rejection_reason,
            ADD COLUMN student_rejection_reason TEXT NULL AFTER address_rejection_reason,
            ADD COLUMN biometric_rejection_reason TEXT NULL AFTER student_rejection_reason,
            DROP COLUMN provider,
            DROP COLUMN provider_verification_id,
            DROP COLUMN document_type,
            DROP COLUMN document_path,
            DROP COLUMN selfie_path,
            DROP COLUMN status,
            DROP COLUMN rejection_reason,
            DROP COLUMN verification_data,
            DROP COLUMN expires_at
        ");
    }

    public function down()
    {
        $this->execute("
            ALTER TABLE verifications 
            ADD COLUMN provider ENUM('jumio', 'id.me', 'onfido', 'internal') NOT NULL AFTER user_id,
            ADD COLUMN provider_verification_id VARCHAR(255) NULL AFTER provider,
            ADD COLUMN document_type ENUM('passport', 'id_card', 'driving_license', 'student_id') NULL AFTER provider_verification_id,
            ADD COLUMN document_path VARCHAR(255) NULL AFTER document_type,
            ADD COLUMN selfie_path VARCHAR(255) NULL AFTER document_path,
            ADD COLUMN status ENUM('pending', 'approved', 'rejected') NOT NULL DEFAULT 'pending' AFTER selfie_path,
            ADD COLUMN rejection_reason TEXT NULL AFTER status,
            ADD COLUMN verification_data JSON NULL AFTER rejection_reason,
            ADD COLUMN expires_at TIMESTAMP NULL AFTER verification_data,
            DROP COLUMN identity_status,
            DROP COLUMN address_status,
            DROP COLUMN student_status,
            DROP COLUMN biometric_status,
            DROP COLUMN identity_document_path,
            DROP COLUMN address_document_path,
            DROP COLUMN student_document_path,
            DROP COLUMN biometric_image_path,
            DROP COLUMN identity_rejection_reason,
            DROP COLUMN address_rejection_reason,
            DROP COLUMN student_rejection_reason,
            DROP COLUMN biometric_rejection_reason
        ");
    }
}
