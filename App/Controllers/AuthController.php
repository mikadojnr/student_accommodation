<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use App\Models\Verification;

class AuthController
{
    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            View::redirect('/dashboard');
            return;
        }
        
        View::render('auth/login', ['title' => 'Login - SecureStay']);
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::redirect('/login');
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        // Validation
        $errors = [];
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        }
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = ['email' => $email];
            View::redirect('/login');
            return;
        }

        // Find user
        $user = User::findByEmail($email);
        if (!$user || !password_verify($password, $user->password)) {
            $_SESSION['error'] = 'Invalid email or password';
            $_SESSION['old'] = ['email' => $email];
            View::redirect('/login');
            return;
        }

        // Login successful
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_name'] = $user->first_name . ' ' . $user->last_name;
        $_SESSION['user_type'] = $user->user_type;

        // Update last login
        $userModel = new User();
        $userModel->updateLastLogin($user->id);

        // Set remember me cookie if requested
        if ($remember) {
            $token = bin2hex(random_bytes(32));
            setcookie('remember_token', $token, time() + (86400 * 30), '/'); // 30 days
            $userModel->update($user->id, ['remember_token' => $token]);
        }

        $_SESSION['success'] = 'Welcome back, ' . $user->first_name . '!';
        
        // Redirect to intended page or dashboard
        $redirect = $_SESSION['intended_url'] ?? '/dashboard';
        unset($_SESSION['intended_url']);
        View::redirect($redirect);
    }

    public function showRegister()
    {
        if (isset($_SESSION['user_id'])) {
            View::redirect('/dashboard');
            return;
        }
        
        View::render('auth/register', ['title' => 'Register - SecureStay']);
    }

   public function register()
    {
        // Verify CSRF token
        if (!verify_csrf_token($_POST['csrf_token'])) {
            $_SESSION['errors'] = ['csrf_token' => 'Invalid CSRF token'];
            redirect('/register');
        }

        // Validate input
        $errors = $this->validateRegistrationInput($_POST);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $_POST;
            redirect('/register');
        }

        // Create user
        try {
            $userData = [
                'first_name' => sanitize($_POST['first_name']),
                'last_name' => sanitize($_POST['last_name']),
                'email' => sanitize($_POST['email']),
                'phone' => !empty($_POST['phone']) ? sanitize($_POST['phone']) : null,
                'university' => !empty($_POST['university']) ? sanitize($_POST['university']) : null,
                'password' => $_POST['password'],
                'user_type' => $_POST['user_type'],
                'verification_status' => 'unverified'
            ];

            $user = User::create($userData);

            if ($user) {
                // Create initial verification record
                Verification::create([
                    'user_id' => $user->id,
                    'identity_status' => 'not_started',
                    'address_status' => 'not_started',
                    'student_status' => $user->user_type === 'student' ? 'not_started' : null,
                    'biometric_status' => 'not_started'
                ]);

                // Log the user in
                $_SESSION['user_id'] = $user->id;
                logActivity("User registered: {$user->email}", 'info');

                // Redirect based on user type
                $redirectPath = $user->user_type === 'landlord' ? '/dashboard/landlord' : '/dashboard/student';
                redirect($redirectPath);
            } else {
                $_SESSION['errors'] = ['general' => 'Failed to create user'];
                $_SESSION['old'] = $_POST;
                redirect('/register');
            }
        } catch (\Exception $e) {
            logActivity("Registration error: " . $e->getMessage(), 'error');
            $_SESSION['errors'] = ['general' => 'An error occurred during registration'];
            $_SESSION['old'] = $_POST;
            redirect('/register');
        }
    }


    public function logout()
    {
        // Clear remember me cookie
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/');
        }

        // Destroy session
        session_destroy();
        
        View::redirect('/');
    }

    

    private function validateRegistrationInput($data)
    {
        $errors = [];

        // Required fields
        $requiredFields = [
            'first_name' => 'First name',
            'last_name' => 'Last name',
            'email' => 'Email',
            'password' => 'Password',
            'password_confirmation' => 'Password confirmation',
            'user_type' => 'User type',
            'terms' => 'Terms and conditions'
        ];

        foreach ($requiredFields as $field => $label) {
            if ($error = validateRequired($data[$field] ?? '', $label)) {
                $errors[$field] = $error;
            }
        }

        // Validate user type
        if (!in_array($data['user_type'] ?? '', ['student', 'landlord'])) {
            $errors['user_type'] = 'Invalid user type';
        }

        // Validate email
        if (!$error = validateEmail($data['email'] ?? '')) {
            // Check if email is unique
            if (User::findByEmail($data['email'])) {
                $errors['email'] = 'Email is already registered';
            }
        } else {
            $errors['email'] = $error;
        }

        // Validate password
        if ($error = validateMinLength($data['password'] ?? '', 8, 'Password')) {
            $errors['password'] = $error;
        }

        // Validate password confirmation
        if (($data['password'] ?? '') !== ($data['password_confirmation'] ?? '')) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        // Validate phone (if provided)
        if (!empty($data['phone']) && !isValidPhone($data['phone'])) {
            $errors['phone'] = 'Invalid phone number format';
        }

        // Validate university (if provided and user is student)
        if ($data['user_type'] === 'student' && empty($data['university'])) {
            $errors['university'] = 'University is required for students';
        }

        return $errors;
    }
}
