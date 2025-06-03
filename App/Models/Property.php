<?php

namespace App\Models;

use App\Core\Model;

class Property extends Model
{
    protected $table = 'properties';
    protected $fillable = [
        'user_id', 'title', 'description', 'property_type', 'address', 
        'city', 'postal_code', 'campus_distance', 'price', 'deposit', 
        'bedrooms', 'bathrooms', 'amenities', 'status'
    ];

    public function user()
    {
        return User::find($this->user_id);
    }

    public function images()
    {
        return PropertyImage::where('property_id', $this->id);
    }

    public function primaryImage()
    {
        $images = PropertyImage::where('property_id', $this->id);
        foreach ($images as $image) {
            if ($image->is_primary) {
                return $image;
            }
        }
        return !empty($images) ? $images[0] : null;
    }

    public function incrementViews()
    {
        $this->views = ($this->views ?? 0) + 1;
        return $this->save();
    }

    public function getAmenitiesArray()
    {
        return $this->amenities ? json_decode($this->amenities, true) : [];
    }

    public function setAmenitiesArray($amenities)
    {
        $this->amenities = json_encode($amenities);
    }

    public static function search($filters = [])
    {
        $sql = "SELECT p.*, u.first_name, u.last_name, 
                       (SELECT image_path FROM property_images pi WHERE pi.property_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image
                FROM properties p 
                JOIN users u ON p.user_id = u.id 
                WHERE p.status = 'active'";
        
        $params = [];
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.title LIKE ? OR p.description LIKE ? OR p.city LIKE ? OR p.address LIKE ?)";
            $searchTerm = '%' . $filters['search'] . '%';
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }
        
        if (!empty($filters['property_type'])) {
            $sql .= " AND p.property_type = ?";
            $params[] = $filters['property_type'];
        }
        
        if (!empty($filters['min_price'])) {
            $sql .= " AND p.price >= ?";
            $params[] = $filters['min_price'];
        }
        
        if (!empty($filters['max_price'])) {
            $sql .= " AND p.price <= ?";
            $params[] = $filters['max_price'];
        }
        
        if (!empty($filters['campus_distance'])) {
            $sql .= " AND p.campus_distance <= ?";
            $params[] = $filters['campus_distance'];
        }
        
        if (!empty($filters['verified_only'])) {
            $sql .= " AND u.verification_status = 'verified'";
        }
        
        $sql .= " ORDER BY p.created_at DESC";
        
        return static::query($sql, $params);
    }

    /**
     * Get featured properties (based on views, active status)
     * @param int $limit Number of properties to return
     * @return array Collection of Property objects
     */
    public static function getFeatured($limit = 6)
    {
        $sql = "SELECT p.*, 
                       (SELECT image_path FROM property_images pi WHERE pi.property_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image
                FROM properties p
                WHERE p.status = 'active'
                ORDER BY p.views DESC, p.created_at DESC
                LIMIT ?";
        return static::query($sql, [$limit]);
    }

    /**
     * Get recently added properties
     * @param int $limit Number of properties to return
     * @return array Collection of Property objects
     */
    public static function getRecentlyAdded($limit = 6)
    {
        $sql = "SELECT p.*, 
                       (SELECT image_path FROM property_images pi WHERE pi.property_id = p.id AND pi.is_primary = 1 LIMIT 1) as primary_image
                FROM properties p
                WHERE p.status = 'active'
                ORDER BY p.created_at DESC
                LIMIT ?";
        return static::query($sql, [$limit]);
    }

    /**
     * Get property statistics
     * @return array Statistics about properties and landlords
     */
    public static function getStatistics()
    {
        $totalProperties = static::query("SELECT COUNT(*) as count FROM properties WHERE status = 'active'")[0]->count;
        $verifiedLandlords = static::query("SELECT COUNT(DISTINCT user_id) as count FROM properties p JOIN users u ON p.user_id = u.id WHERE u.verification_status = 'verified'")[0]->count;
        $cities = static::query("SELECT COUNT(DISTINCT city) as count FROM properties WHERE status = 'active'")[0]->count;

        return [
            'total_properties' => $totalProperties,
            'verified_landlords' => $verifiedLandlords,
            'cities' => $cities
        ];
    }
}