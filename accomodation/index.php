<?php
/**
 * Student Accommodation Listing System
 * Entry point for the application
 */

// Define the base path
define('BASE_PATH', __DIR__);

// Load the bootstrap file
require_once BASE_PATH . '/bootstrap/app.php';

// Initialize the router
$router = new App\Core\Router();

// Define routes
// Home routes
$router->get('/', 'HomeController@index');
$router->get('/about', 'HomeController@about');
$router->get('/contact', 'HomeController@contact');

// Auth routes
$router->get('/login', 'AuthController@loginForm');
$router->post('/login', 'AuthController@login');
$router->get('/register', 'AuthController@registerForm');
$router->post('/register', 'AuthController@register');
$router->get('/logout', 'AuthController@logout');
$router->get('/verify-email/{token}', 'AuthController@verifyEmail');
$router->get('/forgot-password', 'AuthController@forgotPasswordForm');
$router->post('/forgot-password', 'AuthController@forgotPassword');
$router->get('/reset-password/{token}', 'AuthController@resetPasswordForm');
$router->post('/reset-password', 'AuthController@resetPassword');
$router->get('/2fa-setup', 'AuthController@twoFactorSetupForm');
$router->post('/2fa-setup', 'AuthController@twoFactorSetup');
$router->post('/2fa-verify', 'AuthController@twoFactorVerify');

// Property routes
$router->get('/properties', 'PropertyController@index');
$router->get('/properties/{id}', 'PropertyController@show');
$router->get('/properties/create', 'PropertyController@create');
$router->post('/properties', 'PropertyController@store');
$router->get('/properties/{id}/edit', 'PropertyController@edit');
$router->post('/properties/{id}', 'PropertyController@update');
$router->post('/properties/{id}/delete', 'PropertyController@delete');
$router->post('/properties/{id}/images', 'PropertyController@uploadImages');
$router->post('/properties/{id}/images/{imageId}/delete', 'PropertyController@deleteImage');

// Dashboard routes
$router->get('/dashboard', 'DashboardController@index');
$router->get('/dashboard/properties', 'DashboardController@properties');
$router->get('/dashboard/saved', 'DashboardController@saved');
$router->post('/dashboard/save-property/{id}', 'DashboardController@saveProperty');
$router->post('/dashboard/unsave-property/{id}', 'DashboardController@unsaveProperty');
$router->get('/dashboard/messages', 'DashboardController@messages');
$router->get('/dashboard/verification', 'DashboardController@verification');
$router->get('/dashboard/settings', 'DashboardController@settings');
$router->post('/dashboard/settings', 'DashboardController@updateSettings');

// Verification routes
$router->get('/verify', 'VerificationController@index');
$router->post('/verify/upload-document', 'VerificationController@uploadDocument');
$router->post('/verify/selfie', 'VerificationController@uploadSelfie');
$router->get('/verify/status', 'VerificationController@status');
$router->post('/verify/initiate/{provider}', 'VerificationController@initiateVerification');
$router->get('/verify/callback/{provider}', 'VerificationController@verificationCallback');

// API routes
$router->get('/api/properties', 'ApiController@getProperties');
$router->get('/api/properties/{id}', 'ApiController@getProperty');
$router->post('/api/messages', 'ApiController@sendMessage');
$router->get('/api/messages/{conversationId}', 'ApiController@getMessages');
$router->post('/api/report-listing/{id}', 'ApiController@reportListing');
$router->get('/api/verification-status/{userId}', 'ApiController@getVerificationStatus');

// Dispatch the request
$router->dispatch();
