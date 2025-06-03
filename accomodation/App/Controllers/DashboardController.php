<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use App\Models\Property;
use App\Models\Verification;
use App\Models\Message;

class DashboardController
{
    public function index()
    {
        $this->requireAuth();
        
        $user = User::find($_SESSION['user_id']);
        $verification = Verification::getByUserId($_SESSION['user_id']);
        
        if ($_SESSION['user_type'] === 'landlord') {
            $properties = Property::getByUserId($_SESSION['user_id']);
            $totalProperties = count($properties);
            $totalViews = array_sum(array_column($properties, 'views'));
            $recentMessages = Message::getRecentForLandlord($_SESSION['user_id'], 5);
            
            View::render('dashboard/landlord', [
                'user' => $user,
                'verification' => $verification,
                'properties' => $properties,
                'totalProperties' => $totalProperties,
                'totalViews' => $totalViews,
                'recentMessages' => $recentMessages
            ]);
        } else {
            $savedProperties = Property::getSavedByUserId($_SESSION['user_id']);
            $recentSearches = $_SESSION['recent_searches'] ?? [];
            $messages = Message::getByUserId($_SESSION['user_id'], 10);
            
            View::render('dashboard/student', [
                'user' => $user,
                'verification' => $verification,
                'savedProperties' => $savedProperties,
                'recentSearches' => $recentSearches,
                'messages' => $messages
            ]);
        }
    }

    public function profile()
    {
        $this->requireAuth();
        
        $user = User::find($_SESSION['user_id']);
        
        View::render('dashboard/profile', [
            'user' => $user
        ]);
    }

    public function updateProfile()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard/profile');
            exit;
        }

        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'phone' => $_POST['phone'] ?? '',
            'university' => $_POST['university'] ?? '',
            'bio' => $_POST['bio'] ?? ''
        ];

        // Validation
        $errors = [];
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'First name is required';
        }
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Last name is required';
        }
        if (empty($data['phone'])) {
            $errors['phone'] = 'Phone number is required';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: /dashboard/profile');
            exit;
        }

        if (User::update($_SESSION['user_id'], $data)) {
            $_SESSION['success'] = 'Profile updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update profile';
        }
        
        header('Location: /dashboard/profile');
        exit;
    }

    public function security()
    {
        $this->requireAuth();
        
        $user = User::find($_SESSION['user_id']);
        
        View::render('dashboard/security', [
            'user' => $user
        ]);
    }

    public function updatePassword()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /dashboard/security');
            exit;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $user = User::find($_SESSION['user_id']);

        // Validation
        $errors = [];
        
        if (!password_verify($currentPassword, $user['password'])) {
            $errors['current_password'] = 'Current password is incorrect';
        }
        
        if (strlen($newPassword) < 8) {
            $errors['new_password'] = 'New password must be at least 8 characters';
        }
        
        if ($newPassword !== $confirmPassword) {
            $errors['confirm_password'] = 'Passwords do not match';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /dashboard/security');
            exit;
        }

        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        
        if (User::update($_SESSION['user_id'], ['password' => $hashedPassword])) {
            $_SESSION['success'] = 'Password updated successfully!';
        } else {
            $_SESSION['error'] = 'Failed to update password';
        }
        
        header('Location: /dashboard/security');
        exit;
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
