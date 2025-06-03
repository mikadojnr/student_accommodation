<div class="min-h-screen">
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-primary to-secondary text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h1 class="font-heading font-bold text-4xl md:text-6xl mb-6">
                        Secure Student Housing 
                        <span class="text-yellow-300">Without Scams</span>
                    </h1>
                    <p class="text-xl mb-8 text-blue-100">
                        Find verified student accommodation with trusted landlords. 
                        Every listing is verified, every landlord is authenticated.
                    </p>
                    
                    <!-- Search Bar -->
                    <div class="bg-white rounded-lg p-4 shadow-lg">
                        <form action="/properties" method="GET" class="flex flex-col md:flex-row gap-4">
                            <div class="flex-1">
                                <input type="text" name="search" placeholder="Enter university or city..." 
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                            <div class="flex gap-2">
                                <select name="property_type" class="px-4 py-3 border border-gray-300 rounded-lg text-gray-900 focus:ring-2 focus:ring-primary">
                                    <option value="">Property Type</option>
                                    <option value="room">Room</option>
                                    <option value="apartment">Apartment</option>
                                    <option value="house">House</option>
                                    <option value="studio">Studio</option>
                                </select>
                                <button type="submit" class="bg-accent text-white px-6 py-3 rounded-lg hover:bg-red-600 transition-colors">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Trust Indicators -->
                    <div class="flex items-center space-x-6 mt-8">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-shield-check text-green-300 text-xl"></i>
                            <span class="text-sm">SSL Secured</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-user-check text-green-300 text-xl"></i>
                            <span class="text-sm">ID Verified</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-lock text-green-300 text-xl"></i>
                            <span class="text-sm">Encrypted Chat</span>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <img src="/placeholder.svg?height=500&width=600" alt="Secure Housing" 
                         class="rounded-lg shadow-2xl">
                    <div class="absolute -bottom-6 -left-6 bg-white rounded-lg p-4 shadow-lg">
                        <div class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                <i class="fas fa-check text-green-600 text-xl"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">100% Verified</p>
                                <p class="text-sm text-gray-600">Landlords & Properties</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Value Proposition Section -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="font-heading font-bold text-3xl md:text-4xl text-gray-900 mb-4">
                    Why Choose SecureStay?
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    We've built the most secure platform for student accommodation, 
                    protecting you from scams and fraudulent listings.
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Verified Listings -->
                <div class="text-center p-6 rounded-lg border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shield-check text-primary text-2xl"></i>
                    </div>
                    <h3 class="font-heading font-semibold text-xl mb-3">Verified Listings</h3>
                    <p class="text-gray-600 mb-4">
                        Every property is verified through document checks and physical inspections. 
                        No fake listings, no scams.
                    </p>
                    <div class="flex justify-center">
                        <span class="verification-badge gold">
                            <i class="fas fa-shield-check"></i>
                            Fully Verified
                        </span>
                    </div>
                </div>
                
                <!-- Direct Chat -->
                <div class="text-center p-6 rounded-lg border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-secondary bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-comments text-secondary text-2xl"></i>
                    </div>
                    <h3 class="font-heading font-semibold text-xl mb-3">Secure Communication</h3>
                    <p class="text-gray-600 mb-4">
                        End-to-end encrypted messaging with automatic scam detection. 
                        All conversations are monitored for safety.
                    </p>
                    <div class="bg-gray-100 rounded-lg p-3">
                        <div class="flex items-center space-x-2 text-sm">
                            <i class="fas fa-lock text-green-600"></i>
                            <span class="text-gray-700">Messages are encrypted</span>
                        </div>
                    </div>
                </div>
                
                <!-- Fraud Protection -->
                <div class="text-center p-6 rounded-lg border border-gray-200 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 bg-accent bg-opacity-10 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-user-shield text-accent text-2xl"></i>
                    </div>
                    <h3 class="font-heading font-semibold text-xl mb-3">Fraud Protection</h3>
                    <p class="text-gray-600 mb-4">
                        Advanced AI detects suspicious activity. Secure escrow payments 
                        protect your deposits until move-in.
                    </p>
                    <div class="scam-alert rounded-lg p-3">
                        <div class="flex items-center space-x-2 text-sm">
                            <i class="fas fa-exclamation-triangle"></i>
                            <span>Never pay outside our platform</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Featured Properties -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-12">
                <div>
                    <h2 class="font-heading font-bold text-3xl md:text-4xl text-gray-900 mb-4">
                        Featured Properties
                    </h2>
                    <p class="text-xl text-gray-600">
                        Handpicked verified accommodations near top universities
                    </p>
                </div>
                <a href="/properties" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    View All Properties
                </a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                <div class="property-card bg-white rounded-lg overflow-hidden">
                    <div class="relative">
                        <img src="/placeholder.svg?height=200&width=300" alt="Property <?= $i ?>" 
                             class="w-full h-48 object-cover">
                        <div class="absolute top-3 left-3">
                            <span class="verification-badge gold">
                                <i class="fas fa-shield-check"></i>
                                Verified
                            </span>
                        </div>
                        <div class="absolute top-3 right-3 bg-white rounded-full p-2 shadow-lg cursor-pointer hover:bg-gray-50">
                            <i class="fas fa-heart text-gray-400 hover:text-red-500"></i>
                        </div>
                    </div>
                    
                    <div class="p-6">
                        <div class="flex justify-between items-start mb-2">
                            <h3 class="font-semibold text-lg text-gray-900">Modern Student Room</h3>
                            <span class="text-primary font-bold text-xl">£<?= 400 + ($i * 50) ?>/mo</span>
                        </div>
                        
                        <p class="text-gray-600 text-sm mb-3">
                            Spacious room in shared house, 5 min walk to campus
                        </p>
                        
                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-4">
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-bed"></i>
                                <span>1 Bed</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-bath"></i>
                                <span>Shared</span>
                            </div>
                            <div class="flex items-center space-x-1">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>0.<?= $i ?>km to campus</span>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-2">
                                <img src="/placeholder.svg?height=32&width=32" alt="Landlord" 
                                     class="w-8 h-8 rounded-full">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">John Smith</p>
                                    <div class="trust-meter" style="width: 60px;">
                                        <div class="trust-meter-fill high" style="width: <?= 80 + $i ?>%"></div>
                                    </div>
                                </div>
                            </div>
                            <a href="/properties/<?= $i ?>" class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                </div>
                <?php endfor; ?>
            </div>
        </div>
    </section>

    <!-- Security Features -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div>
                    <h2 class="font-heading font-bold text-3xl md:text-4xl text-gray-900 mb-6">
                        Advanced Security Features
                    </h2>
                    <p class="text-xl text-gray-600 mb-8">
                        Our multi-layered verification system ensures every user and property 
                        is authentic and trustworthy.
                    </p>
                    
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-primary bg-opacity-10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-id-card text-primary"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg mb-2">Identity Verification</h3>
                                <p class="text-gray-600">
                                    Government ID scanning with biometric verification using Jumio and Onfido technology.
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-secondary bg-opacity-10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-graduation-cap text-secondary"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg mb-2">Student Verification</h3>
                                <p class="text-gray-600">
                                    University email verification and student ID validation through official channels.
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-accent bg-opacity-10 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-home text-accent"></i>
                            </div>
                            <div>
                                <h3 class="font-semibold text-lg mb-2">Property Authentication</h3>
                                <p class="text-gray-600">
                                    Physical property inspections and document verification for all listings.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="relative">
                    <div class="bg-gradient-to-br from-primary to-secondary rounded-lg p-8 text-white">
                        <h3 class="font-heading font-bold text-2xl mb-6">Verification Process</h3>
                        
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold">1</span>
                                </div>
                                <span>Upload Government ID</span>
                                <i class="fas fa-check text-green-300 ml-auto"></i>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold">2</span>
                                </div>
                                <span>Biometric Face Scan</span>
                                <i class="fas fa-check text-green-300 ml-auto"></i>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold">3</span>
                                </div>
                                <span>Student Status Verification</span>
                                <i class="fas fa-spinner fa-spin text-yellow-300 ml-auto"></i>
                            </div>
                            
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                                    <span class="text-sm font-bold">4</span>
                                </div>
                                <span>Get Verified Badge</span>
                                <i class="fas fa-clock text-gray-300 ml-auto"></i>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-white bg-opacity-10 rounded-lg">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm">Verification Progress</span>
                                <span class="text-sm">75%</span>
                            </div>
                            <div class="trust-meter">
                                <div class="trust-meter-fill high" style="width: 75%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-16 bg-gradient-to-r from-primary to-secondary text-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="font-heading font-bold text-3xl md:text-4xl mb-6">
                Ready to Find Your Secure Student Home?
            </h2>
            <p class="text-xl mb-8 text-blue-100">
                Join thousands of students who have found safe, verified accommodation through SecureStay.
            </p>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="bg-white text-primary px-8 py-4 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    Get Started - It's Free
                </a>
                <a href="/properties" class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                    Browse Properties
                </a>
            </div>
            
            <div class="flex justify-center items-center space-x-8 mt-12 text-blue-200">
                <div class="text-center">
                    <div class="text-2xl font-bold">10,000+</div>
                    <div class="text-sm">Verified Students</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">5,000+</div>
                    <div class="text-sm">Safe Properties</div>
                </div>
                <div class="text-center">
                    <div class="text-2xl font-bold">99.9%</div>
                    <div class="text-sm">Scam-Free Rate</div>
                </div>
            </div>
        </div>
    </section>
</div>
