<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Site extends BaseConfig
{
    // Global Platform Information
    public string $siteName     = 'Vantage Luxe';
    public string $siteNameLong     = 'Vantage Luxe Realty';
    public string $defaultTitle = 'Premium Real Estate Platform';
    public string $contactEmail = 'support@Vantage.com';
    public string $contactPhone = ' +234 803 339 0219 | +234 913 543 3368';
    public string $address      = 'Abuja, Nigeria';
    public string $logoPath     = 'assets/img/logo/logo1.png';
    
    // Social Links
    public string $facebookUrl  = 'https://facebook.com/Vantage';
    public string $instagramUrl = 'https://instagram.com/Vantage';

    // Business Logic Settings
    public string $currency     = '₦';
}