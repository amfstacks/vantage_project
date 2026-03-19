<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyPriceModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $priceModel = new PropertyPriceModel();

        // 1. Calculate Analytics
        $totalProperties = $propertyModel->countAllResults();
        $activeShortlets = $propertyModel->where('purpose', 'shortlet')->where('status', 'active')->countAllResults();
        $pendingApproval = $propertyModel->where('status', 'pending')->countAllResults();

        // 2. Calculate Sales Portfolio (Sum of prices for active 'sale' properties)
        $db = \Config\Database::connect();
        $builder = $db->table('properties');
        $builder->selectSum('property_prices.price');
        $builder->join('property_prices', 'property_prices.property_id = properties.id', 'left');
        $builder->where('properties.purpose', 'sale');
        $builder->where('properties.status', 'active');
        $salesPortfolio = $builder->get()->getRow()->price ?? 0;

        // 3. Fetch Recent Properties (Last 5)
        $recentProperties = $propertyModel->orderBy('created_at', 'DESC')->limit(5)->find();

        // Attach the first price to each recent property for the table display
        if (!empty($recentProperties)) {
            $propertyIds = array_column($recentProperties, 'id');
            $allPrices = $priceModel->whereIn('property_id', $propertyIds)->findAll();
            
            $pricesByProperty = [];
            foreach ($allPrices as $price) {
                $pricesByProperty[$price->property_id][] = $price;
            }
            
            foreach ($recentProperties as $prop) {
                $prop->prices = $pricesByProperty[$prop->id] ?? [];
            }
        }

        // 4. Pass data to the view
        $data = [
            'title'            => 'Overview Dashboard',
            'totalProperties'  => $totalProperties,
            'activeShortlets'  => $activeShortlets,
            'pendingApproval'  => $pendingApproval,
            'salesPortfolio'   => $salesPortfolio,
            'recentProperties' => $recentProperties
        ];

        return view('admin/dashboard', $data);
    }
}