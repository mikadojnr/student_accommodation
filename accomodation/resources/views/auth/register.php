<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <div class="flex justify-center">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-shield-alt text-primary text-3xl"></i>
                    <span class="font-heading font-bold text-2xl text-gray-900">SecureStay</span>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                Create your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <a href="/login" class="font-medium text-primary hover:text-blue-700">
                    sign in to existing account
                </a>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="/register" method="POST">
            <!-- User Type Selection -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">I am a:</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative">
                        <input type="radio" name="user_type" value="student" 
                               class="sr-only peer" 
                               <?= ($_SESSION['old']['user_type'] ?? 'student') === 'student' ? 'checked' : '' ?>>
                        <div class="w-full p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-primary peer-checked:bg-blue-50 hover:bg-gray-50">
                            <div class="text-center">
                                <i class="fas fa-graduation-cap text-2xl text-gray-600 peer-checked:text-primary mb-2"></i>
                                <div class="font-medium">Student</div>
                                <div class="text-xs text-gray-500">Looking for housing</div>
                            </div>
                        </div>
                    </label>
                    
                    <label class="relative">
                        <input type="radio" name="user_type" value="landlord" 
                               class="sr-only peer"
                               <?= ($_SESSION['old']['user_type'] ?? '') === 'landlord' ? 'checked' : '' ?>>
                        <div class="w-full p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-primary peer-checked:bg-blue-50 hover:bg-gray-50">
                            <div class="text-center">
                                <i class="fas fa-home text-2xl text-gray-600 peer-checked:text-primary mb-2"></i>
                                <div class="font-medium">Landlord</div>
                                <div class="text-xs text-gray-500">Listing properties</div>
                            </div>
                        </div>
                    </label>
                </div>
                <?php if (isset($_SESSION['errors']['user_type'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['user_type']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Name Fields -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input id="first_name" name="first_name" type="text" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                           value="<?= htmlspecialchars($_SESSION['old']['first_name'] ?? '') ?>">
                    <?php if (isset($_SESSION['errors']['first_name'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['first_name']) ?></p>
                    <?php endif; ?>
                </div>
                
                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input id="last_name" name="last_name" type="text" required
                           class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                           value="<?= htmlspecialchars($_SESSION['old']['last_name'] ?? '') ?>">
                    <?php if (isset($_SESSION['errors']['last_name'])): ?>
                        <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['last_name']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input id="email" name="email" type="email" autocomplete="email" required
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                       value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>">
                <?php if (isset($_SESSION['errors']['email'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['email']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Phone -->
            <div>
                <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                <input id="phone" name="phone" type="tel" required
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                       value="<?= htmlspecialchars($_SESSION['old']['phone'] ?? '') ?>">
                <?php if (isset($_SESSION['errors']['phone'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['phone']) ?></p>
                <?php endif; ?>
            </div>

            <!-- University (for students) -->
            <div id="university-field" class="hidden">
                <label for="university" class="block text-sm font-medium text-gray-700">University</label>
                <input id="university" name="university" type="text"
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary focus:border-primary sm:text-sm"
                       placeholder="e.g., University of Oxford"
                       value="<?= htmlspecialchars($_SESSION['old']['university'] ?? '') ?>">
                <?php if (isset($_SESSION['errors']['university'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['university']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Password Fields -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" name="password" type="password" autocomplete="new-password" required
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                <p class="mt-1 text-xs text-gray-500">Must be at least 8 characters long</p>
                <?php if (isset($_SESSION['errors']['password'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['password']) ?></p>
                <?php endif; ?>
            </div>

            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input id="confirm_password" name="confirm_password" type="password" required
                       class="mt-1 appearance-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-md focus:outline-none focus:ring-primary focus:border-primary sm:text-sm">
                <?php if (isset($_SESSION['errors']['confirm_password'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($_SESSION['errors']['confirm_password']) ?></p>
                <?php endif; ?>
            </div>

            <!-- Terms and Conditions -->
            <div class="flex items-center">
                <input id="terms" name="terms" type="checkbox" required
                       class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                <label for="terms" class="ml-2 block text-sm text-gray-900">
                    I agree to the 
                    <a href="/terms" class="text-primary hover:text-blue-700">Terms of Service</a> 
                    and 
                    <a href="/privacy" class="text-primary hover:text-blue-700">Privacy Policy</a>
                </label>
            </div>

            <div>
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-user-plus text-blue-300 group-hover:text-blue-200"></i>
                    </span>
                    Create Account
                </button>
            </div>
            
            <!-- Security Notice -->
            <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                <div class="flex items-start space-x-3">
                    <i class="fas fa-info-circle text-primary mt-0.5"></i>
                    <div class="text-sm text-gray-700">
                        <p class="font-medium mb-1">Next Steps:</p>
                        <ul class="list-disc list-inside space-y-1 text-xs">
                            <li>Verify your identity with government ID</li>
                            <li>Complete biometric verification</li>
                            <li>Get your verification badge</li>
                        </ul>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Show/hide university field based on user type
document.addEventListener('DOMContentLoaded', function() {
    const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
    const universityField = document.getElementById('university-field');
    const universityInput = document.getElementById('university');
    
    function toggleUniversityField() {
        const selectedType = document.querySelector('input[name="user_type"]:checked')?.value;
        if (selectedType === 'student') {
            universityField.classList.remove('hidden');
            universityInput.required = true;
        } else {
            universityField.classList.add('hidden');
            universityInput.required = false;
        }
    }
    
    userTypeRadios.forEach(radio => {
        radio.addEventListener('change', toggleUniversityField);
    });
    
    // Initial check
    toggleUniversityField();
});
</script>

<?php unset($_SESSION['old'], $_SESSION['errors']); ?>
