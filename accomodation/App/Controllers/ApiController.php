<?php

namespace App\Controllers;

use App\Models\Property;
use App\Models\Message;
use App\Models\User;

class ApiController
{
    public function searchProperties()
    {
        header('Content-Type: application/json');
        
        $query = $_GET['q'] ?? '';
        $limit = min(($_GET['limit'] ?? 10), 50);
        
        if (strlen($query) < 2) {
            echo json_encode(['properties' => []]);
            exit;
        }

        $properties = Property::search($query, $limit);
        
        echo json_encode(['properties' => $properties]);
        exit;
    }

    public function sendMessage()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $propertyId = $input['property_id'] ?? null;
        $recipientId = $input['recipient_id'] ?? null;
        $message = $input['message'] ?? '';

        if (!$propertyId || !$recipientId || empty($message)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit;
        }

        // Validate property exists
        $property = Property::find($propertyId);
        if (!$property) {
            http_response_code(404);
            echo json_encode(['error' => 'Property not found']);
            exit;
        }

        // Validate recipient exists
        $recipient = User::find($recipientId);
        if (!$recipient) {
            http_response_code(404);
            echo json_encode(['error' => 'Recipient not found']);
            exit;
        }

        // Check for spam/scam content
        if ($this->containsScamContent($message)) {
            http_response_code(400);
            echo json_encode(['error' => 'Message contains prohibited content']);
            exit;
        }

        $messageId = Message::create([
            'sender_id' => $_SESSION['user_id'],
            'recipient_id' => $recipientId,
            'property_id' => $propertyId,
            'message' => $message,
            'is_encrypted' => 1
        ]);

        if ($messageId) {
            echo json_encode(['success' => true, 'message_id' => $messageId]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to send message']);
        }
        exit;
    }

    public function getMessages()
    {
        $this->requireAuth();
        
        $propertyId = $_GET['property_id'] ?? null;
        $otherUserId = $_GET['user_id'] ?? null;
        
        if (!$propertyId || !$otherUserId) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required parameters']);
            exit;
        }

        $messages = Message::getConversation($_SESSION['user_id'], $otherUserId, $propertyId);
        
        echo json_encode(['messages' => $messages]);
        exit;
    }

    public function reportProperty()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        
        $propertyId = $input['property_id'] ?? null;
        $reason = $input['reason'] ?? '';
        $description = $input['description'] ?? '';

        if (!$propertyId || empty($reason)) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing required fields']);
            exit;
        }

        // Log the report (in a real system, this would go to a reports table)
        error_log("Property Report - Property ID: $propertyId, Reason: $reason, User: {$_SESSION['user_id']}");
        
        echo json_encode(['success' => true, 'message' => 'Report submitted successfully']);
        exit;
    }

    public function saveProperty()
    {
        $this->requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        $propertyId = $input['property_id'] ?? null;

        if (!$propertyId) {
            http_response_code(400);
            echo json_encode(['error' => 'Property ID required']);
            exit;
        }

        // Check if property exists
        $property = Property::find($propertyId);
        if (!$property) {
            http_response_code(404);
            echo json_encode(['error' => 'Property not found']);
            exit;
        }

        // Toggle save status
        $isSaved = Property::toggleSave($_SESSION['user_id'], $propertyId);
        
        echo json_encode(['success' => true, 'saved' => $isSaved]);
        exit;
    }

    public function getPropertyStats()
    {
        $this->requireAuth();
        
        $propertyId = $_GET['property_id'] ?? null;
        
        if (!$propertyId) {
            http_response_code(400);
            echo json_encode(['error' => 'Property ID required']);
            exit;
        }

        $property = Property::find($propertyId);
        
        if (!$property || $property['user_id'] != $_SESSION['user_id']) {
            http_response_code(403);
            echo json_encode(['error' => 'Access denied']);
            exit;
        }

        $stats = [
            'views' => $property['views'],
            'saves' => Property::getSaveCount($propertyId),
            'messages' => Message::getCountForProperty($propertyId),
            'inquiries_today' => Message::getCountForPropertyToday($propertyId)
        ];
        
        echo json_encode($stats);
        exit;
    }

    private function containsScamContent($message)
    {
        $scamPhrases = [
            'wire transfer',
            'western union',
            'moneygram',
            'bitcoin',
            'cryptocurrency',
            'urgent payment',
            'send money immediately',
            'pay outside platform',
            'cash only',
            'no viewing necessary'
        ];

        $message = strtolower($message);
        
        foreach ($scamPhrases as $phrase) {
            if (strpos($message, $phrase) !== false) {
                return true;
            }
        }
        
        return false;
    }

    private function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Authentication required']);
            exit;
        }
    }
}
