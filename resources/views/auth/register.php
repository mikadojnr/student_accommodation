<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <a href="/" class="flex items-center justify-center space-x-2">
                <i class="fas fa-shield-alt text-primary text-3xl"></i>
                <span class="font-heading font-bold text-2xl text-gray-900">SecureStay</span>
            </a>
            <h2 class="mt-6 text-3xl font-bold text-gray-900">Create your account</h2>
            <p class="mt-2 text-sm text-gray-600">
                Or
                <a href="/login" class="font-medium text-primary hover:text-blue-700">
                    sign in to your existing account
                </a>
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" action="/register" method="POST">
                <?= csrf_field() ?>
                
                <!-- User Type Selection -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-3">
                        I am a:
                    </label>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="relative">
                            <input type="radio" name="user_type" value="student" 
                                   <?= old('user_type', 'student') === 'student' ? 'checked' : '' ?>
                                   class="sr-only peer">
                            <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-primary peer-checked:bg-blue-50 hover:border-gray-400 transition-colors">
                                <div class="text-center">
                                    <i class="fas fa-graduation-cap text-2xl text-gray-600 peer-checked:text-primary mb-2"></i>
                                    <div class="font-medium">Student</div>
                                    <div class="text-sm text-gray-500">Looking for housing</div>
                                </div>
                            </div>
                        </label>
                        <label class="relative">
                            <input type="radio" name="user_type" value="landlord" 
                                   <?= old('user_type') === 'landlord' ? 'checked' : '' ?>
                                   class="sr-only peer">
                            <div class="p-4 border-2 border-gray-300 rounded-lg cursor-pointer peer-checked:border-primary peer-checked:bg-blue-50 hover:border-gray-400 transition-colors">
                                <div class="text-center">
                                    <i class="fas fa-home text-2xl text-gray-600 peer-checked:text-primary mb-2"></i>
                                    <div class="font-medium">Landlord</div>
                                    <div class="text-sm text-gray-500">Listing properties</div>
                                </div>
                            </div>
                        </label>
                    </div>
                    <?php if (hasError('user_type')): ?>
                        <p class="mt-2 text-sm text-red-600"><?= error('user_type') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Name Fields -->
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="first_name" class="block text-sm font-medium text-gray-700">
                            First name
                        </label>
                        <div class="mt-1">
                            <input id="first_name" name="first_name" type="text" required
                                   value="<?= htmlspecialchars(old('first_name')) ?>"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm <?= hasError('first_name') ? 'border-red-300' : '' ?>">
                        </div>
                        <?php if (hasError('first_name')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= error('first_name') ?></p>
                        <?php endif; ?>
                    </div>
                    <div>
                        <label for="last_name" class="block text-sm font-medium text-gray-700">
                            Last name
                        </label>
                        <div class="mt-1">
                            <input id="last_name" name="last_name" type="text" required
                                   value="<?= htmlspecialchars(old('last_name')) ?>"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm <?= hasError('last_name') ? 'border-red-300' : '' ?>">
                        </div>
                        <?php if (hasError('last_name')): ?>
                            <p class="mt-1 text-sm text-red-600"><?= error('last_name') ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">
                        Email address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               value="<?= htmlspecialchars(old('email')) ?>"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm <?= hasError('email') ? 'border-red-300' : '' ?>">
                    </div>
                    <?php if (hasError('email')): ?>
                        <p class="mt-2 text-sm text-red-600"><?= error('email') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Phone -->
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">
                        Phone number (optional)
                    </label>
                    <div class="mt-1">
                        <input id="phone" name="phone" type="tel"
                               value="<?= htmlspecialchars(old('phone')) ?>"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm <?= hasError('phone') ? 'border-red-300' : '' ?>">
                    </div>
                    <?php if (hasError('phone')): ?>
                        <p class="mt-2 text-sm text-red-600"><?= error('phone') ?></p>
                    <?php endif; ?>
                </div>

                <!-- University (for students) -->
                <div id="university-field" style="display: none;">
                    <label for="university" class="block text-sm font-medium text-gray-700">
                        University
                    </label>
                    <div class="mt-1">
                        <input id="university" name="university" type="text"
                               value="<?= htmlspecialchars(old('university')) ?>"
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm <?= hasError('university') ? 'border-red-300' : '' ?>"
                               placeholder="e.g., University of Oxford">
                    </div>
                    <?php if (hasError('university')): ?>
                        <p class="mt-2 text-sm text-red-600"><?= error('university') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm <?= hasError('password') ? 'border-red-300' : '' ?>">
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Must be at least 8 characters</p>
                    <?php if (hasError('password')): ?>
                        <p class="mt-2 text-sm text-red-600"><?= error('password') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                        Confirm password
                    </label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm <?= hasError('password_confirmation') ? 'border-red-300' : '' ?>">
                    </div>
                    <?php if (hasError('password_confirmation')): ?>
                        <p class="mt-2 text-sm text-red-600"><?= error('password_confirmation') ?></p>
                    <?php endif; ?>
                </div>

                <!-- Terms and Conditions -->
                <div class="flex items-center">
                    <input id="terms" name="terms" type="checkbox" required
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="terms" class="ml-2 block text-sm text-gray-900">
                        I agree to the
                        <a href="/terms" class="text-primary hover:text-blue-700">Terms and Conditions</a>
                        and
                        <a href="/privacy" class="text-primary hover:text-blue-700">Privacy Policy</a>
                    </label>
                </div>
                <?php if (hasError('terms')): ?>
                    <p class="mt-2 text-sm text-red-600"><?= error('terms') ?></p>
                <?php endif; ?>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-user-plus text-blue-300 group-hover:text-blue-200"></i>
                        </span>
                        Create account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
// Show/hide university field based on user type
document.addEventListener('DOMContentLoaded', function() {
    const userTypeRadios = document.querySelectorAll('input[name="user_type"]');
    const universityField = document.getElementById('university-field');
    const universityInput = document.getElementById('university');
    
    function toggleUniversityField() {
        const selectedType = document.querySelector('input[name="user_type"]:checked').value;
        if (selectedType === 'student') {
            universityField.style.display = 'block';
            universityInput.required = true;
        } else {
            universityField.style.display = 'none';
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
