<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Error - SecureStay</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="text-center">
            <div class="mb-8">
                <i class="fas fa-exclamation-triangle text-red-400 text-8xl mb-4"></i>
                <h1 class="text-6xl font-bold text-gray-900 mb-4">500</h1>
                <h2 class="text-2xl font-semibold text-gray-700 mb-4">Server Error</h2>
                <p class="text-gray-600 mb-8">Something went wrong on our end. Please try again later.</p>
            </div>
            
            <div class="space-x-4">
                <a href="/" class="bg-primary text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors">
                    <i class="fas fa-home mr-2"></i>
                    Go Home
                </a>
                <button onclick="window.location.reload()" class="border border-primary text-primary px-6 py-3 rounded-lg hover:bg-blue-50 transition-colors">
                    <i class="fas fa-redo mr-2"></i>
                    Try Again
                </button>
            </div>
        </div>
    </div>
</body>
</html>
