<?php

/**
 * Helper functions for the application
 */

function env($key, $default = null)
{
    return $_ENV[$key] ?? $default;
}

function redirect($url)
{
    header("Location: $url");
    exit;
}

function old($key, $default = '')
{
    return $_SESSION['old'][$key] ?? $default;
}

function error($key)
{
    return $_SESSION['errors'][$key] ?? null;
}

function hasError($key)
{
    return isset($_SESSION['errors'][$key]);
}

function csrf_token()
{
    if (!isset($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf_token($token)
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

function formatPrice($price)
{
    return '£' . number_format($price, 0);
}

function formatDate($date)
{
    return date('M j, Y', strtotime($date));
}

function timeAgo($datetime)
{
    $time = time() - strtotime($datetime);
    
    if ($time < 60) return 'just now';
    if ($time < 3600) return floor($time/60) . ' minutes ago';
    if ($time < 86400) return floor($time/3600) . ' hours ago';
    if ($time < 2592000) return floor($time/86400) . ' days ago';
    
    return formatDate($datetime);
}

function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

function currentUser()
{
    if (!isLoggedIn()) {
        return null;
    }
    
    return \App\Models\User::find($_SESSION['user_id']);
}

function asset($path)
{
    return '/' . ltrim($path, '/');
}

function url($path)
{
    return '/' . ltrim($path, '/');
}

function sanitize($string)
{
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

function truncate($string, $length = 100)
{
    if (strlen($string) <= $length) {
        return $string;
    }
    
    return substr($string, 0, $length) . '...';
}

function generateSlug($string)
{
    $slug = strtolower($string);
    $slug = preg_replace('/[^a-z0-9\s-]/', '', $slug);
    $slug = preg_replace('/[\s-]+/', '-', $slug);
    $slug = trim($slug, '-');
    
    return $slug;
}

function isValidEmail($email)
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function isValidPhone($phone)
{
    return preg_match('/^[\+]?[1-9][\d]{0,15}$/', $phone);
}

function generateRandomString($length = 32)
{
    return bin2hex(random_bytes($length / 2));
}

function uploadFile($file, $directory = 'uploads')
{
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $uploadDir = "public/$directory/";
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }
    
    $fileName = uniqid() . '_' . $file['name'];
    $filePath = $uploadDir . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $filePath)) {
        return "/$directory/$fileName";
    }
    
    return false;
}

function deleteFile($path)
{
    $fullPath = 'public' . $path;
    if (file_exists($fullPath)) {
        return unlink($fullPath);
    }
    return false;
}

function validateRequired($value, $fieldName)
{
    if (empty($value)) {
        return "$fieldName is required";
    }
    return null;
}

function validateEmail($email)
{
    if (!isValidEmail($email)) {
        return "Invalid email format";
    }
    return null;
}

function validateMinLength($value, $minLength, $fieldName)
{
    if (strlen($value) < $minLength) {
        return "$fieldName must be at least $minLength characters";
    }
    return null;
}

function validateMaxLength($value, $maxLength, $fieldName)
{
    if (strlen($value) > $maxLength) {
        return "$fieldName must not exceed $maxLength characters";
    }
    return null;
}

function validateNumeric($value, $fieldName)
{
    if (!is_numeric($value)) {
        return "$fieldName must be a number";
    }
    return null;
}

function validatePositive($value, $fieldName)
{
    if ($value <= 0) {
        return "$fieldName must be positive";
    }
    return null;
}

function logActivity($message, $level = 'info')
{
    $logFile = 'logs/app.log';
    $logDir = dirname($logFile);
    
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $timestamp = date('Y-m-d H:i:s');
    $userId = $_SESSION['user_id'] ?? 'guest';
    $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    $logEntry = "[$timestamp] [$level] [User: $userId] [IP: $ip] $message" . PHP_EOL;
    
    file_put_contents($logFile, $logEntry, FILE_APPEND | LOCK_EX);
}

function sendEmail($to, $subject, $message, $from = null)
{
    $from = $from ?? env('MAIL_FROM', 'noreply@securestay.com');
    
    $headers = [
        'From' => $from,
        'Reply-To' => $from,
        'Content-Type' => 'text/html; charset=UTF-8',
        'MIME-Version' => '1.0'
    ];
    
    $headerString = '';
    foreach ($headers as $key => $value) {
        $headerString .= "$key: $value\r\n";
    }
    
    return mail($to, $subject, $message, $headerString);
}

function generateVerificationCode($length = 6)
{
    return str_pad(random_int(0, pow(10, $length) - 1), $length, '0', STR_PAD_LEFT);
}

function isProduction()
{
    return env('APP_ENV') === 'production';
}

function isDevelopment()
{
    return env('APP_ENV') === 'development';
}

function config($key, $default = null)
{
    $config = [
        'app.name' => 'SecureStay',
        'app.url' => env('APP_URL', 'http://localhost'),
        'app.timezone' => 'UTC',
        'mail.from' => env('MAIL_FROM', 'noreply@securestay.com'),
        'upload.max_size' => 5 * 1024 * 1024, // 5MB
        'upload.allowed_types' => ['jpg', 'jpeg', 'png', 'pdf'],
        'verification.expiry_days' => 30,
        'session.lifetime' => 120, // minutes
    ];
    
    return $config[$key] ?? $default;
}
