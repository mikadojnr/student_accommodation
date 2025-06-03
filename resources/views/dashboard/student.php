<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="font-heading font-bold text-3xl text-gray-900">
                        Welcome back, <?= htmlspecialchars($user['first_name']) ?>!
                    </h1>
                    <p class="text-gray-600 mt-1">Find your perfect student accommodation</p>
                </div>
                
                <!-- Verification Status -->
                <div class="flex items-center space-x-4">
                    <?php if ($verification): ?>
                        <?php
                        $trustScore = 0;
                        if ($verification['identity_status'] === 'verified') $trustScore += 30;
                        if ($verification['address_status'] === 'verified') $trustScore += 20;
                        if ($verification['biometric_status'] === 'verified') $trustScore += 25;
                        if ($verification['student_status'] === 'verified') $trustScore += 25;
                        ?>
                        
                        <div class="text-center">
                            <div class="text-2xl font-bold text-primary"><?= $trustScore ?>%</div>
                            <div class="text-sm text-gray-600">Trust Score</div>
                        </div>
                        
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
                        <a href="/properties" class="p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition-colors">
                            <i class="fas fa-search text-primary text-2xl mb-2"></i>
                            <h3 class="font-medium">Find Properties</h3>
                            <p class="text-sm text-gray-600">Browse verified listings</p>
                        </a>
                        
                        <a href="/dashboard/saved" class="p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition-colors">
                            <i class="fas fa-heart text-red-500 text-2xl mb-2"></i>
                            <h3 class="font-medium">Saved Properties</h3>
                            <p class="text-sm text-gray-600"><?= count($savedProperties) ?> saved</p>
                        </a>
                        
                        <a href="/dashboard/messages" class="p-4 border border-gray-200 rounded-lg hover:border-primary hover:bg-blue-50 transition-colors">
                            <i class="fas fa-comments text-secondary text-2xl mb-2"></i>
                            <h3 class="font-medium">Messages</h3>
                            <p class="text-sm text-gray-600"><?= count($messages) ?> conversations</p>
                        </a>
                    </div>
                </div>

                <!-- Saved Properties -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="font-semibold text-xl">Saved Properties</h2>
                        <a href="/dashboard/saved" class="text-primary hover:text-blue-700 text-sm">View All</a>
                    </div>
                    
                    <?php if (empty($savedProperties)): ?>
                        <div class="text-center py-8">
                            <i class="fas fa-heart text-gray-300 text-4xl mb-4"></i>
                            <h3 class="font-medium text-gray-900 mb-2">No saved properties yet</h3>
                            <p class="text-gray-600 mb-4">Start browsing to save properties you're interested in</p>
                            <a href="/properties" class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                Browse Properties
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <?php foreach (array_slice($savedProperties, 0, 4) as $property): ?>
                                <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-md transition-shadow">
                                    <div class="relative">
                                        <img src="<?= htmlspecialchars($property->primary_image ?? '/placeholder.svg?height=150&width=250') ?>" 
                                             alt="<?= htmlspecialchars($property->title) ?>" 
                                             class="w-full h-32 object-cover">
                                        <div class="absolute top-2 left-2">
                                            <span class="verification-badge gold text-xs">
                                                <i class="fas fa-shield-check"></i>
                                                Verified
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4">
                                        <h3 class="font-medium text-gray-900 mb-1 truncate">
                                            <?= htmlspecialchars($property->title) ?>
                                        </h3>
                                        <p class="text-primary font-semibold">£<?= number_format($property->price) ?>/mo</p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            <?= htmlspecialchars($property->city) ?> • <?= $property->campus_distance ?>km to campus
                                        </p>
                                        
                                        <div class="flex justify-between items-center mt-3">
                                            <a href="/properties/<?= $property->id ?>" 
                                               class="text-primary hover:text-blue-700 text-sm">View Details</a>
                                            <button onclick="unsaveProperty(<?= $property->id ?>)" 
                                                    class="text-red-500 hover:text-red-700">
                                                <i class="fas fa-heart-broken"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Recent Searches -->
                <?php if (!empty($recentSearches)): ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="font-semibold text-xl mb-4">Recent Searches</h2>
                    
                    <div class="space-y-2">
                        <?php foreach (array_slice($recentSearches, 0, 5) as $search): ?>
                            <a href="/properties?search=<?= urlencode($search) ?>" 
                               class="block p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                                <div class="flex items-center space-x-3">
                                    <i class="fas fa-search text-gray-400"></i>
                                    <span class="text-gray-900"><?= htmlspecialchars($search) ?></span>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Verification Progress -->
                <?php if ($verification): ?>
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h3 class="font-semibold text-lg mb-4">Verification Status</h3>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm">Identity</span>
                            <?php if ($verification['identity_status'] === 'verified'): ?>
                                <i class="fas fa-check-circle text-green-500"></i>
                            <?php elseif ($verification['identity_status'] === 'pending'): ?>
                                <i class="fas fa-clock text-yellow-500"></i
