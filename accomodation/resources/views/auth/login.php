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
                Sign in to your account
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                Or
                <a href="/register" class="font-medium text-primary hover:text-blue-700">
                    create a new account
                </a>
            </p>
        </div>
        
        <form class="mt-8 space-y-6" action="/login" method="POST">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <label for="email" class="sr-only">Email address</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm"
                           placeholder="Email address"
                           value="<?= htmlspecialchars($_SESSION['old']['email'] ?? '') ?>">
                </div>
                <div>
                    <label for="password" class="sr-only">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="appearance-none rounded-none relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-primary focus:border-primary focus:z-10 sm:text-sm"
                           placeholder="Password">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember-me" name="remember-me" type="checkbox"
                           class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                    <label for="remember-me" class="ml-2 block text-sm text-gray-900">
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
                <button type="submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-primary hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary">
                    <span class="absolute left-0 inset-y-0 flex items-center pl-3">
                        <i class="fas fa-lock text-blue-300 group-hover:text-blue-200"></i>
                    </span>
                    Sign in
                </button>
            </div>
            
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-gray-50 text-gray-500">Security Features</span>
                    </div>
                </div>
                
                <div class="mt-4 grid grid-cols-2 gap-4 text-sm text-gray-600">
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-shield-check text-green-500"></i>
                        <span>SSL Encrypted</span>
                    </div>
                    <div class="flex items-center space-x-2">
                        <i class="fas fa-user-shield text-green-500"></i>
                        <span>2FA Protected</span>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php unset($_SESSION['old']); ?>
