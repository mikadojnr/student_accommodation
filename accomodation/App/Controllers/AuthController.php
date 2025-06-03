<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\User;
use App\Models\Verification;

class AuthController
{
    public function showLogin()
    {
        View::render('auth/login');
    }

    public function showRegister()
    {
        View::render('auth/register');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /login');
            exit;
        }

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Email and password are required';
            header('Location: /login');
            exit;
        }

        $user = User::findByEmail($email);
        
        if (!$user || !password_verify($password, $user['password'])) {
            $_SESSION['error'] = 'Invalid credentials';
            header('Location: /login');
            exit;
        }

        // Update last login
        User::updateLastLogin($user['id']);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_type'] = $user['user_type'];
        $_SESSION['success'] = 'Welcome back!';
        
        header('Location: /dashboard');
        exit;
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /register');
            exit;
        }

        $data = [
            'first_name' => $_POST['first_name'] ?? '',
            'last_name' => $_POST['last_name'] ?? '',
            'email' => $_POST['email'] ?? '',
            'password' => $_POST['password'] ?? '',
            'confirm_password' => $_POST['confirm_password'] ?? '',
            'user_type' => $_POST['user_type'] ?? 'student',
            'phone' => $_POST['phone'] ?? '',
            'university' => $_POST['university'] ?? ''
        ];

        // Validation
        $errors = $this->validateRegistration($data);
        
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: /register');
            exit;
        }

        // Check if email exists
        if (User::findByEmail($data['email'])) {
            $_SESSION['error'] = 'Email already exists';
            $_SESSION['old'] = $data;
            header('Location: /register');
            exit;
        }

        // Create user
        $userId = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
            'user_type' => $data['user_type'],
            'phone' => $data['phone'],
            'university' => $data['university']
        ]);

        if ($userId) {
            $_SESSION['user_id'] = $userId;
            $_SESSION['user_type'] = $data['user_type'];
            $_SESSION['success'] = 'Registration successful! Please complete your verification.';
            header('Location: /verification');
            exit;
        } else {
            $_SESSION['error'] = 'Registration failed. Please try again.';
            header('Location: /register');
            exit;
        }
    }

    public function logout()
    {
        session_destroy();
        header('Location: /');
        exit;
    }

    private function validateRegistration($data)
    {
        $errors = [];

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
        }

        if (empty($data['password'])) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($data['password']) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        }

        if ($data['password'] !== $data['confirm_password']) {
            $errors['confirm_password'] = 'Passwords do not match';
        }

        if (empty($data['phone'])) {
            $errors['phone'] = 'Phone number is required';
        }

        if ($data['user_type'] === 'student' && empty($data['university'])) {
            $errors['university'] = 'University is required for students';
        }

        return $errors;
    }
}
