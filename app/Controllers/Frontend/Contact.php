<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;

class Contact extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Contact Us'
        ];

        return view('frontend/contact', $data);
    }
}