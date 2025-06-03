<div class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-primary to-secondary text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="font-heading font-bold text-5xl lg:text-6xl mb-6">
                        Safe Student Housing
                        <span class="text-yellow-300">Without Scams</span>
                    </h1>
                    <p class="text-xl mb-8 text-blue-100">
                        Find verified accommodation with trusted landlords. Our advanced verification system ensures your safety and security.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/properties" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors text-center">
                            Find Housing
                        </a>
                        <a href="/register" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors text-center">
                            List Property
                        </a>
                    </div>
                </div>
                <div class="relative">
                    <img src="/placeholder.svg?height=500&width=600" alt="Safe Student Housing" class="rounded-lg shadow-2xl">
                    <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-lg shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-shield-check text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">100% Verified</p>
                                <p class="text-sm text-gray-600">All landlords verified</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary mb-2"><?= number_format($stats['total_properties'] ?? 0) ?></div>
                    <div class="text-gray-600">Verified Properties</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary mb-2">100%</div>
                    <div class="text-gray-600">Scam-Free</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary mb-2">24/7</div>
                    <div class="text-gray-600">Support</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-primary mb-2">£<?= number_format($stats['average_price'] ?? 0) ?></div>
                    <div class="text-gray-600">Average Rent</div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-heading font-bold text-4xl text-gray-900 mb-4">How SecureStay Works</h2>
                <p class="text-xl text-gray-600">Simple, secure, and transparent process</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="text-center">
                    <div class="w-20 h-20 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-user-check text-primary text-3xl"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-4">1. Get Verified</h3>
                    <p class="text-gray-600">Complete our secure verification process with government ID and biometric checks.</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-secondary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-search text-secondary text-3xl"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-4">2. Search & Connect</h3>
                    <p class="text-gray-600">Browse verified properties and connect with trusted landlords through our secure platform.</p>
                </div>
                <div class="text-center">
                    <div class="w-20 h-20 bg-accent bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-home text-accent text-3xl"></i>
                    </div>
                    <h3 class="font-semibold text-xl mb-4">3. Move In Safely</h3>
                    <p class="text-gray-600">Complete your rental agreement with confidence, knowing everyone is verified.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    <?php if (!empty($featuredProperties)): ?>
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="font-heading font-bold text-4xl text-gray-900 mb-4">Featured Properties</h2>
                    <p class="text-xl text-gray-600">Popular verified accommodations</p>
                </div>
                <a href="/properties" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    View All Properties
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($featuredProperties as $property): ?>
                    <div class="property-card bg-white rounded-lg overflow-hidden">
                        <div class="relative">
                            <img src="<?= htmlspecialchars($property->primary_image ?? '/placeholder.svg?height=200&width=300') ?>" 
                                 alt="<?= htmlspecialchars($property->title) ?>" 
                                 class="w-full h-48 object-cover">
                            
                            <div class="absolute top-3 left-3">
                                <span class="verification-badge gold">
                                    <i class="fas fa-shield-check"></i>
                                    Verified
                                </span>
                            </div>
                            
                            <div class="absolute top-3 right-3">
                                <span class="bg-primary text-white px-3 py-1 rounded-full text-sm font-semibold">
                                    £<?= number_format($property->price) ?>/mo
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6">
                            <h3 class="font-semibold text-lg text-gray-900 mb-2 truncate">
                                <?= htmlspecialchars($property->title) ?>
                            </h3>
                            
                            <p class="text-gray-600 text-sm mb-3">
                                <i class="fas fa-map-marker-alt mr-1"></i>
                                <?= htmlspecialchars($property->city) ?>
                            </p>
                            
                            <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-bed"></i>
                                    <span><?= $property->bedrooms ?> Bed</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-bath"></i>
                                    <span><?= $property->bathrooms ?> Bath</span>
                                </div>
                                <div class="flex items-center space-x-1">
                                    <i class="fas fa-eye"></i>
                                    <span><?= number_format($property->views) ?></span>
                                </div>
                            </div>
                            
                            <a href="/properties/<?= $property->id ?>" 
                               class="block w-full bg-primary text-white text-center py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Safety Features -->
    <section class="py-20 bg-gray-900 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="font-heading font-bold text-4xl mb-4">Your Safety is Our Priority</h2>
                <p class="text-xl text-gray-300">Advanced security features to protect you from scams</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="w-16 h-16 bg-primary bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-id-card text-primary text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">ID Verification</h3>
                    <p class="text-gray-300 text-sm">Government ID verification for all users</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-secondary bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-check text-secondary text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Biometric Checks</h3>
                    <p class="text-gray-300 text-sm">Facial recognition to prevent fake profiles</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-accent bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comments text-accent text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">Secure Messaging</h3>
                    <p class="text-gray-300 text-sm">AI-powered scam detection in messages</p>
                </div>
                <div class="text-center">
                    <div class="w-16 h-16 bg-green-500 bg-opacity-20 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-alt text-green-500 text-2xl"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2">24/7 Monitoring</h3>
                    <p class="text-gray-300 text-sm">Continuous security monitoring and support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-primary">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-heading font-bold text-4xl text-white mb-6">
                Ready to Find Safe Student Housing?
            </h2>
            <p class="text-xl text-blue-100 mb-8">
                Join thousands of students who trust SecureStay for their accommodation needs
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Get Started Free
                </a>
                <a href="/how-it-works" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                    Learn More
                </a>
            </div>
        </div>
    </section>
</div>
