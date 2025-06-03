<?php

namespace App\Controllers;

use App\Core\View;
use App\Models\Property;

class HomeController
{
    /**
     * Display the home page
     *
     * @return void
     */
    public function index()
    {
        $property = new Property();
        $featuredProperties = $property->where('is_available', '=', 'active');
        
        // Limit to 6 properties
        $featuredProperties = array_slice($featuredProperties, 0, 6);
        
        View::output('home.index', [
            'title' => 'Secure Student Housing Without Scams',
            'featuredProperties' => $featuredProperties
        ]);
    }

    /**
     * Display the about page
     *
     * @return void
     */
    public function about()
    {
        View::output('home.about', [
            'title' => 'About Us'
        ]);
    }

    /**
     * Display the contact page
     *
     * @return void
     */
    public function contact()
    {
        View::output('home.contact', [
            'title' => 'Contact Us'
        ]);
    }
}
