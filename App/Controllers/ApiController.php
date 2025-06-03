<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Property;
use App\Models\Message;
use App\Models\User;

class ApiController
{
    public function properties()
    {
        $filters = [
            'search' => $_GET['search'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'property_type' => $_GET['property_type'] ?? '',
            'campus_distance' => $_GET['campus_distance'] ?? '',
            'verified_only' => isset($_GET['verified_only']),
            'limit' => min(50, $_GET['limit'] ?? 20),
            'offset' => $_GET['offset'] ?? 0
        ];

        $properties = Property::search($filters);

        View::json([
            'success' => true,
            'data' => $properties,
            'count' => count($properties)
        ]);
    }

    public function sendMessage()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::json(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $recipientId = $input['recipient_id'] ?? '';
        $message = trim($input['message'] ?? '');
        $propertyId = $input['property_id'] ?? null;

        if (empty($recipientId) || empty($message)) {
            View::json(['success' => false, 'error' => 'Missing required fields'], 400);
            return;
        }

        // Verify recipient exists
        $recipient = User::find($recipientId);
        if (!$recipient) {
            View::json(['success' => false, 'error' => 'Recipient not found'], 404);
            return;
        }

        $messageId = Message::sendMessage($_SESSION['user_id'], $recipientId, $message, $propertyId);

        if ($messageId) {
            View::json(['success' => true, 'message_id' => $messageId]);
        } else {
            View::json(['success' => false, 'error' => 'Failed to send message'], 500);
        }
    }

    public function getMessages($conversationId)
    {
        if (!isset($_SESSION['user_id'])) {
            View::json(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }

        // Parse conversation ID (format: userId1-userId2-propertyId)
        $parts = explode('-', $conversationId);
        if (count($parts) < 2) {
            View::json(['success' => false, 'error' => 'Invalid conversation ID'], 400);
            return;
        }

        $otherUserId = $parts[0] == $_SESSION['user_id'] ? $parts[1] : $parts[0];
        $propertyId = $parts[2] ?? null;

        $messages = Message::getConversation($_SESSION['user_id'], $otherUserId, $propertyId);

        View::json([
            'success' => true,
            'data' => $messages
        ]);
    }

    public function saveProperty()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::json(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $propertyId = $input['property_id'] ?? '';

        if (empty($propertyId)) {
            View::json(['success' => false, 'error' => 'Property ID required'], 400);
            return;
        }

        // Check if property exists
        $property = Property::find($propertyId);
        if (!$property) {
            View::json(['success' => false, 'error' => 'Property not found'], 404);
            return;
        }

        // Check if already saved
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT id FROM saved_properties WHERE user_id = ? AND property_id = ?");
        $stmt->execute([$_SESSION['user_id'], $propertyId]);
        $existing = $stmt->fetch();

        if ($existing) {
            // Remove from saved
            $stmt = $db->prepare("DELETE FROM saved_properties WHERE user_id = ? AND property_id = ?");
            $stmt->execute([$_SESSION['user_id'], $propertyId]);
            $saved = false;
        } else {
            // Add to saved
            $stmt = $db->prepare("INSERT INTO saved_properties (user_id, property_id) VALUES (?, ?)");
            $stmt->execute([$_SESSION['user_id'], $propertyId]);
            $saved = true;
        }

        View::json(['success' => true, 'saved' => $saved]);
    }

    public function reportProperty()
    {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::json(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $propertyId = $input['property_id'] ?? '';
        $reason = $input['reason'] ?? '';
        $description = $input['description'] ?? '';

        if (empty($propertyId) || empty($reason)) {
            View::json(['success' => false, 'error' => 'Missing required fields'], 400);
            return;
        }

        // Log the report
        logActivity("Property {$propertyId} reported by user {$_SESSION['user_id']} for: {$reason}. Description: {$description}", 'warning');

        // In a real application, you would save this to a reports table
        // For now, we'll just log it

        View::json(['success' => true, 'message' => 'Report submitted successfully']);
    }

    public function searchUsers()
    {
        if (!isset($_SESSION['user_id'])) {
            View::json(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }

        $query = $_GET['q'] ?? '';
        $type = $_GET['type'] ?? null;

        if (strlen($query) < 2) {
            View::json(['success' => false, 'error' => 'Query too short'], 400);
            return;
        }

        $users = User::search($query, $type, 10);

        // Remove sensitive information
        foreach ($users as $user) {
            unset($user->password, $user->remember_token);
        }

        View::json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function getStats()
    {
        $propertyStats = Property::getStatistics();
        $userStats = User::getUserStatistics();
        $messageStats = Message::getMessageStatistics();

        View::json([
            'success' => true,
            'data' => [
                'properties' => $propertyStats,
                'users' => $userStats,
                'messages' => $messageStats
            ]
        ]);
    }
}
