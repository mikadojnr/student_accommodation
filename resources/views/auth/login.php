<div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <a href="/" class="flex items-center justify-center space-x-2">
                <i class="fas fa-shield-alt text-primary text-3xl"></i>
                <span class="font-heading font-bold text-2xl text-gray-900">SecureStay</span>
            </a>
            <h2 class="mt-6 text-3xl font-bold text-gray-900">Sign in to your account</h2>
            <p class="mt-2 text-sm text-gray-600">
                Or
                <a href="/register" class="font-medium text-primary hover:text-blue-700">
                    create a new account
                </a>
            </p>
        </div>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form class="space-y-6" action="/login" method="POST">
                <?= csrf_field() ?>
                
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

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md placeholder-gray-400 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm <?= hasError('password') ? 'border-red-300' : '' ?>">
                    </div>
                    <?php if (hasError('password')): ?>
                        <p class="mt-2 text-sm text-red-600"><?= error('password') ?></p>
                    <?php endif; ?>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="/forgot-password" class="font-medium text-primary hover:text-blue-700">
                            Forgot your password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                        <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                            <i class="fas fa-sign-in-alt text-blue-300 group-hover:text-blue-200"></i>
                        </span>
                        Sign in
                    </button>
                </div>
            </form>

            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300" />
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Demo Accounts</span>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-2 gap-3">
                    <button onclick="fillDemoAccount('student')" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="fas fa-graduation-cap mr-2"></i>
                        Student Demo
                    </button>
                    <button onclick="fillDemoAccount('landlord')" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <i class="fas fa-home mr-2"></i>
                        Landlord Demo
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fillDemoAccount(type) {
    if (type === 'student') {
        document.getElementById('email').value = 'sarah.johnson@student.ox.ac.uk';
        document.getElementById('password').value = 'password';
    } else if (type === 'landlord') {
        document.getElementById('email').value = 'john.smith@landlord.com';
        document.getElementById('password').value = 'password';
    }
}
</script>
