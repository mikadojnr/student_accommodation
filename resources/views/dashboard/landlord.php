<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-heading font-bold text-3xl text-gray-900">
                        Welcome back, <?= htmlspecialchars($user['first_name']) ?>!
                    </h1>
                    <p class="text-gray-600 mt-1">Manage your property listings and tenant inquiries</p>
                </div>
                
                <!-- Quick Stats -->
                <div class="flex items-center space-x-6">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary"><?= $totalProperties ?></div>
                        <div class="text-sm text-gray-600">Properties</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600"><?= number_format($totalViews) ?></div>
                        <div class="text-sm text-gray-600">Total Views</div>
                    </div>
                    
                    <?php if ($verification): ?>
                        <?php
                        $trustScore = 0;
                        if ($verification['identity_status'] === 'verified') $trustScore += 40;
                        if ($verification['address_status'] === 'verified') $trustScore += 30;
                        if ($verification['biometric_status'] === 'verified') $trustScore += 30;
                        ?>
                        
                        <?php if ($trustScore >= 90): ?>
                            <span class="verification-badge gold">
                                <i class="fas fa-shield-check"></i>
                                Fully Verified
                            </span>
                        <?php elseif ($trustScore >= 70): ?>
                            <span class="verification-badge silver">
                                <i class="fas fa-shield"></i>
                                Verified
                            </span>
                        <?php else: ?>
                            <span class="verification-badge bronze">
                                <i class="fas fa-shield-alert"></i>
                                Partially Verified
                            </span>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="/verification" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            Complete Verification
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Quick Actions -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="font-semibold text-xl mb-4">Quick Actions</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="/properties/create" class="p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition-colors">
                            <i class="fas fa-plus text-primary text-2xl mb-2"></i>
                            <h3 class="font-medium">Add Property</h3>
                            <p class="text-sm text-gray-600">List a new property</p>
                        </a>
                        
                        <a href="/dashboard/messages" class="p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition-colors">
                            <i class="fas fa-comments text-secondary text-2xl mb-2"></i>
                            <h3 class="font-medium">Messages</h3>
                            <p class="text-sm text-gray-600"><?= count($recentMessages) ?> new inquiries</p>
                        </a>
                        
                        <a href="/dashboard/analytics" class="p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition-colors">
                            <i class="fas fa-chart-bar text-accent text-2xl mb-2"></i>
                            <h3 class="font-medium">Analytics</h3>
                            <p class="text-sm text-gray-600">View performance</p>
                        </a>
                    </div>
                </div>

                <!-- Properties List -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-semibold text-xl">Your Properties</h2>
                        <a href="/properties/create" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                            <i class="fas fa-plus mr-2"></i>
                            Add Property
                        </a>
                    </div>
                    
                    <?php if (empty($properties)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-home text-gray-300 text-4xl mb-4"></i>
                            <h3 class="font-medium text-gray-900 mb-2">No properties listed yet</h3>
                            <p class="text-gray-600 mb-4">Start by adding your first property listing</p>
                            <a href="/properties/create" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Add Your First Property
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="space-y-4">
                            <?php foreach ($properties as $property): ?>
                                <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                    <div class="flex items-start space-x-4">
                                        <img src="<?= htmlspecialchars($property->primary_image ?? '/placeholder.svg?height=80&width=120') ?>" 
                                             alt="<?= htmlspecialchars($property->title) ?>" 
                                             class="w-20 h-16 object-cover rounded-lg">
                                        
                                        <div class="flex-1">
                                            <div class="flex justify-between items-start mb-2">
                                                <h3 class="font-medium text-gray-900">
                                                    <?= htmlspecialchars($property->title) ?>
                                                </h3>
                                                <span class="text-primary font-semibold">
                                                    £<?= number_format($property->price) ?>/mo
                                                </span>
                                            </div>
                                            
                                            <p class="text-sm text-gray-600 mb-2">
                                                <?= htmlspecialchars($property->address . ', ' . $property->city) ?>
                                            </p>
                                            
                                            <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                                                <span><?= $property->bedrooms ?> bed</span>
                                                <span><?= $property->bathrooms ?> bath</span>
                                                <span><?= number_format($property->views) ?> views</span>
                                                <span><?= $property->image_count ?? 0 ?> photos</span>
                                            </div>
                                            
                                            <div class="flex justify-between items-center">
                                                <div class="flex items-center space-x-2">
                                                    <?php if ($property->is_available): ?>
                                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Active</span>
                                                    <?php else: ?>
                                                        <span class="bg-gray-100 text-gray-800 text-xs px-2 py-1 rounded-full">Inactive</span>
                                                    <?php endif; ?>
                                                    
                                                    <span class="verification-badge gold text-xs">
                                                        <i class="fas fa-shield-check"></i>
                                                        Verified
                                                    </span>
                                                </div>
                                                
                                                <div class="flex items-center space-x-2">
                                                    <a href="/properties/<?= $property->id ?>" 
                                                       class="text-primary hover:text-blue-700 text-sm">View</a>
                                                    <a href="/properties/<?= $property->id ?>/edit" 
                                                       class="text-gray-600 hover:text-gray-800 text-sm">Edit</a>
                                                    <button onclick="deleteProperty(<?= $property->id ?>)" 
                                                            class="text-red-600 hover:text-red-800 text-sm">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Recent Messages -->
                <?php if (!empty($recentMessages)): ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="font-semibold text-lg">Recent Inquiries</h3>
                        <a href="/dashboard/messages" class="text-primary hover:text-blue-700 text-sm">View All</a>
                    </div>
                    
                    <div class="space-y-3">
                        <?php foreach ($recentMessages as $message): ?>
                            <div class="p-3 border border-gray-200 rounded-lg">
                                <div class="flex items-center space-x-2 mb-1">
                                    <img src="/placeholder.svg?height=24&width=24" 
                                         alt="<?= htmlspecialchars($message['first_name']) ?>" 
                                         class="w-6 h-6 rounded-full">
                                    <span class="font-medium text-sm"><?= htmlspecialchars($message['first_name'] . ' ' . $message['last_name']) ?></span>
                                </div>
                                <p class="text-xs text-gray-600 mb-1">
                                    Re: <?= htmlspecialchars($message['property_title']) ?>
                                </p>
                                <p class="text-sm text-gray-600 truncate">
                                    <?= htmlspecialchars(substr($message['message'], 0, 50)) ?>...
                                </p>
                                <p class="text-xs text-gray-500 mt-1">
                                    <?= timeAgo($message['created_at']) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Performance Stats -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4">This Month</h3>
                    
                    <div class="space-y-4">
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Property Views</span>
                            <span class="font-semibold"><?= number_format($totalViews) ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">New Inquiries</span>
                            <span class="font-semibold"><?= count($recentMessages) ?></span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">Active Listings</span>
                            <span class="font-semibold"><?= count(array_filter($properties, fn($p) => $p->is_available)) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Verification Status -->
                <?php if ($verification): ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4">Verification Status</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Identity</span>
                            <?php if ($verification['identity_status'] === 'verified'): ?>
                                <i class="fas fa-check-circle text-green-500"></i>
                            <?php elseif ($verification['identity_status'] === 'pending'): ?>
                                <i class="fas fa-clock text-yellow-500"></i>
                            <?php else: ?>
                                <i class="fas fa-times-circle text-red-500"></i>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Address</span>
                            <?php if ($verification['address_status'] === 'verified'): ?>
                                <i class="fas fa-check-circle text-green-500"></i>
                            <?php elseif ($verification['address_status'] === 'pending'): ?>
                                <i class="fas fa-clock text-yellow-500"></i>
                            <?php else: ?>
                                <i class="fas fa-times-circle text-red-500"></i>
                            <?php endif; ?>
                        </div>
                        
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Biometric</span>
                            <?php if ($verification['biometric_status'] === 'verified'): ?>
                                <i class="fas fa-check-circle text-green-500"></i>
                            <?php elseif ($verification['biometric_status'] === 'pending'): ?>
                                <i class="fas fa-clock text-yellow-500"></i>
                            <?php else: ?>
                                <i class="fas fa-times-circle text-red-500"></i>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php if ($trustScore < 100): ?>
                        <a href="/verification" 
                           class="block w-full mt-4 bg-primary text-white text-center py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                            Complete Verification
                        </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <!-- Tips for Landlords -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4">Tips for Success</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-camera text-blue-500 mt-0.5"></i>
                            <span>Add high-quality photos to get 3x more views</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-clock text-green-500 mt-0.5"></i>
                            <span>Respond to inquiries within 2 hours</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-shield-check text-purple-500 mt-0.5"></i>
                            <span>Complete verification to build trust</span>
                        </div>
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-star text-yellow-500 mt-0.5"></i>
                            <span>Detailed descriptions increase bookings</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteProperty(propertyId) {
    if (confirm('Are you sure you want to delete this property? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/properties/${propertyId}/delete`;
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
