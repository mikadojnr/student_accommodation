<?php

namespace Database\Migrations;

use App\Core\Migration;

class _20230610_UpdateMessagesTable extends Migration
{
    public function up()
    {
        // Drop existing foreign key constraint on receiver_id
        $this->execute("ALTER TABLE messages DROP FOREIGN KEY messages_ibfk_2");

        // Drop old columns
        $this->execute("ALTER TABLE messages DROP COLUMN receiver_id");
        $this->execute("ALTER TABLE messages DROP COLUMN conversation_id");
        $this->execute("ALTER TABLE messages DROP COLUMN is_read");
        $this->execute("ALTER TABLE messages DROP COLUMN flagged_as_suspicious");

        // Add new columns
        $this->execute("ALTER TABLE messages ADD COLUMN recipient_id INT NOT NULL AFTER sender_id");
        $this->execute("ALTER TABLE messages ADD COLUMN is_encrypted BOOLEAN NOT NULL DEFAULT 0 AFTER message");

        // Add new foreign key constraint
        $this->execute("
            ALTER TABLE messages 
            ADD CONSTRAINT fk_messages_recipient FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE
        ");
    }

    public function down()
    {
        // Drop new foreign key first
        $this->execute("ALTER TABLE messages DROP FOREIGN KEY fk_messages_recipient");

        // Drop new columns
        $this->execute("ALTER TABLE messages DROP COLUMN recipient_id");
        $this->execute("ALTER TABLE messages DROP COLUMN is_encrypted");

        // Add old columns back
        $this->execute("ALTER TABLE messages ADD COLUMN receiver_id INT NOT NULL AFTER sender_id");
        $this->execute("ALTER TABLE messages ADD COLUMN conversation_id VARCHAR(50) NOT NULL AFTER property_id");
        $this->execute("ALTER TABLE messages ADD COLUMN is_read BOOLEAN NOT NULL DEFAULT 0 AFTER message");
        $this->execute("ALTER TABLE messages ADD COLUMN flagged_as_suspicious BOOLEAN NOT NULL DEFAULT 0 AFTER is_read");

        // Recreate old foreign key constraint (if needed)
        $this->execute("
            ALTER TABLE messages 
            ADD CONSTRAINT messages_ibfk_2 FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
        ");
    }
}
