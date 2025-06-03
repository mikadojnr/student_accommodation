<?php

namespace Database\Migrations;

use App\Core\Migration;

class _20230608_UpdatePropertiesTable extends Migration
{
    public function up()
    {
        $this->execute("
            ALTER TABLE properties 
            ADD COLUMN postal_code VARCHAR(20) NOT NULL AFTER city,
            ADD COLUMN campus_distance DECIMAL(5,2) DEFAULT 0 AFTER postal_code,
            ADD COLUMN views INT DEFAULT 0 AFTER campus_distance,
            DROP COLUMN zip_code,
            DROP COLUMN latitude,
            DROP COLUMN longitude,
            DROP COLUMN size,
            DROP COLUMN is_furnished,
            DROP COLUMN available_from,
            DROP COLUMN lease_term,
            DROP COLUMN safety_features,
            DROP COLUMN verification_status
        ");
    }

    public function down()
    {
        $this->execute("
            ALTER TABLE properties 
            ADD COLUMN zip_code VARCHAR(20) NOT NULL AFTER city,
            ADD COLUMN latitude DECIMAL(10, 8) NULL AFTER zip_code,
            ADD COLUMN longitude DECIMAL(11, 8) NULL AFTER latitude,
            ADD COLUMN size DECIMAL(10, 2) NULL AFTER longitude,
            ADD COLUMN is_furnished BOOLEAN NOT NULL DEFAULT 0 AFTER size,
            ADD COLUMN available_from DATE NOT NULL AFTER is_furnished,
            ADD COLUMN lease_term ENUM('short_term', 'semester', 'academic_year', 'yearly') NOT NULL AFTER available_from,
            ADD COLUMN safety_features JSON NULL AFTER amenities,
            ADD COLUMN verification_status ENUM('unverified', 'pending', 'verified') NOT NULL DEFAULT 'unverified' AFTER safety_features,
            DROP COLUMN postal_code,
            DROP COLUMN campus_distance,
            DROP COLUMN views,
            DROP COLUMN status
        ");
    }
}
