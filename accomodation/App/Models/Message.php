<?php

namespace App\Models;

use App\Core\Database;

class Message
{
    public static function create($data)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "INSERT INTO messages (sender_id, recipient_id, property_id, message, is_encrypted, created_at) 
                VALUES (?, ?, ?, ?, ?, NOW())";
        
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $data['sender_id'],
            $data['recipient_id'],
            $data['property_id'],
            $data['message'],
            $data['is_encrypted'] ?? 0
        ]);
        
        return $result ? $db->lastInsertId() : false;
    }

    public static function getConversation($userId1, $userId2, $propertyId)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT m.*, u.first_name, u.last_name 
                FROM messages m 
                JOIN users u ON m.sender_id = u.id 
                WHERE ((m.sender_id = ? AND m.recipient_id = ?) OR (m.sender_id = ? AND m.recipient_id = ?))
                AND m.property_id = ?
                ORDER BY m.created_at ASC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId1, $userId2, $userId2, $userId1, $propertyId]);
        
        return $stmt->fetchAll();
    }

    public static function getByUserId($userId, $limit = 10)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT DISTINCT m.*, u.first_name, u.last_name, p.title as property_title
                FROM messages m 
                JOIN users u ON (m.sender_id = u.id OR m.recipient_id = u.id) AND u.id != ?
                LEFT JOIN properties p ON m.property_id = p.id
                WHERE m.sender_id = ? OR m.recipient_id = ?
                ORDER BY m.created_at DESC 
                LIMIT ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId, $userId, $userId, $limit]);
        
        return $stmt->fetchAll();
    }

    public static function getRecentForLandlord($landlordId, $limit = 5)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT m.*, u.first_name, u.last_name, p.title as property_title
                FROM messages m 
                JOIN users u ON m.sender_id = u.id
                JOIN properties p ON m.property_id = p.id
                WHERE p.user_id = ? AND m.recipient_id = ?
                ORDER BY m.created_at DESC 
                LIMIT ?";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$landlordId, $landlordId, $limit]);
        
        return $stmt->fetchAll();
    }

    public static function getCountForProperty($propertyId)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT COUNT(*) FROM messages WHERE property_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$propertyId]);
        
        return $stmt->fetchColumn();
    }

    public static function getCountForPropertyToday($propertyId)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT COUNT(*) FROM messages WHERE property_id = ? AND DATE(created_at) = CURDATE()";
        $stmt = $db->prepare($sql);
        $stmt->execute([$propertyId]);
        
        return $stmt->fetchColumn();
    }
}
