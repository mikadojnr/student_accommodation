<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use App\Models\Verification;
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
        if (!isset($_SESSION['user_id'])) {
            View::redirect('/login');
            return;
        }

        $user = User::find($_SESSION['user_id']);
        $verification = Verification::findByUser($_SESSION['user_id']);

        View::render('verification/index', [
            'title' => 'Identity Verification - SecureStay',
            'user' => $user,
            'verification' => $verification
        ]);
    }

    public function uploadDocument()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::redirect('/verification');
            return;
        }

        $documentType = $_POST['document_type'] ?? '';
        $allowedTypes = ['identity', 'address', 'student'];

        if (!in_array($documentType, $allowedTypes)) {
            $_SESSION['error'] = 'Invalid document type';
            View::redirect('/verification');
            return;
        }

        if (!isset($_FILES['document']) || $_FILES['document']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = 'Please select a valid document file';
            View::redirect('/verification');
            return;
        }

        $file = $_FILES['document'];
        
        // Validate file
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($fileExtension, $allowedExtensions)) {
            $_SESSION['error'] = 'Invalid file type. Please upload JPG, PNG, or PDF files only.';
            View::redirect('/verification');
            return;
        }

        if ($file['size'] > 5 * 1024 * 1024) { // 5MB limit
            $_SESSION['error'] = 'File size too large. Maximum 5MB allowed.';
            View::redirect('/verification');
            return;
        }

        // Upload file
        $uploadDir = "public/uploads/verification/{$_SESSION['user_id']}/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = $documentType . '_' . time() . '.' . $fileExtension;
        $filePath = $uploadDir . $fileName;

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $relativePath = "/uploads/verification/{$_SESSION['user_id']}/{$fileName}";
            
            // Save to database
            $verificationModel = new Verification();
            $verificationModel->uploadDocument($_SESSION['user_id'], $documentType, $relativePath);

            $_SESSION['success'] = ucfirst($documentType) . ' document uploaded successfully. It will be reviewed within 24 hours.';
            
            // Log activity
            logActivity("Document uploaded: {$documentType} by user {$_SESSION['user_id']}");
        } else {
            $_SESSION['error'] = 'Failed to upload document. Please try again.';
        }

        View::redirect('/verification');
    }

    public function biometric()
    {
        if (!isset($_SESSION['user_id'])) {
            View::redirect('/login');
            return;
        }

        View::render('verification/biometric', [
            'title' => 'Biometric Verification - SecureStay'
        ]);
    }

    public function processBiometric()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::redirect('/verification');
            return;
        }

        $imageData = $_POST['image_data'] ?? '';
        
        if (empty($imageData)) {
            $_SESSION['error'] = 'No image data received';
            View::redirect('/verification/biometric');
            return;
        }

        // Decode base64 image
        $imageData = str_replace('data:image/png;base64,', '', $imageData);
        $imageData = base64_decode($imageData);

        if ($imageData === false) {
            $_SESSION['error'] = 'Invalid image data';
            View::redirect('/verification/biometric');
            return;
        }

        // Save image
        $uploadDir = "public/uploads/verification/{$_SESSION['user_id']}/";
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $fileName = 'biometric_' . time() . '.png';
        $filePath = $uploadDir . $fileName;

        if (file_put_contents($filePath, $imageData)) {
            $relativePath = "/uploads/verification/{$_SESSION['user_id']}/{$fileName}";
            
            // Process biometric verification
            $result = $this->verificationService->verifyBiometric($filePath);
            
            $verificationModel = new Verification();
            if ($result['success']) {
                $verificationModel->updateVerificationStatus($_SESSION['user_id'], 'biometric', 'verified');
                $_SESSION['success'] = 'Biometric verification completed successfully!';
                
                // Log activity
                logActivity("Biometric verification successful for user {$_SESSION['user_id']}");
            } else {
                $verificationModel->updateVerificationStatus($_SESSION['user_id'], 'biometric', 'rejected', $result['error']);
                $_SESSION['error'] = 'Biometric verification failed: ' . $result['error'];
                
                // Log activity
                logActivity("Biometric verification failed for user {$_SESSION['user_id']}: " . $result['error'], 'warning');
            }
        } else {
            $_SESSION['error'] = 'Failed to save biometric image. Please try again.';
        }

        View::redirect('/verification');
    }

    public function admin()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
            View::redirect('/');
            return;
        }

        $pendingVerifications = Verification::getPendingVerifications();
        $stats = Verification::getVerificationStatistics();

        View::render('admin/verifications', [
            'title' => 'Verification Management - SecureStay',
            'pending' => $pendingVerifications,
            'stats' => $stats
        ]);
    }

    public function approve()
    {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::redirect('/');
            return;
        }

        $userId = $_POST['user_id'] ?? '';
        $type = $_POST['type'] ?? '';
        $action = $_POST['action'] ?? '';
        $reason = $_POST['reason'] ?? '';

        $verificationModel = new Verification();
        
        if ($action === 'approve') {
            $verificationModel->updateVerificationStatus($userId, $type, 'verified');
            $_SESSION['success'] = ucfirst($type) . ' verification approved';
            
            // Update user verification status if all verifications are complete
            $this->updateUserVerificationStatus($userId);
        } elseif ($action === 'reject') {
            $verificationModel->updateVerificationStatus($userId, $type, 'rejected', $reason);
            $_SESSION['success'] = ucfirst($type) . ' verification rejected';
        }

        View::redirect('/admin/verifications');
    }

    private function updateUserVerificationStatus($userId)
    {
        $verification = Verification::findByUser($userId);
        $user = User::find($userId);
        
        if (!$verification || !$user) {
            return;
        }

        $requiredVerifications = ['identity', 'address', 'biometric'];
        if ($user->user_type === 'student') {
            $requiredVerifications[] = 'student';
        }

        $allVerified = true;
        foreach ($requiredVerifications as $type) {
            $status = $verification->{$type . '_status'};
            if ($status !== 'verified') {
                $allVerified = false;
                break;
            }
        }

        if ($allVerified) {
            $userModel = new User();
            $userModel->update($userId, ['verification_status' => 'verified']);
        }
    }
}
