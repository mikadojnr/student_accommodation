<?php

namespace Database\Migrations;

use App\Core\Migration;

class _20230611_FixPropertiesTableStructure extends Migration
{
    public function up()
    {
        $this->execute("
            ALTER TABLE properties 
            ADD COLUMN verification_status ENUM('pending', 'verified', 'rejected') DEFAULT 'verified'
        ");
    }

    public function down()
    {
        $this->execute("
            ALTER TABLE properties 
            DROP COLUMN verification_status
        ");
    }
}
