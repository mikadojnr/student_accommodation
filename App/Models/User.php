<?php

namespace App\Models;

use App\Core\Model;
use App\Core\Database;
use PDO;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'user_type', 
        'phone', 'university', 'bio', 'verification_status', 
        'two_factor_enabled', 'two_factor_secret', 'remember_token'
    ];
    protected $hidden = ['password', 'remember_token'];

    public static function findByEmail($email)
    {
        $users = static::where('email', $email);
        return !empty($users) ? $users[0] : null;
    }

    public function verifyPassword($password)
    {
        $user = self::find($this->id ?? null);
        if (!$user) {
            return false;
        }
        return password_verify($password, $user->password);
    }

    public static function create($data)
    {
        $instance = new static();
        
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        
        // Set default verification status
        $data['verification_status'] = $data['verification_status'] ?? 'unverified';
        
        return $instance->save($data);
    }

    public function updatePassword($userId, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password' => $hashedPassword]);
    }

    public static function getVerifiedLandlords($limit = null)
    {
        $instance = new static();
        $sql = "
            SELECT u.*, COUNT(p.id) as property_count
            FROM users u
            LEFT JOIN properties p ON u.id = p.user_id AND p.status = 'active'
            WHERE u.user_type = 'landlord' AND u.verification_status = 'verified'
            GROUP BY u.id
            ORDER BY property_count DESC, u.created_at DESC
        ";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $instance->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getStudentsByUniversity($university = null, $limit = null)
    {
        $instance = new static();
        $sql = "
            SELECT * FROM users 
            WHERE user_type = 'student' AND verification_status = 'verified'
        ";
        $params = [];
        
        if ($university) {
            $sql .= " AND university LIKE ?";
            $params[] = "%{$university}%";
        }
        
        $sql .= " ORDER BY created_at DESC";
        
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        
        $stmt = $instance->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getProfile($userId)
    {
        $sql = "
            SELECT u.*, v.identity_status, v.address_status, v.student_status, v.biometric_status
            FROM users u
            LEFT JOIN verifications v ON u.id = v.user_id
            WHERE u.id = ?
        ";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        
        return $stmt->fetch(PDO::FETCH_OBJ);
    }

    public function getTrustScore($userId)
    {
        $profile = $this->getProfile($userId);
        if (!$profile) {
            return 0;
        }
        
        $score = 0;
        
        // Base score for verified email
        if ($profile->email_verified_at) {
            $score += 10;
        }
        
        // Identity verification (30 points)
        if ($profile->identity_status === 'verified') {
            $score += 30;
        }
        
        // Address verification (20 points)
        if ($profile->address_status === 'verified') {
            $score += 20;
        }
        
        // Biometric verification (25 points)
        if ($profile->biometric_status === 'verified') {
            $score += 25;
        }
        
        // Student verification (15 points) - only for students
        if ($profile->user_type === 'student' && $profile->student_status === 'verified') {
            $score += 15;
        }
        
        return min($score, 100);
    }

    public static function getUserStatistics()
    {
        $instance = new static();
        
        $stats = [];
        
        // Total users
        $stmt = $instance->db->query("SELECT COUNT(*) as count FROM users");
        $stats['total_users'] = $stmt->fetch(PDO::FETCH_OBJ)->count;
        
        // Users by type
        $stmt = $instance->db->query("
            SELECT user_type, COUNT(*) as count 
            FROM users 
            GROUP BY user_type
        ");
        $stats['by_type'] = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Verification status
        $stmt = $instance->db->query("
            SELECT verification_status, COUNT(*) as count 
            FROM users 
            GROUP BY verification_status
        ");
        $stats['by_verification'] = $stmt->fetchAll(PDO::FETCH_OBJ);
        
        // Recent registrations (last 30 days)
        $stmt = $instance->db->query("
            SELECT COUNT(*) as count 
            FROM users 
            WHERE created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
        ");
        $stats['recent_registrations'] = $stmt->fetch(PDO::FETCH_OBJ)->count;
        
        return $stats;
    }

    public static function search($query, $type = null, $limit = 20)
    {
        $instance = new static();
        $sql = "
            SELECT * FROM users 
            WHERE (first_name LIKE ? OR last_name LIKE ? OR email LIKE ? OR university LIKE ?)
            AND verification_status = 'verified'
        ";
        $params = ["%{$query}%", "%{$query}%", "%{$query}%", "%{$query}%"];
        
        if ($type) {
            $sql .= " AND user_type = ?";
            $params[] = $type;
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT {$limit}";
        
        $stmt = $instance->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function getFullName()
    {
        return trim($this->first_name . ' ' . $this->last_name);
    }

    public function isVerified()
    {
        return $this->verification_status === 'verified';
    }

    public function properties()
    {
        return Property::where('user_id', $this->id);
    }

    public function messages()
    {
        $sql = "SELECT m.*, u.first_name, u.last_name 
                FROM messages m 
                JOIN users u ON (u.id = m.sender_id OR u.id = m.recipient_id) 
                WHERE (m.sender_id = ? OR m.recipient_id = ?) AND u.id != ?
                ORDER BY m.created_at DESC";
        
        return static::query($sql, [$this->id, $this->id, $this->id]);
    }

    public function verification()
    {
        $verifications = Verification::where('user_id', $this->id);
        return !empty($verifications) ? $verifications[0] : null;
    }

    public function updateLastLogin()
    {
        $this->last_login = date('Y-m-d H:i:s');
        return $this->save();
    }

    public function incrementViews()
    {
        $this->views = ($this->views ?? 0) + 1;
        return $this->save();
    }
}
