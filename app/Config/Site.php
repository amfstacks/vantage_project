<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Site extends BaseConfig
{
    // Global Platform Information
    public string $siteName     = 'Vantage Luxe';
    public string $defaultTitle = 'Premium Real Estate Platform';
    public string $contactEmail = 'support@Vantage.com';
    public string $contactPhone = '+234 800 000 0000';
    public string $address      = '123 Real Estate Avenue, Abuja, FCT';
    
    // Social Links
    public string $facebookUrl  = 'https://facebook.com/Vantage';
    public string $instagramUrl = 'https://instagram.com/Vantage';

    // Business Logic Settings
    public string $currency     = '₦';
}