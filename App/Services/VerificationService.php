<?php

namespace App\Services;

class VerificationService
{
    private $jumioApiKey;
    private $jumioApiSecret;
    private $onfidoApiKey;
    
    public function __construct()
    {
        $this->jumioApiKey = $_ENV['JUMIO_API_KEY'] ?? '';
        $this->jumioApiSecret = $_ENV['JUMIO_API_SECRET'] ?? '';
        $this->onfidoApiKey = $_ENV['ONFIDO_API_KEY'] ?? '';
    }

    public function verifyIdentityDocument($documentPath, $documentType)
    {
        // Simulate Jumio ID verification
        return $this->callJumioAPI($documentPath, $documentType);
    }

    public function verifyBiometric($imagePath, $referenceImagePath = null)
    {
        // Simulate Onfido biometric verification
        return $this->callOnfidoAPI($imagePath, $referenceImagePath);
    }

    public function verifyStudentStatus($universityEmail, $studentId)
    {
        // Simulate student verification through university database
        return $this->verifyUniversityCredentials($universityEmail, $studentId);
    }

    private function callJumioAPI($documentPath, $documentType)
    {
        // In a real implementation, this would make HTTP requests to Jumio API
        // For demo purposes, we'll simulate the response
        
        if (empty($this->jumioApiKey)) {
            return [
                'success' => false,
                'error' => 'Jumio API key not configured'
            ];
        }

        // Simulate API call delay
        usleep(500000); // 0.5 second delay

        // Simulate verification result (80% success rate)
        $isValid = rand(1, 10) > 2;
        
        if ($isValid) {
            return [
                'success' => true,
                'verification_id' => 'jumio_' . uniqid(),
                'status' => 'verified',
                'confidence_score' => rand(85, 99),
                'extracted_data' => [
                    'first_name' => 'John',
                    'last_name' => 'Doe',
                    'date_of_birth' => '1995-05-15',
                    'document_number' => 'ID123456789',
                    'expiry_date' => '2025-05-15'
                ]
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Document verification failed',
                'reason' => 'Poor image quality or document not recognized'
            ];
        }
    }

    private function callOnfidoAPI($imagePath, $referenceImagePath)
    {
        // In a real implementation, this would make HTTP requests to Onfido API
        
        if (empty($this->onfidoApiKey)) {
            return [
                'success' => false,
                'error' => 'Onfido API key not configured'
            ];
        }

        // Simulate API call delay
        usleep(750000); // 0.75 second delay

        // Simulate biometric verification result (90% success rate)
        $isValid = rand(1, 10) > 1;
        
        if ($isValid) {
            return [
                'success' => true,
                'verification_id' => 'onfido_' . uniqid(),
                'status' => 'verified',
                'confidence_score' => rand(88, 98),
                'liveness_check' => 'passed',
                'face_match' => $referenceImagePath ? 'matched' : 'not_applicable'
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Biometric verification failed',
                'reason' => 'Liveness check failed or face not clearly visible'
            ];
        }
    }

    private function verifyUniversityCredentials($universityEmail, $studentId)
    {
        // Simulate university verification
        // In reality, this would integrate with university systems or use services like SheerID
        
        // Check if email domain is from a known university
        $universityDomains = [
            'student.university.edu',
            'students.college.edu',
            'mail.university.ac.uk',
            'student.uni.edu'
        ];
        
        $emailDomain = substr(strrchr($universityEmail, "@"), 1);
        $isValidDomain = in_array($emailDomain, $universityDomains);
        
        // Simulate verification result
        $isValid = $isValidDomain && rand(1, 10) > 2; // 80% success rate for valid domains
        
        if ($isValid) {
            return [
                'success' => true,
                'verification_id' => 'univ_' . uniqid(),
                'status' => 'verified',
                'university_name' => 'Sample University',
                'student_status' => 'active',
                'enrollment_year' => date('Y')
            ];
        } else {
            return [
                'success' => false,
                'error' => 'Student verification failed',
                'reason' => 'Invalid university email or student ID not found'
            ];
        }
    }

    public function calculateTrustScore($verificationData)
    {
        $score = 0;
        $maxScore = 100;
        
        // Identity verification (40 points)
        if ($verificationData['identity_status'] === 'verified') {
            $score += 40;
        }
        
        // Address verification (20 points)
        if ($verificationData['address_status'] === 'verified') {
            $score += 20;
        }
        
        // Biometric verification (25 points)
        if ($verificationData['biometric_status'] === 'verified') {
            $score += 25;
        }
        
        // Student verification (15 points)
        if ($verificationData['student_status'] === 'verified') {
            $score += 15;
        }
        
        return min($score, $maxScore);
    }

    public function generateVerificationBadge($trustScore)
    {
        if ($trustScore >= 90) {
            return [
                'level' => 'gold',
                'label' => 'Fully Verified',
                'color' => '#FFD700',
                'icon' => 'shield-check'
            ];
        } elseif ($trustScore >= 70) {
            return [
                'level' => 'silver',
                'label' => 'Verified',
                'color' => '#C0C0C0',
                'icon' => 'shield'
            ];
        } elseif ($trustScore >= 50) {
            return [
                'level' => 'bronze',
                'label' => 'Partially Verified',
                'color' => '#CD7F32',
                'icon' => 'shield-alert'
            ];
        } else {
            return [
                'level' => 'none',
                'label' => 'Unverified',
                'color' => '#999999',
                'icon' => 'shield-x'
            ];
        }
    }
}
