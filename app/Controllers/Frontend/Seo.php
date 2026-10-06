<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\PropertyModel;

class Seo extends BaseController
{
    public function robots()
    {
        $body = "User-agent: *\nAllow: /\nDisallow: /admin/\nDisallow: /login\nSitemap: " . base_url('sitemap.xml') . "\n";
        return $this->response->setContentType('text/plain')->setBody($body);
    }

    public function sitemap()
    {
        $properties = (new PropertyModel())
            ->select('slug, updated_at')
            ->where('status', 'active')
            ->orderBy('updated_at', 'DESC')
            ->findAll();

        return $this->response
            ->setContentType('application/xml')
            ->setBody(view('frontend/sitemap', ['properties' => $properties]));
    }
}
