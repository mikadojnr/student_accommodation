<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Property;
use App\Models\User;

class HomeController
{
    public function index()
    {
        // echo "Welcome home";
        $featuredProperties = Property::getFeatured(6);
        $recentProperties = Property::getRecentlyAdded(6);
        $stats = Property::getStatistics();

        View::render('home/index', [
            'title' => 'SecureStay - Safe Student Accommodation',
            'featuredProperties' => $featuredProperties,
            'recentProperties' => $recentProperties,
            'stats' => $stats
        ]);
    }

    // public function index()
    // {
    //     echo "Step 1<br>";
    //     $featuredProperties = \App\Models\Property::getFeatured(6);
    //     echo "Step 2<br>";
    //     $recentProperties = \App\Models\Property::getRecentlyAdded(6);
    //     echo "Step 3<br>";
    //     $stats = \App\Models\Property::getStatistics();
    //     echo "Step 4<br>";

    //     View::render('home/index', [
    //         'title' => 'SecureStay - Safe Student Accommodation',
    //         'featuredProperties' => $featuredProperties,
    //         'recentProperties' => $recentProperties,
    //         'stats' => $stats
    //     ]);
    // }


    public function howItWorks()
    {
        View::render('home/how-it-works', [
            'title' => 'How It Works - SecureStay'
        ]);
    }

    public function safety()
    {
        View::render('home/safety', [
            'title' => 'Safety Guide - SecureStay'
        ]);
    }

    public function about()
    {
        View::render('home/about', [
            'title' => 'About Us - SecureStay'
        ]);
    }

    public function contact()
    {
        View::render('home/contact', [
            'title' => 'Contact Us - SecureStay'
        ]);
    }

    public function contactSubmit()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            View::redirect('/contact');
            return;
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $subject = trim($_POST['subject'] ?? '');
        $message = trim($_POST['message'] ?? '');

        // Validation
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Name is required';
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Valid email is required';
        }
        if (empty($subject)) {
            $errors['subject'] = 'Subject is required';
        }
        if (empty($message)) {
            $errors['message'] = 'Message is required';
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = compact('name', 'email', 'subject', 'message');
            View::redirect('/contact');
            return;
        }

        // Send email (implement your email sending logic here)
        $emailSent = sendEmail(
            env('CONTACT_EMAIL', 'contact@securestay.com'),
            "Contact Form: {$subject}",
            "Name: {$name}\nEmail: {$email}\n\nMessage:\n{$message}"
        );

        if ($emailSent) {
            $_SESSION['success'] = 'Thank you for your message. We will get back to you soon!';
        } else {
            $_SESSION['error'] = 'Failed to send message. Please try again later.';
        }

        View::redirect('/contact');
    }

    public function privacy()
    {
        View::render('home/privacy', [
            'title' => 'Privacy Policy - SecureStay'
        ]);
    }

    public function terms()
    {
        View::render('home/terms', [
            'title' => 'Terms of Service - SecureStay'
        ]);
    }

    public function help()
    {
        View::render('home/help', [
            'title' => 'Help Center - SecureStay'
        ]);
    }
}
