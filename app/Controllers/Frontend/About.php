<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class About extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'About Us'
        ];

        return view('frontend/about', $data);
    }
}