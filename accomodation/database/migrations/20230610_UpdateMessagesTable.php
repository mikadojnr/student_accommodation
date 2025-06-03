<?php

namespace Database\Migrations;

use App\Core\Migration;

class UpdateMessagesTable extends Migration
{
    public function up()
    {
        $this->execute("
            ALTER TABLE messages 
            ADD COLUMN recipient_id INT NOT NULL AFTER sender_id,
            ADD COLUMN is_encrypted BOOLEAN NOT NULL DEFAULT 0 AFTER message,
            ADD CONSTRAINT fk_messages_recipient FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE,
            DROP COLUMN receiver_id,
            DROP COLUMN conversation_id,
            DROP COLUMN is_read,
            DROP COLUMN flagged_as_suspicious
        ");
    }

    public function down()
    {
        $this->execute("
            ALTER TABLE messages 
            ADD COLUMN receiver_id INT NOT NULL AFTER sender_id,
            ADD COLUMN conversation_id VARCHAR(50) NOT NULL AFTER property_id,
            ADD COLUMN is_read BOOLEAN NOT NULL DEFAULT 0 AFTER message,
            ADD COLUMN flagged_as_suspicious BOOLEAN NOT NULL DEFAULT 0 AFTER is_read,
            DROP FOREIGN KEY fk_messages_recipient,
            DROP COLUMN recipient_id,
            DROP COLUMN is_encrypted
        ");
    }
}
