<?php

namespace App\Models;

use App\Core\Database;

class PropertyImage
{
    public static function create($data)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "INSERT INTO property_images (property_id, image_path, is_primary, created_at) 
                VALUES (?, ?, ?, NOW())";
        
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $data['property_id'],
            $data['image_path'],
            $data['is_primary'] ?? 0
        ]);
        
        return $result ? $db->lastInsertId() : false;
    }

    public static function getByPropertyId($propertyId)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM property_images WHERE property_id = ? ORDER BY is_primary DESC, created_at ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$propertyId]);
        
        return $stmt->fetchAll();
    }

    public static function delete($id)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "DELETE FROM property_images WHERE id = ?";
        $stmt = $db->prepare($sql);
        
        return $stmt->execute([$id]);
    }
}
