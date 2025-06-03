<div class="min-h-screen bg-gray-50">
    <!-- Header Section -->
    <section class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="font-heading font-bold text-3xl text-gray-900">Student Properties</h1>
                    <p class="text-gray-600 mt-2">Find verified accommodation near your university</p>
                </div>
                
                <!-- Quick Stats -->
                <div class="flex items-center space-x-6 mt-4 lg:mt-0">
                    <div class="text-center">
                        <div class="text-2xl font-bold text-primary"><?= count($properties) ?></div>
                        <div class="text-sm text-gray-600">Available</div>
                    </div>
                    <div class="text-center">
                        <div class="text-2xl font-bold text-green-600">100%</div>
                        <div class="text-sm text-gray-600">Verified</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Filters Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h3 class="font-semibold text-lg mb-4">Filters</h3>
                    
                    <form method="GET" action="/properties" class="space-y-6">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                            <input type="text" name="search" 
                                   value="<?= htmlspecialchars($filters['search'] ?? '') ?>"
                                   placeholder="Location, university..."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                        </div>

                        <!-- Price Range -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Price Range (£/month)</label>
                            <div class="grid grid-cols-2 gap-2">
                                <input type="number" name="min_price" 
                                       value="<?= htmlspecialchars($filters['min_price'] ?? '') ?>"
                                       placeholder="Min"
                                       class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <input type="number" name="max_price" 
                                       value="<?= htmlspecialchars($filters['max_price'] ?? '') ?>"
                                       placeholder="Max"
                                       class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                            </div>
                        </div>

                        <!-- Property Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                            <select name="property_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">All Types</option>
                                <option value="room" <?= ($filters['property_type'] ?? '') === 'room' ? 'selected' : '' ?>>Room</option>
                                <option value="apartment" <?= ($filters['property_type'] ?? '') === 'apartment' ? 'selected' : '' ?>>Apartment</option>
                                <option value="house" <?= ($filters['property_type'] ?? '') === 'house' ? 'selected' : '' ?>>House</option>
                                <option value="studio" <?= ($filters['property_type'] ?? '') === 'studio' ? 'selected' : '' ?>>Studio</option>
                            </select>
                        </div>

                        <!-- Campus Distance -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Max Distance to Campus (km)</label>
                            <select name="campus_distance" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Any Distance</option>
                                <option value="1" <?= ($filters['campus_distance'] ?? '') === '1' ? 'selected' : '' ?>>Within 1km</option>
                                <option value="2" <?= ($filters['campus_distance'] ?? '') === '2' ? 'selected' : '' ?>>Within 2km</option>
                                <option value="5" <?= ($filters['campus_distance'] ?? '') === '5' ? 'selected' : '' ?>>Within 5km</option>
                                <option value="10" <?= ($filters['campus_distance'] ?? '') === '10' ? 'selected' : '' ?>>Within 10km</option>
                            </select>
                        </div>

                        <!-- Verified Only -->
                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="verified_only" value="1" 
                                       <?= $filters['verified_only'] ? 'checked' : '' ?>
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700">Verified landlords only</span>
                            </label>
                        </div>

                        <button type="submit" class="w-full bg-primary text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">
                            Apply Filters
                        </button>
                        
                        <a href="/properties" class="block text-center text-sm text-gray-600 hover:text-primary">
                            Clear all filters
                        </a>
                    </form>
                </div>
            </div>

            <!-- Properties Grid -->
            <div class="lg:col-span-3">
                <?php if (empty($properties)): ?>
                    <div class="text-center py-12">
                        <i class="fas fa-home text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No properties found</h3>
                        <p class="text-gray-600 mb-6">Try adjusting your filters or search criteria</p>
                        <a href="/properties" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            View All Properties
                        </a>
                    </div>
                <?php else: ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        <?php foreach ($properties as $property): ?>
                            <div class="property-card bg-white rounded-lg overflow-hidden">
                                <div class="relative">
                                    <img src="<?= htmlspecialchars($property->primary_image ?? '/placeholder.svg?height=200&width=300') ?>" 
                                         alt="<?= htmlspecialchars($property->title) ?>" 
                                         class="w-full h-48 object-cover">
                                    
                                    <!-- Verification Badge -->
                                    <div class="absolute top-3 left-3">
                                        <span class="verification-badge gold">
                                            <i class="fas fa-shield-check"></i>
                                            Verified
                                        </span>
                                    </div>
                                    
                                    <!-- Save Button -->
                                    <div class="absolute top-3 right-3">
                                        <button onclick="toggleSave(<?= $property->id ?>)" 
                                                class="bg-white rounded-full p-2 shadow-lg hover:bg-gray-50 transition-colors">
                                            <i class="fas fa-heart text-gray-400 hover:text-red-500"></i>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-semibold text-lg text-gray-900 truncate">
                                            <?= htmlspecialchars($property->title) ?>
                                        </h3>
                                        <span class="text-primary font-bold text-xl">
                                            £<?= number_format($property->price) ?>/mo
                                        </span>
                                    </div>
                                    
                                    <p class="text-gray-600 text-sm mb-3 line-clamp-2">
                                        <?= htmlspecialchars(substr($property->description, 0, 100)) ?>...
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
                                            <i class="fas fa-map-marker-alt"></i>
                                            <span><?= $property->campus_distance ?? 'N/A' ?>km</span>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center">
                                        <div class="flex items-center space-x-2">
                                            <img src="/placeholder.svg?height=32&width=32" 
                                                 alt="<?= htmlspecialchars($property->first_name ?? 'Landlord') ?>" 
                                                 class="w-8 h-8 rounded-full">
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">
                                                    <?= htmlspecialchars(($property->first_name ?? '') . ' ' . ($property->last_name ?? '')) ?>
                                                </p>
                                                <div class="trust-meter" style="width: 60px;">
                                                    <div class="trust-meter-fill high" style="width: 85%"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <a href="/properties/<?= $property->id ?>" 
                                           class="bg-primary text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700 transition-colors">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
</script>
