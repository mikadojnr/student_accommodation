<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Verification;
use App\Models\User;
use App\Services\VerificationService;

class VerificationController
{
    private $verificationService;

    public function __construct()
    {
        $this->verificationService = new VerificationService();
    }

    public function index()
    {
        $this->requireAuth();
        
        $verification = Verification::getByUserId($_SESSION['user_id']);
        $user = User::find($_SESSION['user_id']);
        
        View::render('verification/index', [
            'verification' => $verification,
            'user' => $user
        ]);
    }

    public function uploadDocument()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /verification');
            exit;
        }

        $documentType = $_POST['document_type'] ?? '';
        
        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Please select a valid document';
            header('Location: /verification');
            exit;
        }

        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
        $fileType = $_FILES['document']['type'];
        
        if (!in_array($fileType, $allowedTypes)) {
            $_SESSION['error'] = 'Invalid file type. Please upload JPG, PNG, or PDF files only.';
            header('Location: /verification');
            exit;
        }

        // Create upload directory
        $uploadDir = 'public/uploads/documents/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Generate unique filename
        $fileName = uniqid() . '_' . $_FILES['document']['name'];
        $filePath = $uploadDir . $fileName;
        
        if (move_uploaded_file($_FILES['document']['tmp_name'], $filePath)) {
            // Save to database
            $verification = Verification::getByUserId($_SESSION['user_id']);
            
            if ($verification) {
                // Update existing verification
                $updateData = [
                    $documentType . '_document_path' => '/uploads/documents/' . $fileName,
                    $documentType . '_status' => 'pending',
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                
                Verification::update($verification['id'], $updateData);
            } else {
                // Create new verification record
                Verification::create([
                    'user_id' => $_SESSION['user_id'],
                    $documentType . '_document_path' => '/uploads/documents/' . $fileName,
                    $documentType . '_status' => 'pending'
                ]);
            }

            // Process with third-party service (simulated)
            $this->processDocument($documentType, $filePath);
            
            $_SESSION['success'] = 'Document uploaded successfully! Verification in progress.';
        } else {
            $_SESSION['error'] = 'Failed to upload document';
        }
        
        header('Location: /verification');
        exit;
    }

    public function biometricCapture()
    {
        $this->requireAuth();
        
        View::render('verification/biometric');
    }

    public function processBiometric()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /verification/biometric');
            exit;
        }

        // Get base64 image data from webcam
        $imageData = $_POST['image_data'] ?? '';
        
        if (empty($imageData)) {
            $_SESSION['error'] = 'No image captured';
            header('Location: /verification/biometric');
            exit;
        }

        // Process base64 image
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = str_replace(' ', '+', $imageData);
        $decodedImage = base64_decode($imageData);

        // Save image
        $uploadDir = 'public/uploads/biometric/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = uniqid() . '_biometric.png';
        $filePath = $uploadDir . $fileName;
        
        if (file_put_contents($filePath, $decodedImage)) {
            // Update verification record
            $verification = Verification::getByUserId($_SESSION['user_id']);
            
            $updateData = [
                'biometric_image_path' => '/uploads/biometric/' . $fileName,
                'biometric_status' => 'pending',
                'updated_at' => date('Y-m-d H:i:s')
            ];
            
            if ($verification) {
                Verification::update($verification['id'], $updateData);
            } else {
                $updateData['user_id'] = $_SESSION['user_id'];
                Verification::create($updateData);
            }

            // Process with biometric service (simulated)
            $this->processBiometricVerification($filePath);
            
            $_SESSION['success'] = 'Biometric verification completed!';
            header('Location: /verification');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to save biometric data';
            header('Location: /verification/biometric');
            exit;
        }
    }

    public function checkStatus()
    {
        $this->requireAuth();
        
        $verification = Verification::getByUserId($_SESSION['user_id']);
        
        if (!$verification) {
            echo json_encode(['status' => 'not_started']);
            exit;
        }

        $overallStatus = $this->calculateOverallStatus($verification);
        
        echo json_encode([
            'status' => $overallStatus,
            'identity_status' => $verification['identity_status'],
            'address_status' => $verification['address_status'],
            'student_status' => $verification['student_status'],
            'biometric_status' => $verification['biometric_status']
        ]);
        exit;
    }

    private function processDocument($documentType, $filePath)
    {
        // Simulate third-party verification service processing
        // In real implementation, this would call Jumio, Onfido, or ID.me API
        
        // Simulate processing delay and random result
        $isValid = rand(1, 10) > 2; // 80% success rate for demo
        
        $verification = Verification::getByUserId($_SESSION['user_id']);
        
        $updateData = [
            $documentType . '_status' => $isValid ? 'verified' : 'rejected',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if (!$isValid) {
            $updateData[$documentType . '_rejection_reason'] = 'Document quality insufficient or information mismatch';
        }
        
        Verification::update($verification['id'], $updateData);
    }

    private function processBiometricVerification($filePath)
    {
        // Simulate biometric verification processing
        $isValid = rand(1, 10) > 1; // 90% success rate for demo
        
        $verification = Verification::getByUserId($_SESSION['user_id']);
        
        $updateData = [
            'biometric_status' => $isValid ? 'verified' : 'rejected',
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if (!$isValid) {
            $updateData['biometric_rejection_reason'] = 'Biometric match failed or image quality insufficient';
        }
        
        Verification::update($verification['id'], $updateData);
    }

    private function calculateOverallStatus($verification)
    {
        $statuses = [
            $verification['identity_status'],
            $verification['address_status'],
            $verification['biometric_status']
        ];

        // Add student verification for students
        $user = User::find($_SESSION['user_id']);
        if ($user['user_type'] === 'student') {
            $statuses[] = $verification['student_status'];
        }

        if (in_array('rejected', $statuses)) {
            return 'rejected';
        }
        
        if (in_array('pending', $statuses) || in_array(null, $statuses)) {
            return 'pending';
        }
        
        if (all_verified($statuses)) {
            return 'verified';
        }
        
        return 'not_started';
    }

    private function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login to continue';
            header('Location: /login');
            exit;
        }
    }
}

function all_verified($statuses) {
    foreach ($statuses as $status) {
        if ($status !== 'verified') {
            return false;
        }
    }
    return true;
}
