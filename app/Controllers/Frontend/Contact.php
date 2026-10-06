<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class Contact extends BaseController
{
    public function index()
    {
        return view('frontend/contact', [
            'title' => 'Contact Vantage Luxe Realty',
            'meta_description' => 'Contact Vantage Luxe Realty in Abuja for property enquiries, buying, renting, shortlets and real estate support.',
            'canonical_url' => base_url('contact'),
        ]);
    }
}
