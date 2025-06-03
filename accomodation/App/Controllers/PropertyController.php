<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\User;

class PropertyController
{
    public function index()
    {
        $filters = [
            'min_price' => $_GET['min_price'] ?? null,
            'max_price' => $_GET['max_price'] ?? null,
            'property_type' => $_GET['property_type'] ?? null,
            'campus_distance' => $_GET['campus_distance'] ?? null,
            'verified_only' => isset($_GET['verified_only']),
            'search' => $_GET['search'] ?? null
        ];

        $properties = Property::getFiltered($filters);
        
        View::render('properties/index', [
            'properties' => $properties,
            'filters' => $filters
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

        $images = PropertyImage::getByPropertyId($id);
        $landlord = User::find($property['user_id']);

        View::render('properties/show', [
            'property' => $property,
            'images' => $images,
            'landlord' => $landlord
        ]);
    }

    public function create()
    {
        $this->requireAuth();
        $this->requireLandlord();
        
        View::render('properties/create');
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
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'property_type' => $_POST['property_type'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'deposit' => $_POST['deposit'] ?? 0,
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'postal_code' => $_POST['postal_code'] ?? '',
            'bedrooms' => $_POST['bedrooms'] ?? 1,
            'bathrooms' => $_POST['bathrooms'] ?? 1,
            'campus_distance' => $_POST['campus_distance'] ?? 0,
            'amenities' => $_POST['amenities'] ?? [],
            'user_id' => $_SESSION['user_id']
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
            $_SESSION['error'] = 'Failed to create property';
            header('Location: /properties/create');
            exit;
        }
    }

    public function edit($id)
    {
        $this->requireAuth();
        
        $property = Property::find($id);
        
        if (!$property || $property['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Property not found or access denied';
            header('Location: /dashboard');
            exit;
        }

        $images = PropertyImage::getByPropertyId($id);

        View::render('properties/edit', [
            'property' => $property,
            'images' => $images
        ]);
    }

    public function update($id)
    {
        $this->requireAuth();
        
        $property = Property::find($id);
        
        if (!$property || $property['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Property not found or access denied';
            header('Location: /dashboard');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /properties/' . $id . '/edit');
            exit;
        }

        $data = [
            'title' => $_POST['title'] ?? '',
            'description' => $_POST['description'] ?? '',
            'property_type' => $_POST['property_type'] ?? '',
            'price' => $_POST['price'] ?? 0,
            'deposit' => $_POST['deposit'] ?? 0,
            'address' => $_POST['address'] ?? '',
            'city' => $_POST['city'] ?? '',
            'postal_code' => $_POST['postal_code'] ?? '',
            'bedrooms' => $_POST['bedrooms'] ?? 1,
            'bathrooms' => $_POST['bathrooms'] ?? 1,
            'campus_distance' => $_POST['campus_distance'] ?? 0,
            'amenities' => $_POST['amenities'] ?? []
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
            $_SESSION['error'] = 'Failed to update property';
            header('Location: /properties/' . $id . '/edit');
            exit;
        }
    }

    public function delete($id)
    {
        $this->requireAuth();
        
        $property = Property::find($id);
        
        if (!$property || $property['user_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Property not found or access denied';
            header('Location: /dashboard');
            exit;
        }

        if (Property::delete($id)) {
            $_SESSION['success'] = 'Property deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete property';
        }
        
        header('Location: /dashboard');
        exit;
    }

    private function validateProperty($data)
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors['title'] = 'Title is required';
        }

        if (empty($data['description'])) {
            $errors['description'] = 'Description is required';
        }

        if (empty($data['property_type'])) {
            $errors['property_type'] = 'Property type is required';
        }

        if (empty($data['price']) || $data['price'] <= 0) {
            $errors['price'] = 'Valid price is required';
        }

        if (empty($data['address'])) {
            $errors['address'] = 'Address is required';
        }

        if (empty($data['city'])) {
            $errors['city'] = 'City is required';
        }

        return $errors;
    }

    private function handleImageUploads($propertyId)
    {
        if (!isset($_FILES['images'])) {
            return;
        }

        $uploadDir = 'public/uploads/properties/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $files = $_FILES['images'];
        $fileCount = count($files['name']);

        for ($i = 0; $i < $fileCount; $i++) {
            if ($files['error'][$i] === UPLOAD_ERR_OK) {
                $fileName = uniqid() . '_' . $files['name'][$i];
                $filePath = $uploadDir . $fileName;
                
                if (move_uploaded_file($files['tmp_name'][$i], $filePath)) {
                    PropertyImage::create([
                        'property_id' => $propertyId,
                        'image_path' => '/uploads/properties/' . $fileName,
                        'is_primary' => $i === 0 ? 1 : 0
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
