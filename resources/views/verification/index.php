<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="text-center mb-8">
                <h1 class="font-heading font-bold text-3xl text-gray-900 mb-2">Identity Verification</h1>
                <p class="text-gray-600">Complete your verification to build trust and access all features</p>
            </div>

            <!-- Verification Progress -->
            <div class="mb-8">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-sm font-medium text-gray-700">Verification Progress</span>
                    <span class="text-sm text-gray-600">
                        <?php 
                        $completed = 0;
                        $total = 4;
                        if ($verification) {
                            if ($verification['identity_status'] === 'verified') $completed++;
                            if ($verification['address_status'] === 'verified') $completed++;
                            if ($verification['biometric_status'] === 'verified') $completed++;
                            if ($user['user_type'] === 'student' && $verification['student_status'] === 'verified') $completed++;
                            if ($user['user_type'] === 'landlord') $total = 3;
                        }
                        echo round(($completed / $total) * 100) . '%';
                        ?>
                    </span>
                </div>
                <div class="trust-meter">
                    <div class="trust-meter-fill high" style="width: <?= round(($completed / $total) * 100) ?>%"></div>
                </div>
            </div>

            <!-- Verification Steps -->
            <div class="space-y-6">
                <!-- Identity Verification -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-primary bg-opacity-10 rounded-full flex items-center justify-center">
                                <i class="fas fa-id-card text-primary"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Identity Verification</h3>
                                <p class="text-gray-600 text-sm">Upload government-issued ID</p>
                            </div>
                        </div>
                        
                        <?php if ($verification && $verification['identity_status'] === 'verified'): ?>
                            <span class="verification-badge gold">
                                <i class="fas fa-shield-check"></i>
                                Verified
                            </span>
                        <?php elseif ($verification && $verification['identity_status'] === 'pending'): ?>
                            <span class="verification-badge bronze">
                                <i class="fas fa-clock"></i>
                                Pending
                            </span>
                        <?php elseif ($verification && $verification['identity_status'] === 'rejected'): ?>
                            <span class="verification-badge none">
                                <i class="fas fa-times"></i>
                                Rejected
                            </span>
                        <?php else: ?>
                            <span class="verification-badge none">
                                <i class="fas fa-clock"></i>
                                Not Started
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!$verification || $verification['identity_status'] !== 'verified'): ?>
                        <form action="/verification/upload-document" method="POST" enctype="multipart/form-data" class="space-y-4">
                            <input type="hidden" name="document_type" value="identity">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Document Type</label>
                                <select name="id_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                    <option value="">Select ID Type</option>
                                    <option value="passport">Passport</option>
                                    <option value="driving_license">Driving License</option>
                                    <option value="national_id">National ID Card</option>
                                </select>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Document</label>
                                <input type="file" name="document" accept="image/*,.pdf" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <p class="text-sm text-gray-500 mt-1">Accepted formats: JPG, PNG, PDF (max 5MB)</p>
                            </div>
                            
                            <button type="submit" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Upload Identity Document
                            </button>
                        </form>
                    <?php endif; ?>
                    
                    <?php if ($verification && $verification['identity_status'] === 'rejected'): ?>
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                            <p class="text-red-800 text-sm">
                                <strong>Rejection Reason:</strong> <?= htmlspecialchars($verification['identity_rejection_reason'] ?? 'Document quality insufficient') ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Address Verification -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-secondary bg-opacity-10 rounded-full flex items-center justify-center">
                                <i class="fas fa-home text-secondary"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Address Verification</h3>
                                <p class="text-gray-600 text-sm">Upload proof of address</p>
                            </div>
                        </div>
                        
                        <?php if ($verification && $verification['address_status'] === 'verified'): ?>
                            <span class="verification-badge gold">
                                <i class="fas fa-shield-check"></i>
                                Verified
                            </span>
                        <?php elseif ($verification && $verification['address_status'] === 'pending'): ?>
                            <span class="verification-badge bronze">
                                <i class="fas fa-clock"></i>
                                Pending
                            </span>
                        <?php else: ?>
                            <span class="verification-badge none">
                                <i class="fas fa-clock"></i>
                                Not Started
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!$verification || $verification['address_status'] !== 'verified'): ?>
                        <form action="/verification/upload-document" method="POST" enctype="multipart/form-data" class="space-y-4">
                            <input type="hidden" name="document_type" value="address">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Proof of Address</label>
                                <input type="file" name="document" accept="image/*,.pdf" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <p class="text-sm text-gray-500 mt-1">Bank statement, utility bill, or council tax bill (max 5MB)</p>
                            </div>
                            
                            <button type="submit" class="bg-secondary text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                Upload Address Proof
                            </button>
                        </form>
                    <?php endif; ?>
                </div>

                <!-- Student Verification (only for students) -->
                <?php if ($user['user_type'] === 'student'): ?>
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-accent bg-opacity-10 rounded-full flex items-center justify-center">
                                <i class="fas fa-graduation-cap text-accent"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Student Verification</h3>
                                <p class="text-gray-600 text-sm">Verify your student status</p>
                            </div>
                        </div>
                        
                        <?php if ($verification && $verification['student_status'] === 'verified'): ?>
                            <span class="verification-badge gold">
                                <i class="fas fa-shield-check"></i>
                                Verified
                            </span>
                        <?php elseif ($verification && $verification['student_status'] === 'pending'): ?>
                            <span class="verification-badge bronze">
                                <i class="fas fa-clock"></i>
                                Pending
                            </span>
                        <?php else: ?>
                            <span class="verification-badge none">
                                <i class="fas fa-clock"></i>
                                Not Started
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!$verification || $verification['student_status'] !== 'verified'): ?>
                        <form action="/verification/upload-document" method="POST" enctype="multipart/form-data" class="space-y-4">
                            <input type="hidden" name="document_type" value="student">
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Student ID or Enrollment Letter</label>
                                <input type="file" name="document" accept="image/*,.pdf" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <p class="text-sm text-gray-500 mt-1">Student ID card or official enrollment letter (max 5MB)</p>
                            </div>
                            
                            <button type="submit" class="bg-accent text-white px-6 py-2 rounded-lg hover:bg-red-600 transition-colors">
                                Upload Student Proof
                            </button>
                        </form>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Biometric Verification -->
                <div class="border border-gray-200 rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-3">
                            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-user-check text-green-600"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg">Biometric Verification</h3>
                                <p class="text-gray-600 text-sm">Take a selfie for identity confirmation</p>
                            </div>
                        </div>
                        
                        <?php if ($verification && $verification['biometric_status'] === 'verified'): ?>
                            <span class="verification-badge gold">
                                <i class="fas fa-shield-check"></i>
                                Verified
                            </span>
                        <?php elseif ($verification && $verification['biometric_status'] === 'pending'): ?>
                            <span class="verification-badge bronze">
                                <i class="fas fa-clock"></i>
                                Pending
                            </span>
                        <?php else: ?>
                            <span class="verification-badge none">
                                <i class="fas fa-clock"></i>
                                Not Started
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (!$verification || $verification['biometric_status'] !== 'verified'): ?>
                        <div class="space-y-4">
                            <p class="text-sm text-gray-600">
                                We'll use your device camera to take a selfie and match it with your ID document.
                            </p>
                            
                            <a href="/verification/biometric" 
                               class="inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition-colors">
                                Start Biometric Verification
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Trust Score -->
            <?php if ($verification): ?>
            <div class="mt-8 p-6 bg-blue-50 rounded-lg">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="font-semibold text-lg">Your Trust Score</h3>
                    <div class="text-2xl font-bold text-primary">
                        <?php
                        $trustScore = 0;
                        if ($verification['identity_status'] === 'verified') $trustScore += 30;
                        if ($verification['address_status'] === 'verified') $trustScore += 20;
                        if ($verification['biometric_status'] === 'verified') $trustScore += 25;
                        if ($user['user_type'] === 'student' && $verification['student_status'] === 'verified') $trustScore += 25;
                        if ($user['user_type'] === 'landlord') $trustScore = round($trustScore * 1.33); // Adjust for landlords
                        echo $trustScore;
                        ?>%
                    </div>
                </div>
                
                <div class="trust-meter mb-4">
                    <div class="trust-meter-fill <?= $trustScore >= 80 ? 'high' : ($trustScore >= 50 ? 'medium' : 'low') ?>" 
                         style="width: <?= $trustScore ?>%"></div>
                </div>
                
                <p class="text-sm text-gray-700">
                    A higher trust score increases your credibility and helps you get better responses from 
                    <?= $user['user_type'] === 'student' ? 'landlords' : 'students' ?>.
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
