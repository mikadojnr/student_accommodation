<?php

namespace App\Models;

use App\Core\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = ['sender_id', 'recipient_id', 'property_id', 'message', 'is_encrypted'];

    public function sender()
    {
        return User::find($this->sender_id);
    }

    public function recipient()
    {
        return User::find($this->recipient_id);
    }

    public function property()
    {
        return $this->property_id ? Property::find($this->property_id) : null;
    }

    public static function getConversation($userId1, $userId2, $propertyId = null)
    {
        $sql = "SELECT m.*, 
                       s.first_name as sender_first_name, s.last_name as sender_last_name,
                       r.first_name as recipient_first_name, r.last_name as recipient_last_name
                FROM messages m
                JOIN users s ON m.sender_id = s.id
                JOIN users r ON m.recipient_id = r.id
                WHERE ((m.sender_id = ? AND m.recipient_id = ?) OR (m.sender_id = ? AND m.recipient_id = ?))";
        
        $params = [$userId1, $userId2, $userId2, $userId1];
        
        if ($propertyId) {
            $sql .= " AND m.property_id = ?";
            $params[] = $propertyId;
        }
        
        $sql .= " ORDER BY m.created_at ASC";
        
        return static::query($sql, $params);
    }

    public static function getConversationsList($userId)
    {
        $sql = "SELECT DISTINCT 
                    CASE 
                        WHEN m.sender_id = ? THEN m.recipient_id 
                        ELSE m.sender_id 
                    END as other_user_id,
                    u.first_name, u.last_name,
                    p.title as property_title,
                    m.property_id,
                    MAX(m.created_at) as last_message_time,
                    (SELECT message FROM messages m2 
                     WHERE ((m2.sender_id = ? AND m2.recipient_id = other_user_id) OR 
                            (m2.sender_id = other_user_id AND m2.recipient_id = ?))
                     AND (m.property_id IS NULL OR m2.property_id = m.property_id)
                     ORDER BY m2.created_at DESC LIMIT 1) as last_message
                FROM messages m
                JOIN users u ON u.id = CASE 
                    WHEN m.sender_id = ? THEN m.recipient_id 
                    ELSE m.sender_id 
                END
                LEFT JOIN properties p ON m.property_id = p.id
                WHERE m.sender_id = ? OR m.recipient_id = ?
                GROUP BY other_user_id, m.property_id
                ORDER BY last_message_time DESC";
        
        return static::query($sql, [$userId, $userId, $userId, $userId, $userId, $userId]);
    }

    public function encrypt($message)
    {
        // Simple encryption - in production, use proper encryption
        return base64_encode($message);
    }

    public function decrypt($encryptedMessage)
    {
        // Simple decryption - in production, use proper decryption
        return base64_decode($encryptedMessage);
    }
}
