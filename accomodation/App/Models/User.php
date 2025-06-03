<?php

namespace App\Models;

use App\Core\Database;

class User
{
    public static function create($data)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "INSERT INTO users (first_name, last_name, email, password, user_type, phone, university, created_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
        
        $stmt = $db->prepare($sql);
        $result = $stmt->execute([
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            $data['password'],
            $data['user_type'],
            $data['phone'],
            $data['university'] ?? null
        ]);
        
        return $result ? $db->lastInsertId() : false;
    }

    public static function find($id)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM users WHERE id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        
        return $stmt->fetch();
    }

    public static function findByEmail($email)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$email]);
        
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
        
        $sql = "UPDATE users SET " . implode(', ', $fields) . ", updated_at = NOW() WHERE id = ?";
        $stmt = $db->prepare($sql);
        
        return $stmt->execute($values);
    }

    public static function updateLastLogin($id)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "UPDATE users SET last_login = NOW() WHERE id = ?";
        $stmt = $db->prepare($sql);
        
        return $stmt->execute([$id]);
    }

    public static function getTrustScore($id)
    {
        $verification = Verification::getByUserId($id);
        
        if (!$verification) {
            return 0;
        }
        
        $score = 0;
        
        if ($verification['identity_status'] === 'verified') $score += 30;
        if ($verification['address_status'] === 'verified') $score += 20;
        if ($verification['student_status'] === 'verified') $score += 25;
        if ($verification['biometric_status'] === 'verified') $score += 25;
        
        return $score;
    }
}
