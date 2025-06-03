<div class="min-h-screen bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="/properties" class="flex items-center text-gray-600 hover:text-primary transition-colors">
                <i class="fas fa-arrow-left mr-2"></i>
                Back to Properties
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2">
                <!-- Image Gallery -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden mb-6">
                    <div class="relative">
                        <img src="<?= htmlspecialchars($images[0]['image_path'] ?? '/placeholder.svg?height=400&width=600') ?>" 
                             alt="<?= htmlspecialchars($property->title) ?>" 
                             class="w-full h-96 object-cover">
                        
                        <!-- Verification Badge -->
                        <div class="absolute top-4 left-4">
                            <span class="verification-badge gold">
                                <i class="fas fa-shield-check"></i>
                                Fully Verified
                            </span>
                        </div>
                        
                        <!-- Save Button -->
                        <div class="absolute top-4 right-4">
                            <button onclick="toggleSave(<?= $property->id ?>)" 
                                    class="bg-white rounded-full p-3 shadow-lg hover:bg-gray-50 transition-colors">
                                <i class="fas fa-heart text-gray-400 hover:text-red-500 text-lg"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Thumbnail Gallery -->
                    <?php if (count($images) > 1): ?>
                        <div class="p-4 border-t border-gray-200">
                            <div class="flex space-x-2 overflow-x-auto">
                                <?php foreach (array_slice($images, 1, 5) as $image): ?>
                                    <img src="<?= htmlspecialchars($image['image_path']) ?>" 
                                         alt="Property image" 
                                         class="w-20 h-20 object-cover rounded-lg cursor-pointer hover:opacity-75 transition-opacity">
                                <?php endforeach; ?>
                                <?php if (count($images) > 6): ?>
                                    <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center text-gray-600 text-sm">
                                        +<?= count($images) - 6 ?> more
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Property Details -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <h1 class="font-heading font-bold text-3xl text-gray-900 mb-4">
                        <?= htmlspecialchars($property->title) ?>
                    </h1>
                    
                    <div class="flex items-center space-x-4 text-gray-600 mb-6">
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-map-marker-alt"></i>
                            <span><?= htmlspecialchars($property->address . ', ' . $property->city) ?></span>
                        </div>
                        <div class="flex items-center space-x-1">
                            <i class="fas fa-eye"></i>
                            <span><?= number_format($property->views) ?> views</span>
                        </div>
                    </div>
                    
                    <!-- Property Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <i class="fas fa-bed text-primary text-2xl mb-2"></i>
                            <div class="font-semibold"><?= $property->bedrooms ?></div>
                            <div class="text-sm text-gray-600">Bedrooms</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <i class="fas fa-bath text-primary text-2xl mb-2"></i>
                            <div class="font-semibold"><?= $property->bathrooms ?></div>
                            <div class="text-sm text-gray-600">Bathrooms</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <i class="fas fa-home text-primary text-2xl mb-2"></i>
                            <div class="font-semibold"><?= ucfirst($property->property_type) ?></div>
                            <div class="text-sm text-gray-600">Type</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 rounded-lg">
                            <i class="fas fa-map-marker-alt text-primary text-2xl mb-2"></i>
                            <div class="font-semibold"><?= $property->campus_distance ?? 'N/A' ?>km</div>
                            <div class="text-sm text-gray-600">To Campus</div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div>
                        <h3 class="font-semibold text-lg mb-3">Description</h3>
                        <p class="text-gray-700 leading-relaxed">
                            <?= nl2br(htmlspecialchars($property->description)) ?>
                        </p>
                    </div>
                    
                    <!-- Amenities -->
                    <?php if (!empty($property->amenities)): ?>
                        <?php $amenities = json_decode($property->amenities, true) ?: []; ?>
                        <?php if (!empty($amenities)): ?>
                            <div class="mt-6">
                                <h3 class="font-semibold text-lg mb-3">Amenities</h3>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($amenities as $amenity): ?>
                                        <span class="bg-blue-50 text-primary px-3 py-1 rounded-full text-sm">
                                            <?= htmlspecialchars($amenity) ?>
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <!-- Pricing Card -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6 sticky top-6">
                    <div class="text-center mb-6">
                        <div class="text-3xl font-bold text-primary">£<?= number_format($property->price) ?></div>
                        <div class="text-gray-600">per month</div>
                        <div class="text-sm text-gray-500 mt-1">
                            Deposit: £<?= number_format($property->deposit) ?>
                        </div>
                    </div>
                    
                    <!-- Landlord Info -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h4 class="font-semibold mb-3">Landlord</h4>
                        <div class="flex items-center space-x-3 mb-3">
                            <img src="/placeholder.svg?height=48&width=48" 
                                 alt="<?= htmlspecialchars($property->first_name ?? 'Landlord') ?>" 
                                 class="w-12 h-12 rounded-full">
                            <div>
                                <p class="font-medium">
                                    <?= htmlspecialchars(($property->first_name ?? '') . ' ' . ($property->last_name ?? '')) ?>
                                </p>
                                <div class="flex items-center space-x-2">
                                    <div class="trust-meter" style="width: 80px;">
                                        <div class="trust-meter-fill high" style="width: 85%"></div>
                                    </div>
                                    <span class="text-sm text-gray-600">85% Trust</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="verification-badge gold mb-3">
                            <i class="fas fa-shield-check"></i>
                            ID Verified
                        </div>
                    </div>
                    
                    <!-- Contact Buttons -->
                    <div class="space-y-3">
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <button onclick="openChat()" 
                                    class="w-full bg-primary text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                                <i class="fas fa-comments mr-2"></i>
                                Send Message
                            </button>
                            <button onclick="requestViewing()" 
                                    class="w-full border border-primary text-primary py-3 px-4 rounded-lg hover:bg-blue-50 transition-colors">
                                <i class="fas fa-calendar mr-2"></i>
                                Request Viewing
                            </button>
                        <?php else: ?>
                            <a href="/login" 
                               class="block w-full bg-primary text-white py-3 px-4 rounded-lg hover:bg-blue-700 transition-colors text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Login to Contact
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Safety Notice -->
                    <div class="scam-alert rounded-lg p-3 mt-4">
                        <div class="flex items-start space-x-2">
                            <i class="fas fa-exclamation-triangle mt-0.5"></i>
                            <div class="text-sm">
                                <p class="font-medium mb-1">Safety Reminder</p>
                                <ul class="text-xs space-y-1">
                                    <li>• Never pay outside this platform</li>
                                    <li>• Always view property in person</li>
                                    <li>• Verify landlord identity</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Report Button -->
                <div class="text-center">
                    <button onclick="reportProperty()" 
                            class="text-gray-500 hover:text-red-500 text-sm transition-colors">
                        <i class="fas fa-flag mr-1"></i>
                        Report this listing
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chat Modal -->
<div id="chatModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg max-w-md w-full max-h-96 flex flex-col">
            <div class="flex justify-between items-center p-4 border-b">
                <h3 class="font-semibold">Send Message</h3>
                <button onclick="closeChat()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="p-4 flex-1">
                <textarea id="messageText" 
                          placeholder="Type your message here..." 
                          class="w-full h-32 p-3 border border-gray-300 rounded-lg resize-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
            </div>
            <div class="p-4 border-t">
                <button onclick="sendMessage()" 
                        class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                    Send Message
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSave(propertyId) {
    <?php if (isset($_SESSION['user_id'])): ?>
        makeRequest('/api/save-property', {
            method: 'POST',
            body: JSON.stringify({ property_id: propertyId })
        })
        .then(response => {
            const button = event.target.closest('button');
            const icon = button.querySelector('i');
            if (response.saved) {
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-red-500');
            } else {
                icon.classList.remove('text-red-500');
                icon.classList.add('text-gray-400');
            }
        })
        .catch(error => {
            console.error('Error saving property:', error);
        });
    <?php else: ?>
        window.location.href = '/login';
    <?php endif; ?>
}

function openChat() {
    document.getElementById('chatModal').classList.remove('hidden');
}

function closeChat() {
    document.getElementById('chatModal').classList.add('hidden');
}

function sendMessage() {
    const message = document.getElementById('messageText').value.trim();
    if (!message) return;
    
    makeRequest('/api/messages', {
        method: 'POST',
        body: JSON.stringify({
            property_id: <?= $property->id ?>,
            recipient_id: <?= $property->user_id ?>,
            message: message
        })
    })
    .then(response => {
        if (response.success) {
            alert('Message sent successfully!');
            closeChat();
            document.getElementById('messageText').value = '';
        }
    })
    .catch(error => {
        console.error('Error sending message:', error);
        alert('Failed to send message. Please try again.');
    });
}

function requestViewing() {
    const message = `Hi, I'm interested in viewing the property "${<?= json_encode($property->title) ?>}". When would be a good time for a viewing?`;
    document.getElementById('messageText').value = message;
    openChat();
}

function reportProperty() {
    if (confirm('Are you sure you want to report this property?')) {
        makeRequest('/api/report-property', {
            method: 'POST',
            body: JSON.stringify({
                property_id: <?= $property->id ?>,
                reason: 'suspicious',
                description: 'User reported this property as suspicious'
            })
        })
        .then(response => {
            if (response.success) {
                alert('Property reported successfully. We will review it shortly.');
            }
        })
        .catch(error => {
            console.error('Error reporting property:', error);
        });
    }
}
</script>
