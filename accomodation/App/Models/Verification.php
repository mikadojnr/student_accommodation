<?php

namespace App\Models;

use App\Core\Database;

class Verification
{
    public static function create($data)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "INSERT INTO verifications (user_id, identity_status, address_status, student_status, 
                biometric_status, identity_document_path, address_document_path, student_document_path, 
                biometric_image_path, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $data['user_id'],
            $data['identity_status'] ?? 'pending',
            $data['address_status'] ?? null,
            $data['student_status'] ?? null,
            $data['biometric_status'] ?? null,
            $data['identity_document_path'] ?? null,
            $data['address_document_path'] ?? null,
            $data['student_document_path'] ?? null,
            $data['biometric_image_path'] ?? null
        ]);
        
        return $result ? $db->lastInsertId() : false;
    }

    public static function getByUserId($userId)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM verifications WHERE user_id = ? ORDER BY created_at DESC LIMIT 1";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        
        return $stmt->fetch();
    }

    public static function update($id, $data)
    {
        $db = Database::getInstance()->getConnection();
        
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = $value;
        }
        
        $values[] = $id;
        
        $sql = "UPDATE verifications SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = ?";
        $stmt = $db->prepare($sql);
        
        return $stmt->execute($values);
    }
}
