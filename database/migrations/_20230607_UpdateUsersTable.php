<?php

namespace Database\Migrations;

use App\Core\Migration;

class _20230607_UpdateUsersTable extends Migration
{
    public function up()
    {
        $this->execute("
            ALTER TABLE users 
            ADD COLUMN first_name VARCHAR(100) NOT NULL AFTER id,
            ADD COLUMN last_name VARCHAR(100) NOT NULL AFTER first_name,
            ADD COLUMN user_type ENUM('student', 'landlord') NOT NULL DEFAULT 'student' AFTER email,
            ADD COLUMN phone VARCHAR(20) NULL AFTER user_type,
            ADD COLUMN university VARCHAR(255) NULL AFTER phone,
            ADD COLUMN bio TEXT NULL AFTER university,
            ADD COLUMN last_login TIMESTAMP NULL AFTER bio,
            ADD COLUMN views INT DEFAULT 0 AFTER last_login,
            DROP COLUMN name,
            DROP COLUMN role
        ");
    }

    public function down()
    {
        $this->execute("
            ALTER TABLE users 
            ADD COLUMN name VARCHAR(255) NOT NULL AFTER id,
            ADD COLUMN role ENUM('student', 'landlord', 'admin') NOT NULL DEFAULT 'student' AFTER password,
            DROP COLUMN first_name,
            DROP COLUMN last_name,
            DROP COLUMN user_type,
            DROP COLUMN phone,
            DROP COLUMN university,
            DROP COLUMN bio,
            DROP COLUMN last_login,
            DROP COLUMN views
        ");
    }
}
