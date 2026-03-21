<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyPriceModel;

class Home extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $priceModel = new PropertyPriceModel();

        // 1. Fetch the 6 most recent ACTIVE properties with their primary image
        $properties = $propertyModel
            ->select('properties.*, property_images.image_path')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where('properties.status', 'active')
            // ->orderBy('properties.created_at', 'DESC')
            ->orderBy('RAND()')
            ->limit(6)
            ->find();

        // 2. Attach dynamic prices to these properties
        if (!empty($properties)) {
            $propertyIds = array_column($properties, 'id');
            $allPrices = $priceModel->whereIn('property_id', $propertyIds)->findAll();
            
            $pricesByProperty = [];
            foreach ($allPrices as $price) {
                $pricesByProperty[$price->property_id][] = $price;
            }
            
            foreach ($properties as $prop) {
                $prop->prices = $pricesByProperty[$prop->id] ?? [];
            }
        }

        $data = [
            'title' => 'HouseBox - Find Your Perfect Home',
            'featuredProperties' => $properties
        ];

        return view('frontend/home', $data);
    }
}