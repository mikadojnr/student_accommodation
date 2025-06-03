<?php

namespace App\Models;

use App\Core\Model;

class Verification extends Model
{
    protected $table = 'verifications';
    protected $fillable = [
        'user_id', 'identity_status', 'address_status', 'student_status', 
        'biometric_status', 'identity_document_path', 'address_document_path', 
        'student_document_path', 'biometric_image_path', 'identity_rejection_reason',
        'address_rejection_reason', 'student_rejection_reason', 'biometric_rejection_reason'
    ];

    public function user()
    {
        return User::find($this->user_id);
    }

    public static function findByUserId($userId)
    {
        $verifications = static::where('user_id', $userId);
        return !empty($verifications) ? $verifications[0] : null;
    }

    public function updateStatus($type, $status, $rejectionReason = null)
    {
        $statusField = $type . '_status';
        $reasonField = $type . '_rejection_reason';
        
        if (property_exists($this, $statusField)) {
            $this->$statusField = $status;
            
            if ($status === 'rejected' && $rejectionReason) {
                $this->$reasonField = $rejectionReason;
            } else {
                $this->$reasonField = null;
            }
            
            return $this->save();
        }
        
        return false;
    }

    public function calculateTrustScore()
    {
        $score = 0;
        
        if ($this->identity_status === 'verified') $score += 30;
        if ($this->address_status === 'verified') $score += 20;
        if ($this->biometric_status === 'verified') $score += 25;
        if ($this->student_status === 'verified') $score += 25;
        
        return $score;
    }

    public function isFullyVerified()
    {
        return $this->identity_status === 'verified' &&
               $this->address_status === 'verified' &&
               $this->biometric_status === 'verified' &&
               ($this->student_status === 'verified' || $this->user()->user_type === 'landlord');
    }

    public function getVerificationBadge()
    {
        $score = $this->calculateTrustScore();
        
        if ($score >= 90) {
            return [
                'level' => 'gold',
                'label' => 'Fully Verified',
                'color' => '#FFD700',
                'icon' => 'shield-check'
            ];
        } elseif ($score >= 70) {
            return [
                'level' => 'silver',
                'label' => 'Verified',
                'color' => '#C0C0C0',
                'icon' => 'shield'
            ];
        } elseif ($score >= 50) {
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
