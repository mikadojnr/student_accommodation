<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-8">
            <div class="mb-8">
                <h1 class="font-heading font-bold text-3xl text-gray-900 mb-2">List Your Property</h1>
                <p class="text-gray-600">Create a verified listing to attract quality student tenants</p>
            </div>

            <form action="/properties" method="POST" enctype="multipart/form-data" class="space-y-8">
                <!-- Basic Information -->
                <div>
                    <h2 class="font-semibold text-xl text-gray-900 mb-4">Basic Information</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 mb-2">Property Title</label>
                            <input type="text" id="title" name="title" required
                                   value="<?= htmlspecialchars($_SESSION['old']['title'] ?? '') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="e.g., Modern Student Room Near Campus">
                            <?php if (isset($_SESSION['errors']['title'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['title']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="property_type" class="block text-sm font-medium text-gray-700 mb-2">Property Type</label>
                            <select id="property_type" name="property_type" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select Type</option>
                                <option value="room" <?= ($_SESSION['old']['property_type'] ?? '') === 'room' ? 'selected' : '' ?>>Room</option>
                                <option value="apartment" <?= ($_SESSION['old']['property_type'] ?? '') === 'apartment' ? 'selected' : '' ?>>Apartment</option>
                                <option value="house" <?= ($_SESSION['old']['property_type'] ?? '') === 'house' ? 'selected' : '' ?>>House</option>
                                <option value="studio" <?= ($_SESSION['old']['property_type'] ?? '') === 'studio' ? 'selected' : '' ?>>Studio</option>
                            </select>
                            <?php if (isset($_SESSION['errors']['property_type'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['property_type']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 mb-2">Monthly Rent (£)</label>
                            <input type="number" id="price" name="price" required min="0" step="0.01"
                                   value="<?= htmlspecialchars($_SESSION['old']['price'] ?? '') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="500">
                            <?php if (isset($_SESSION['errors']['price'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['price']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="deposit" class="block text-sm font-medium text-gray-700 mb-2">Security Deposit (£)</label>
                            <input type="number" id="deposit" name="deposit" required min="0" step="0.01"
                                   value="<?= htmlspecialchars($_SESSION['old']['deposit'] ?? '') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="500">
                            <?php if (isset($_SESSION['errors']['deposit'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['deposit']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="bedrooms" class="block text-sm font-medium text-gray-700 mb-2">Bedrooms</label>
                            <select id="bedrooms" name="bedrooms" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select</option>
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($_SESSION['old']['bedrooms'] ?? '') == $i ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="bathrooms" class="block text-sm font-medium text-gray-700 mb-2">Bathrooms</label>
                            <select id="bathrooms" name="bathrooms" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
                                <option value="">Select</option>
                                <?php for ($i = 1; $i <= 3; $i++): ?>
                                    <option value="<?= $i ?>" <?= ($_SESSION['old']['bathrooms'] ?? '') == $i ? 'selected' : '' ?>><?= $i ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mt-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea id="description" name="description" rows="4" required
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                  placeholder="Describe your property, its features, and what makes it special for students..."><?= htmlspecialchars($_SESSION['old']['description'] ?? '') ?></textarea>
                        <?php if (isset($_SESSION['errors']['description'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['description']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Location -->
                <div>
                    <h2 class="font-semibold text-xl text-gray-900 mb-4">Location</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Full Address</label>
                            <input type="text" id="address" name="address" required
                                   value="<?= htmlspecialchars($_SESSION['old']['address'] ?? '') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="123 Student Street">
                            <?php if (isset($_SESSION['errors']['address'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['address']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">City</label>
                            <input type="text" id="city" name="city" required
                                   value="<?= htmlspecialchars($_SESSION['old']['city'] ?? '') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="Oxford">
                            <?php if (isset($_SESSION['errors']['city'])): ?>
                                <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['city']) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div>
                            <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">Postal Code</label>
                            <input type="text" id="postal_code" name="postal_code"
                                   value="<?= htmlspecialchars($_SESSION['old']['postal_code'] ?? '') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="OX1 4AA">
                        </div>
                        
                        <div>
                            <label for="campus_distance" class="block text-sm font-medium text-gray-700 mb-2">Distance to Campus (km)</label>
                            <input type="number" id="campus_distance" name="campus_distance" min="0" step="0.1"
                                   value="<?= htmlspecialchars($_SESSION['old']['campus_distance'] ?? '') ?>"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
                                   placeholder="1.5">
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div>
                    <h2 class="font-semibold text-xl text-gray-900 mb-4">Amenities</h2>
                    
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        <?php 
                        $amenities = [
                            'WiFi', 'Desk', 'Wardrobe', 'Shared Kitchen', 'Private Kitchen',
                            'Shared Bathroom', 'Private Bathroom', 'Laundry', 'Parking',
                            'Garden', 'Bike Storage', 'Bills Included', 'Furnished',
                            'Central Heating', 'Air Conditioning', 'Security Entry'
                        ];
                        $selectedAmenities = $_SESSION['old']['amenities'] ?? [];
                        ?>
                        
                        <?php foreach ($amenities as $amenity): ?>
                            <label class="flex items-center">
                                <input type="checkbox" name="amenities[]" value="<?= htmlspecialchars($amenity) ?>"
                                       <?= in_array($amenity, $selectedAmenities) ? 'checked' : '' ?>
                                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                                <span class="ml-2 text-sm text-gray-700"><?= htmlspecialchars($amenity) ?></span>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Images -->
                <div>
                    <h2 class="font-semibold text-xl text-gray-900 mb-4">Property Images</h2>
                    
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                        <i class="fas fa-cloud-upload-alt text-gray-400 text-4xl mb-4"></i>
                        <p class="text-gray-600 mb-4">Upload property images (max 10 images, 5MB each)</p>
                        <input type="file" name="images[]" multiple accept="image/*" 
                               class="hidden" id="imageUpload">
                        <label for="imageUpload" 
                               class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors cursor-pointer">
                            Choose Images
                        </label>
                        <p class="text-sm text-gray-500 mt-2">First image will be used as the main photo</p>
                    </div>
                    
                    <div id="imagePreview" class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4 hidden"></div>
                </div>

                <!-- Submit -->
                <div class="border-t border-gray-200 pt-8">
                    <div class="flex justify-between items-center">
                        <a href="/dashboard" class="text-gray-600 hover:text-primary transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="bg-primary text-white px-8 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                            Create Listing
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('imageUpload').addEventListener('change', function(e) {
    const files = e.target.files;
    const preview = document.getElementById('imagePreview');
    
    if (files.length > 0) {
        preview.classList.remove('hidden');
        preview.innerHTML = '';
        
        for (let i = 0; i < Math.min(files.length, 10); i++) {
            const file = files[i];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative';
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview" class="w-full h-24 object-cover rounded-lg">
                    ${i === 0 ? '<span class="absolute top-1 left-1 bg-primary text-white text-xs px-2 py-1 rounded">Main</span>' : ''}
                `;
                preview.appendChild(div);
            };
            
            reader.readAsDataURL(file);
        }
    } else {
        preview.classList.add('hidden');
    }
});
</script>

<?php unset($_SESSION['old'], $_SESSION['errors']); ?>
