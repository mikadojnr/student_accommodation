<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;
use App\Models\Verification;

class PropertyController
{
    public function index()
    {
        // Get filter parameters
        $filters = [
            'min_price' => $_GET['min_price'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'property_type' => $_GET['property_type'] ?? null,
            'city' => $_GET['city'] ?? null,
            'campus_distance' => $_GET['campus_distance'] ?? null,
            'verified_only' => isset($_GET['verified_only']),
            'search' => $_GET['search'] ?? null,
            'bedrooms' => $_GET['bedrooms'] ?? null,
            'amenities' => $_GET['amenities'] ?? []
        ];

        // Get properties with filters
        $properties = Property::getFiltered($filters);
        
        // Get unique cities for filter dropdown
        $cities = Property::getUniqueCities();
        
        // Get property types for filter
        $propertyTypes = ['room', 'studio', 'apartment', 'house'];
        
        // Get common amenities for filter
        $commonAmenities = [
            'WiFi', 'Parking', 'Garden', 'Laundry', 'Gym', 
            'Bills Included', 'Furnished', 'Pet Friendly', 
            'Bike Storage', 'Security Entry'
        ];

        View::output('properties/index', [
            'title' => 'Find Student Accommodation - SecureStay',
            'properties' => $properties,
            'filters' => $filters,
            'cities' => $cities,
            'propertyTypes' => $propertyTypes,
            'commonAmenities' => $commonAmenities,
            'totalProperties' => count($properties)
        ]);
    }

    public function show($id)
    {
        $property = Property::findWithDetails($id);
        
        if (!$property) {
            $_SESSION['error'] = 'Property not found';
            header('Location: /properties');
            exit;
        }

        // Increment view count
        Property::incrementViews($id);

        // Get property images
        $images = PropertyImage::getByPropertyId($id);
        
        // Get landlord information
        $landlord = User::find($property->user_id);
        
        // Get landlord verification status
        $landlordVerification = null;
        if ($landlord) {
            $landlordVerification = Verification::getByUserId($landlord->id);
        }

        // Get similar properties
        $similarProperties = Property::getSimilar($id, $property->city, $property->property_type, 3);

        // Check if current user has saved this property
        $isSaved = false;
        if (isset($_SESSION['user_id'])) {
            $isSaved = Property::isSavedByUser($_SESSION['user_id'], $id);
        }

        View::output('properties/show', [
            'title' => htmlspecialchars($property->title) . ' - SecureStay',
            'property' => $property,
            'images' => $images,
            'landlord' => $landlord,
            'landlordVerification' => $landlordVerification,
            'similarProperties' => $similarProperties,
            'isSaved' => $isSaved
        ]);
    }

    public function create()
    {
        $this->requireAuth();
        $this->requireLandlord();
        
        // Get common amenities for checkboxes
        $commonAmenities = [
            'WiFi', 'Parking', 'Garden', 'Laundry', 'Gym', 
            'Bills Included', 'Furnished', 'Pet Friendly', 
            'Bike Storage', 'Security Entry', 'Central Heating',
            'Dishwasher', 'Washing Machine', 'Dryer', 'Balcony'
        ];
        
        View::output('properties/create', [
            'title' => 'Add Property - SecureStay',
            'commonAmenities' => $commonAmenities,
            'propertyTypes' => ['room', 'studio', 'apartment', 'house']
        ]);
    }

    public function store()
    {
        $this->requireAuth();
        $this->requireLandlord();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /properties/create');
            exit;
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'property_type' => $_POST['property_type'] ?? '',
            'price' => floatval($_POST['price'] ?? 0),
            'deposit' => floatval($_POST['deposit'] ?? 0),
            'address' => trim($_POST['address'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'postal_code' => trim($_POST['postal_code'] ?? ''),
            'bedrooms' => intval($_POST['bedrooms'] ?? 1),
            'bathrooms' => intval($_POST['bathrooms'] ?? 1),
            'campus_distance' => floatval($_POST['campus_distance'] ?? 0),
            'amenities' => $_POST['amenities'] ?? [],
            'user_id' => $_SESSION['user_id'],
            'is_available' => 1
        ];

        // Validation
        $errors = $this->validateProperty($data);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: /properties/create');
            exit;
        }

        // Convert amenities array to JSON
        $data['amenities'] = json_encode($data['amenities']);

        $propertyId = Property::create($data);

        if ($propertyId) {
            // Handle image uploads
            $this->handleImageUploads($propertyId);
            
            $_SESSION['success'] = 'Property created successfully!';
            header('Location: /properties/' . $propertyId);
            exit;
        } else {
            $_SESSION['error'] = 'Failed to create property. Please try again.';
            $_SESSION['old'] = $data;
            header('Location: /properties/create');
            exit;
        }
    }

    public function edit($id)
    {
        $this->requireAuth();
        
        $property = Property::find($id);
        
        if (!$property || $property->user_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Property not found or access denied';
            header('Location: /dashboard');
            exit;
        }

        $images = PropertyImage::getByPropertyId($id);
        
        // Decode amenities for editing
        $property->amenities = json_decode($property->amenities, true) ?? [];
        
        $commonAmenities = [
            'WiFi', 'Parking', 'Garden', 'Laundry', 'Gym', 
            'Bills Included', 'Furnished', 'Pet Friendly', 
            'Bike Storage', 'Security Entry', 'Central Heating',
            'Dishwasher', 'Washing Machine', 'Dryer', 'Balcony'
        ];

        View::output('properties/edit', [
            'title' => 'Edit Property - SecureStay',
            'property' => $property,
            'images' => $images,
            'commonAmenities' => $commonAmenities,
            'propertyTypes' => ['room', 'studio', 'apartment', 'house']
        ]);
    }

    public function update($id)
    {
        $this->requireAuth();
        
        $property = Property::find($id);
        
        if (!$property || $property->user_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Property not found or access denied';
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /properties/' . $id . '/edit');
            exit;
        }

        $data = [
            'title' => trim($_POST['title'] ?? ''),
            'description' => trim($_POST['description'] ?? ''),
            'property_type' => $_POST['property_type'] ?? '',
            'price' => floatval($_POST['price'] ?? 0),
            'deposit' => floatval($_POST['deposit'] ?? 0),
            'address' => trim($_POST['address'] ?? ''),
            'city' => trim($_POST['city'] ?? ''),
            'postal_code' => trim($_POST['postal_code'] ?? ''),
            'bedrooms' => intval($_POST['bedrooms'] ?? 1),
            'bathrooms' => intval($_POST['bathrooms'] ?? 1),
            'campus_distance' => floatval($_POST['campus_distance'] ?? 0),
            'amenities' => $_POST['amenities'] ?? [],
            'is_available' => isset($_POST['is_available']) ? 1 : 0
        ];

        // Validation
        $errors = $this->validateProperty($data);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: /properties/' . $id . '/edit');
            exit;
        }

        // Convert amenities array to JSON
        $data['amenities'] = json_encode($data['amenities']);

        if (Property::update($id, $data)) {
            // Handle new image uploads
            $this->handleImageUploads($id);
            
            $_SESSION['success'] = 'Property updated successfully!';
            header('Location: /properties/' . $id);
            exit;
        } else {
            $_SESSION['error'] = 'Failed to update property. Please try again.';
            $_SESSION['old'] = $data;
            header('Location: /properties/' . $id . '/edit');
            exit;
        }
    }

    public function delete($id)
    {
        $this->requireAuth();
        
        $property = Property::find($id);
        
        if (!$property || $property->user_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Property not found or access denied';
            header('Location: /dashboard');
            exit;
        }

        if (Property::delete($id)) {
            $_SESSION['success'] = 'Property deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete property. Please try again.';
        }
        
        header('Location: /dashboard');
        exit;
    }

    public function toggleAvailability($id)
    {
        $this->requireAuth();
        
        $property = Property::find($id);
        
        if (!$property || $property->user_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Property not found or access denied';
            header('Location: /dashboard');
            exit;
        }

        $newStatus = $property->is_available ? 0 : 1;
        
        if (Property::update($id, ['is_available' => $newStatus])) {
            $statusText = $newStatus ? 'available' : 'unavailable';
            $_SESSION['success'] = "Property marked as {$statusText}!";
        } else {
            $_SESSION['error'] = 'Failed to update property status.';
        }
        
        header('Location: /dashboard');
        exit;
    }

    public function saveProperty($id)
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /properties/' . $id);
            exit;
        }

        $userId = $_SESSION['user_id'];
        
        if (Property::isSavedByUser($userId, $id)) {
            // Unsave property
            if (Property::unsaveProperty($userId, $id)) {
                $_SESSION['success'] = 'Property removed from saved list!';
            } else {
                $_SESSION['error'] = 'Failed to remove property from saved list.';
            }
        } else {
            // Save property
            if (Property::saveProperty($userId, $id)) {
                $_SESSION['success'] = 'Property saved successfully!';
            } else {
                $_SESSION['error'] = 'Failed to save property.';
            }
        }
        
        header('Location: /properties/' . $id);
        exit;
    }

    public function reportProperty($id)
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /properties/' . $id);
            exit;
        }

        $reason = $_POST['reason'] ?? '';
        $description = trim($_POST['description'] ?? '');
        
        if (empty($reason)) {
            $_SESSION['error'] = 'Please select a reason for reporting.';
            header('Location: /properties/' . $id);
            exit;
        }

        $reportData = [
            'reporter_id' => $_SESSION['user_id'],
            'property_id' => $id,
            'reason' => $reason,
            'description' => $description,
            'status' => 'pending'
        ];

        if (Property::createReport($reportData)) {
            $_SESSION['success'] = 'Property reported successfully. We will review it shortly.';
        } else {
            $_SESSION['error'] = 'Failed to submit report. Please try again.';
        }
        
        header('Location: /properties/' . $id);
        exit;
    }

    private function validateProperty($data)
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        } elseif (strlen($data['title']) < 10) {
            $errors['title'] = 'Title must be at least 10 characters long';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Description is required';
        } elseif (strlen($data['description']) < 50) {
            $errors['description'] = 'Description must be at least 50 characters long';
        }

        if (empty($data['property_type'])) {
            $errors['property_type'] = 'Property type is required';
        } elseif (!in_array($data['property_type'], ['room', 'studio', 'apartment', 'house'])) {
            $errors['property_type'] = 'Invalid property type';
        }

        if (empty($data['price']) || $data['price'] <= 0) {
            $errors['price'] = 'Valid price is required';
        } elseif ($data['price'] > 10000) {
            $errors['price'] = 'Price seems too high. Please check the amount.';
        }

        if (empty($data['deposit']) || $data['deposit'] < 0) {
            $errors['deposit'] = 'Valid deposit amount is required';
        }

        if (empty($data['address'])) {
            $errors['address'] = 'Address is required';
        }

        if (empty($data['city'])) {
            $errors['city'] = 'City is required';
        }

        if (empty($data['postal_code'])) {
            $errors['postal_code'] = 'Postal code is required';
        }

        if ($data['bedrooms'] < 1 || $data['bedrooms'] > 10) {
            $errors['bedrooms'] = 'Number of bedrooms must be between 1 and 10';
        }

        if ($data['bathrooms'] < 1 || $data['bathrooms'] > 10) {
            $errors['bathrooms'] = 'Number of bathrooms must be between 1 and 10';
        }

        if ($data['campus_distance'] < 0 || $data['campus_distance'] > 50) {
            $errors['campus_distance'] = 'Campus distance must be between 0 and 50 km';
        }

        return $errors;
    }

    private function handleImageUploads($propertyId)
    {
        if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
            return;
        }

        $uploadDir = 'public/uploads/properties/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $files = $_FILES['images'];
        $fileCount = count($files['name']);
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxFileSize = 5 * 1024 * 1024; // 5MB

        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                // Validate file type
                $fileType = $files['type'][$i];
                if (!in_array($fileType, $allowedTypes)) {
                    continue;
                }

                // Validate file size
                if ($files['size'][$i] > $maxFileSize) {
                    continue;
                }

                // Generate unique filename
                $extension = pathinfo($files['name'][$i], PATHINFO_EXTENSION);
                $fileName = uniqid() . '_' . time() . '.' . $extension;
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($files['tmp_name'][$i], $filePath)) {
                    // Check if this is the first image (make it primary)
                    $existingImages = PropertyImage::getByPropertyId($propertyId);
                    $isPrimary = empty($existingImages) ? 1 : 0;
                    
                    PropertyImage::create([
                        'property_id' => $propertyId,
                        'image_path' => '/uploads/properties/' . $fileName,
                        'is_primary' => $isPrimary
                    ]);
                }
            }
        }
    }

    private function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to continue';
            header('Location: /login');
            exit;
        }
    }

    private function requireLandlord()
    {
        if ($_SESSION['user_type'] !== 'landlord') {
            $_SESSION['error'] = 'Access denied. Landlord account required.';
            header('Location: /dashboard');
            exit;
        }
    }
}
