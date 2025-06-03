<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SecureStay - Student Accommodation' ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#4361EE',
                        secondary: '#4CC9F0',
                        accent: '#FF6B6B',
                        success: '#4CAF50',
                        warning: '#FFC107',
                        error: '#F44336'
                    },
                    fontFamily: {
                        'heading': ['Montserrat', 'sans-serif'],
                        'body': ['Open Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        .verification-badge {
            position: relative;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.875rem;
            font-weight: 600;
        }
        
        .verification-badge.gold {
            background: linear-gradient(135deg, #FFD700, #FFA500);
            color: #8B4513;
        }
        
        .verification-badge.silver {
            background: linear-gradient(135deg, #C0C0C0, #A9A9A9);
            color: #2F4F4F;
        }
        
        .verification-badge.bronze {
            background: linear-gradient(135deg, #CD7F32, #B8860B);
            color: #FFFFFF;
        }
        
        .verification-badge.none {
            background: #F3F4F6;
            color: #6B7280;
            border: 1px solid #D1D5DB;
        }

        .trust-meter {
            width: 100%;
            height: 8px;
            background: #E5E7EB;
            border-radius: 4px;
            overflow: hidden;
        }
        
        .trust-meter-fill {
            height: 100%;
            transition: width 0.3s ease;
        }
        
        .trust-meter-fill.high {
            background: linear-gradient(90deg, #10B981, #059669);
        }
        
        .trust-meter-fill.medium {
            background: linear-gradient(90deg, #F59E0B, #D97706);
        }
        
        .trust-meter-fill.low {
            background: linear-gradient(90deg, #EF4444, #DC2626);
        }

        .chat-bubble {
            max-width: 70%;
            padding: 0.75rem 1rem;
            border-radius: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .chat-bubble.sent {
            background: #4361EE;
            color: white;
            margin-left: auto;
            border-bottom-right-radius: 0.25rem;
        }
        
        .chat-bubble.received {
            background: #F3F4F6;
            color: #1F2937;
            margin-right: auto;
            border-bottom-left-radius: 0.25rem;
        }

        .property-card {
            transition: all 0.3s ease;
            border: 1px solid #E5E7EB;
        }
        
        .property-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            border-color: #4361EE;
        }

        .scam-alert {
            background: linear-gradient(135deg, #FEF2F2, #FEE2E2);
            border: 1px solid #FECACA;
            color: #991B1B;
        }
    </style>
</head>
<body class="bg-gray-50 font-body">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center">
                    <a href="/" class="flex items-center space-x-2">
                        <i class="fas fa-shield-alt text-primary text-2xl"></i>
                        <span class="font-heading font-bold text-xl text-gray-900">SecureStay</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/properties" class="text-gray-700 hover:text-primary transition-colors">Properties</a>
                    <a href="/how-it-works" class="text-gray-700 hover:text-primary transition-colors">How It Works</a>
                    <a href="/safety" class="text-gray-700 hover:text-primary transition-colors">Safety</a>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="/dashboard" class="text-gray-700 hover:text-primary transition-colors">Dashboard</a>
                        <div class="relative group">
                            <button class="flex items-center space-x-1 text-gray-700 hover:text-primary transition-colors">
                                <i class="fas fa-user-circle"></i>
                                <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'User') ?></span>
                                <i class="fas fa-chevron-down text-xs"></i>
                            </button>
                            <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all">
                                <a href="/dashboard/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                                <a href="/verification" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Verification</a>
                                <a href="/dashboard/security" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Security</a>
                                <hr class="my-1">
                                <a href="/logout" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="/login" class="text-gray-700 hover:text-primary transition-colors">Login</a>
                        <a href="/register" class="bg-primary text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">Sign Up</a>
                    <?php endif; ?>
                </div>

                <!-- Mobile menu button -->
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-gray-700 hover:text-primary">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-white border-t border-gray-200">
            <div class="px-2 pt-2 pb-3 space-y-1">
                <a href="/properties" class="block px-3 py-2 text-gray-700 hover:text-primary">Properties</a>
                <a href="/how-it-works" class="block px-3 py-2 text-gray-700 hover:text-primary">How It Works</a>
                <a href="/safety" class="block px-3 py-2 text-gray-700 hover:text-primary">Safety</a>
                
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="/dashboard" class="block px-3 py-2 text-gray-700 hover:text-primary">Dashboard</a>
                    <a href="/dashboard/profile" class="block px-3 py-2 text-gray-700 hover:text-primary">Profile</a>
                    <a href="/verification" class="block px-3 py-2 text-gray-700 hover:text-primary">Verification</a>
                    <a href="/logout" class="block px-3 py-2 text-gray-700 hover:text-primary">Logout</a>
                <?php else: ?>
                    <a href="/login" class="block px-3 py-2 text-gray-700 hover:text-primary">Login</a>
                    <a href="/register" class="block px-3 py-2 text-gray-700 hover:text-primary">Sign Up</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mx-4 mt-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span><?= htmlspecialchars($_SESSION['success']) ?></span>
            </div>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mx-4 mt-4" role="alert">
            <div class="flex items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span><?= htmlspecialchars($_SESSION['error']) ?></span>
            </div>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <i class="fas fa-shield-alt text-primary text-2xl"></i>
                        <span class="font-heading font-bold text-xl">SecureStay</span>
                    </div>
                    <p class="text-gray-400">Secure student accommodation without scams. Verified listings, trusted landlords.</p>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">For Students</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/properties" class="hover:text-white transition-colors">Find Housing</a></li>
                        <li><a href="/safety" class="hover:text-white transition-colors">Safety Guide</a></li>
                        <li><a href="/verification" class="hover:text-white transition-colors">Get Verified</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">For Landlords</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/register" class="hover:text-white transition-colors">List Property</a></li>
                        <li><a href="/verification" class="hover:text-white transition-colors">Get Verified</a></li>
                        <li><a href="/dashboard" class="hover:text-white transition-colors">Manage Listings</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="font-semibold mb-4">Support</h3>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="/help" class="hover:text-white transition-colors">Help Center</a></li>
                        <li><a href="/contact" class="hover:text-white transition-colors">Contact Us</a></li>
                        <li><a href="/report" class="hover:text-white transition-colors">Report Fraud</a></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">&copy; 2024 SecureStay. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="/privacy" class="text-gray-400 hover:text-white transition-colors">Privacy Policy</a>
                    <a href="/terms" class="text-gray-400 hover:text-white transition-colors">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        });

        // Auto-hide flash messages
        setTimeout(function() {
            const alerts = document.querySelectorAll('[role="alert"]');
            alerts.forEach(function(alert) {
                alert.style.opacity = '0';
                setTimeout(function() {
                    alert.remove();
                }, 300);
            });
        }, 5000);

        // Global functions for verification badges and trust scores
        function renderVerificationBadge(level, label) {
            return `<span class="verification-badge ${level}">
                <i class="fas fa-shield${level === 'gold' ? '-check' : level === 'silver' ? '' : level === 'bronze' ? '-alert' : '-x'}"></i>
                ${label}
            </span>`;
        }

        function renderTrustMeter(score) {
            const level = score >= 80 ? 'high' : score >= 50 ? 'medium' : 'low';
            return `<div class="trust-meter">
                <div class="trust-meter-fill ${level}" style="width: ${score}%"></div>
            </div>`;
        }

        // AJAX helper function
        function makeRequest(url, options = {}) {
            const defaultOptions = {
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            };
            
            return fetch(url, { ...defaultOptions, ...options })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                });
        }
    </script>
</body>
</html>
