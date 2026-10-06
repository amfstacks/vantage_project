<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Site extends BaseConfig
{
    public string $siteName = 'Vantage Luxe';
    public string $siteNameLong = 'Vantage Luxe Realty';
    public string $defaultTitle = 'Premium Real Estate in Abuja';
    public string $tagline = 'Curated homes. Trusted guidance. Elevated living.';

    public string $contactEmail = 'support@vantage.com';
    public string $contactPhone = '+234 803 339 0219';
    public string $whatsappNumber = '2348033390219';
    public string $address = 'Abuja, Nigeria';
    public string $logoPath = 'assets/img/logo/logo1.png';

    public string $facebookUrl = 'https://facebook.com/Vantage';
    public string $instagramUrl = 'https://instagram.com/Vantage';
    public string $youtubeUrl = '#';

    public string $currency = '₦';
    public string $defaultCity = 'Abuja';
}
