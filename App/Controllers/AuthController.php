<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;

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
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::redirect('/register');
            return;
        }

        $data = [
            'first_name' => trim($_POST['first_name'] ?? ''),
            'last_name' => trim($_POST['last_name'] ?? ''),
            'email' => trim($_POST['email'] ?? ''),
            'password' => $_POST['password'] ?? '',
            'password_confirmation' => $_POST['password_confirmation'] ?? '',
            'user_type' => $_POST['user_type'] ?? 'student',
            'phone' => trim($_POST['phone'] ?? ''),
            'university' => trim($_POST['university'] ?? ''),
            'terms' => isset($_POST['terms'])
        ];

        // Validation
        $errors = $this->validateRegistration($data);

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            View::redirect('/register');
            return;
        }

        // Create user
        try {
            $userId = User::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => $data['password'],
                'user_type' => $data['user_type'],
                'phone' => $data['phone'],
                'university' => $data['university']
            ]);

            if ($userId) {
                $_SESSION['success'] = 'Registration successful! Please complete your verification to access all features.';
                
                // Auto-login
                session_regenerate_id(true);
                $_SESSION['user_id'] = $userId;
                $_SESSION['user_name'] = $data['first_name'] . ' ' . $data['last_name'];
                $_SESSION['user_type'] = $data['user_type'];

                View::redirect('/verification');
            } else {
                $_SESSION['error'] = 'Registration failed. Please try again.';
                View::redirect('/register');
            }
        } catch (\Exception $e) {
            $_SESSION['error'] = 'Registration failed: ' . $e->getMessage();
            $_SESSION['old'] = $data;
            View::redirect('/register');
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

    private function validateRegistration($data)
    {
        $errors = [];

        // Required fields
        if (empty($data['first_name'])) {
            $errors['first_name'] = 'First name is required';
        }
        if (empty($data['last_name'])) {
            $errors['last_name'] = 'Last name is required';
        }
        if (empty($data['email'])) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } elseif (User::findByEmail($data['email'])) {
            $errors['email'] = 'Email already registered';
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        } elseif ($data['password'] !== $data['password_confirmation']) {
            $errors['password_confirmation'] = 'Passwords do not match';
        }

        if (!in_array($data['user_type'], ['student', 'landlord'])) {
            $errors['user_type'] = 'Invalid user type';
        }

        if (!empty($data['phone']) && !preg_match('/^[\+]?[1-9][\d]{0,15}$/', $data['phone'])) {
            $errors['phone'] = 'Invalid phone number format';
        }

        if ($data['user_type'] === 'student' && empty($data['university'])) {
            $errors['university'] = 'University is required for students';
        }

        if (!$data['terms']) {
            $errors['terms'] = 'You must accept the terms and conditions';
        }

        return $errors;
    }
}
