<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use App\Models\Property;
use App\Models\Message;
use App\Models\Verification;

class DashboardController
{
    public function index()
    {
        if (!isset($_SESSION['user_id'])) {
            View::redirect('/login');
            return;
        }

        $user = User::find($_SESSION['user_id']);
        
        if ($user->user_type === 'student') {
            $this->studentDashboard($user);
        } else {
            $this->landlordDashboard($user);
        }
    }

    private function studentDashboard($user)
    {
        // Get saved properties
        $savedProperties = Property::where(['saved_by' => $user->id], 10);
        
        // Get recent messages
        $recentMessages = Message::getConversations($user->id, 5);
        
        // Get verification status
        $verification = Verification::findByUser($user->id);
        $trustScore = $verification ? (new Verification())->calculateTrustScore($user->id) : 0;
        
        // Get recommended properties
        $recommendedProperties = Property::getFeatured(6);

        View::render('dashboard/student', [
            'title' => 'Student Dashboard - SecureStay',
            'user' => $user,
            'savedProperties' => $savedProperties,
            'recentMessages' => $recentMessages,
            'verification' => $verification,
            'trustScore' => $trustScore,
            'recommendedProperties' => $recommendedProperties
        ]);
    }

    private function landlordDashboard($user)
    {
        // Get landlord's properties
        $properties = Property::getByUser($user->id);
        
        // Get recent messages
        $recentMessages = Message::getConversations($user->id, 5);
        
        // Get verification status
        $verification = Verification::findByUser($user->id);
        $trustScore = $verification ? (new Verification())->calculateTrustScore($user->id) : 0;
        
        // Calculate statistics
        $stats = [
            'total_properties' => count($properties),
            'active_properties' => count(array_filter($properties, fn($p) => $p->status === 'active')),
            'total_views' => array_sum(array_column($properties, 'views')),
            'unread_messages' => Message::getUnreadCount($user->id)
        ];

        View::render('dashboard/landlord', [
            'title' => 'Landlord Dashboard - SecureStay',
            'user' => $user,
            'properties' => $properties,
            'recentMessages' => $recentMessages,
            'verification' => $verification,
            'trustScore' => $trustScore,
            'stats' => $stats
        ]);
    }

    public function profile()
    {
        if (!isset($_SESSION['user_id'])) {
            View::redirect('/login');
            return;
        }

        $userModel = new User();
        $user = $userModel->getProfile($_SESSION['user_id']);

        View::render('dashboard/profile', [
            'title' => 'Profile - SecureStay',
            'user' => $user
        ]);
    }

    public function updateProfile()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::redirect('/dashboard/profile');
            return;
        }

        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'phone' => trim($_POST['phone'] ?? ''),
            'university' => trim($_POST['university'] ?? ''),
            'bio' => trim($_POST['bio'] ?? '')
        ];

        // Validation
        $errors = [];
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'First name is required';
        }
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Last name is required';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            View::redirect('/dashboard/profile');
            return;
        }

        $userModel = new User();
        if ($userModel->update($_SESSION['user_id'], $data)) {
            $_SESSION['success'] = 'Profile updated successfully';
            $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
        } else {
            $_SESSION['error'] = 'Failed to update profile';
        }

        View::redirect('/dashboard/profile');
    }

    public function security()
    {
        if (!isset($_SESSION['user_id'])) {
            View::redirect('/login');
            return;
        }

        View::render('dashboard/security', [
            'title' => 'Security Settings - SecureStay'
        ]);
    }

    public function changePassword()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::redirect('/dashboard/security');
            return;
        }

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        // Validation
        $errors = [];
        $user = User::find($_SESSION['user_id']);
        
        if (!password_verify($currentPassword, $user->password)) {
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
            View::redirect('/dashboard/security');
            return;
        }

        $userModel = new User();
        if ($userModel->updatePassword($_SESSION['user_id'], $newPassword)) {
            $_SESSION['success'] = 'Password changed successfully';
            
            // Log activity
            logActivity("Password changed for user {$_SESSION['user_id']}");
        } else {
            $_SESSION['error'] = 'Failed to change password';
        }

        View::redirect('/dashboard/security');
    }

    public function messages()
    {
        if (!isset($_SESSION['user_id'])) {
            View::redirect('/login');
            return;
        }

        $conversations = Message::getConversations($_SESSION['user_id']);

        View::render('dashboard/messages', [
            'title' => 'Messages - SecureStay',
            'conversations' => $conversations
        ]);
    }

    public function conversation($otherUserId, $propertyId = null)
    {
        if (!isset($_SESSION['user_id'])) {
            View::redirect('/login');
            return;
        }

        $messages = Message::getConversation($_SESSION['user_id'], $otherUserId, $propertyId);
        $otherUser = User::find($otherUserId);
        $property = $propertyId ? Property::find($propertyId) : null;

        // Mark messages as read
        $messageModel = new Message();
        $messageModel->markConversationAsRead($_SESSION['user_id'], $otherUserId, $propertyId);

        View::render('dashboard/conversation', [
            'title' => 'Conversation - SecureStay',
            'messages' => $messages,
            'otherUser' => $otherUser,
            'property' => $property
        ]);
    }
}
