<?php

namespace App\Models;

use App\Core\Database;
use App\Core\Model;

class Property extends Model
{
    protected $table = 'properties';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'property_type',
        'price',
        'deposit',
        'address',
        'city',
        'postal_code',
        'bedrooms',
        'bathrooms',
        'campus_distance',
        'amenities',
        'is_available',
        'verification_status',
        'views',
        'created_at',
        'updated_at'
    ];

    public function create($data)
    {
        // Filter data to include only fillable fields
        $fillableData = array_intersect_key($data, array_flip($this->fillable));
        // Add created_at timestamp
        $fillableData['created_at'] = date('Y-m-d H:i:s');

        // Use the parent Model's create method
        return parent::create($fillableData);
    }

    public function find($id)
    {
        $db = $this->db;
        
        $sql = "SELECT p.*, u.first_name, u.last_name, u.email as owner_email 
                FROM properties p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$id]);
        
        $result = $stmt->fetch();
        
        return $result ? $this->hydrate($result) : null;
    }

    public function update(array $data)
    {
        // Filter data to include only fillable fields
        $fillableData = array_intersect_key($data, array_flip($this->fillable));
        // Add updated_at timestamp
        $fillableData['updated_at'] = date('Y-m-d H:i:s');

        // Use the parent Model's update method
        return parent::update($fillableData);
    }

    public function delete()
    {
        // Use the parent Model's delete method
        return parent::delete();
    }

    public static function findWithDetails($id)
    {
        $property = (new self())->find($id);
        
        if ($property) {
            // Increment view count
            self::incrementViews($id);
        }
        
        return $property;
    }

    public static function getFiltered($filters)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT p.*, u.first_name, u.last_name, 
                (SELECT image_path FROM property_images WHERE property_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM properties p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.is_available = TRUE";
        
        $params = [];
        
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.price >= ?";
            $params[] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= ?";
            $params[] = $filters['max_price'];
        }
        
        if (!empty($filters['property_type'])) {
            $sql .= " AND p.property_type = ?";
            $params[] = $filters['property_type'];
        }
        
        if (!empty($filters['campus_distance'])) {
            $sql .= " AND p.campus_distance <= ?";
            $params[] = $filters['campus_distance'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.title LIKE ? OR p.description LIKE ? OR p.city LIKE ?)";
            $searchTerm = "%{$filters['search']}%";
            $params[] = $searchTerm;
            $params[] = $searchTerm;
            $params[] = $searchTerm;
        }
        
        if ($filters['verified_only']) {
            $sql .= " AND p.verification_status = 'verified'";
        }
        
        $sql .= " ORDER BY p.created_at DESC LIMIT 50";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $models[] = (new self())->hydrate($result);
        }
        
        return $models;
    }

    public static function getByUserId($userId)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT p.*, 
                (SELECT COUNT(*) FROM property_images WHERE property_id = p.id) as image_count,
                (SELECT image_path FROM property_images WHERE property_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM properties p 
                WHERE p.user_id = ? 
                ORDER BY p.created_at DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $models[] = (new self())->hydrate($result);
        }
        
        return $models;
    }

    public static function getSavedByUserId($userId)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT p.*, u.first_name, u.last_name,
                (SELECT image_path FROM property_images WHERE property_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM saved_properties sp
                JOIN properties p ON sp.property_id = p.id
                JOIN users u ON p.user_id = u.id
                WHERE sp.user_id = ?
                ORDER BY sp.created_at DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $models[] = (new self())->hydrate($result);
        }
        
        return $models;
    }

    public static function updateStatic($id, $data)
    {
        $property = (new self())->find($id);
        if (!$property) {
            return false;
        }
        
        return $property->update($data);
    }

    public static function deleteStatic($id)
    {
        $property = (new self())->find($id);
        if (!$property) {
            return false;
        }
        
        return $property->delete();
    }

    public static function search($query, $limit = 10)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT p.*, u.first_name, u.last_name,
                (SELECT image_path FROM property_images WHERE property_id = p.id AND is_primary = 1 LIMIT 1) as primary_image
                FROM properties p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.is_available = TRUE 
                AND (p.title LIKE ? OR p.description LIKE ? OR p.city LIKE ?)
                ORDER BY p.created_at DESC 
                LIMIT ?";
        
        $searchTerm = "%$query%";
        $stmt = $db->prepare($sql);
        $stmt->execute([$searchTerm, $searchTerm, $searchTerm, $limit]);
        
        $results = $stmt->fetchAll();
        
        $models = [];
        foreach ($results as $result) {
            $models[] = (new self())->hydrate($result);
        }
        
        return $models;
    }

    public static function toggleSave($userId, $propertyId)
    {
        $db = Database::getInstance()->getConnection();
        
        // Check if already saved
        $sql = "SELECT id FROM saved_properties WHERE user_id = ? AND property_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId, $propertyId]);
        
        if ($stmt->fetch()) {
            // Remove save
            $sql = "DELETE FROM saved_properties WHERE user_id = ? AND property_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$userId, $propertyId]);
            return false;
        } else {
            // Add save
            $sql = "INSERT INTO saved_properties (user_id, property_id, created_at) VALUES (?, ?, NOW())";
            $stmt = $db->prepare($sql);
            $stmt->execute([$userId, $propertyId]);
            return true;
        }
    }

    public static function getSaveCount($propertyId)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "SELECT COUNT(*) FROM saved_properties WHERE property_id = ?";
        $stmt = $db->prepare($sql);
        $stmt->execute([$propertyId]);
        
        return $stmt->fetchColumn();
    }

    public static function incrementViews($id)
    {
        $db = Database::getInstance()->getConnection();
        
        $sql = "UPDATE properties SET views = views + 1 WHERE id = ?";
        $stmt = $db->prepare($sql);
        
        return $stmt->execute([$id]);
    }
}