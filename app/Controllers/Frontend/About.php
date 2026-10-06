<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class About extends BaseController
{
    public function index()
    {
        return view('frontend/about', [
            'title' => 'About Vantage Luxe Realty',
            'meta_description' => 'Learn about Vantage Luxe Realty, an Abuja-based real estate and lifestyle company serving residential, commercial, investment and property management needs.',
            'canonical_url' => base_url('about'),
        ]);
    }
}
