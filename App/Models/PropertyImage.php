<?php

namespace App\Models;

use App\Core\Model;

class PropertyImage extends Model
{
    protected $table = 'property_images';
    protected $fillable = ['property_id', 'image_path', 'is_primary'];

    public function property()
    {
        return Property::find($this->property_id);
    }

    public static function createForProperty($propertyId, $imagePath, $isPrimary = false)
    {
        return static::create([
            'property_id' => $propertyId,
            'image_path' => $imagePath,
            'is_primary' => $isPrimary ? 1 : 0
        ]);
    }

    public function setPrimary()
    {
        // First, unset all other images as primary for this property
        $sql = "UPDATE property_images SET is_primary = 0 WHERE property_id = ?";
        $this->db->prepare($sql)->execute([$this->property_id]);
        
        // Then set this image as primary
        $this->is_primary = 1;
        return $this->save();
    }
}
