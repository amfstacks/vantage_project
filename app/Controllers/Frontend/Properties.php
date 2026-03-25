<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyPriceModel;

class Properties extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $priceModel = new PropertyPriceModel();

        // 1. Capture Search and Filter Inputs
        $searchQuery = $this->request->getGet('q');
        $location    = $this->request->getGet('location');
        $sort        = $this->request->getGet('sort') ?? 'newest';
        $perPage     = $this->request->getGet('per_page') ?? 12;

        // 2. Build the Query
        $builder = $propertyModel
            ->select('properties.*, property_images.image_path, MIN(property_prices.price) as lowest_price')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->join('property_prices', 'property_prices.property_id = properties.id', 'left')
            ->where('properties.status', 'active')
            ->groupBy('properties.id');

        // Apply Search (Title, Description, or City)
        if (!empty($searchQuery)) {
            $builder->groupStart()
                ->like('properties.title', $searchQuery)
                ->orLike('properties.description', $searchQuery)
                ->orLike('properties.city', $searchQuery)
            ->groupEnd();
        }

        // Apply Location Filter (From the Homepage Location Slider)
        if (!empty($location)) {
            $builder->where('properties.location', $location);
        }

        // Apply Sorting
        switch ($sort) {
            case 'price_low':
                $builder->orderBy('lowest_price', 'ASC');
                break;
            case 'price_high':
                $builder->orderBy('lowest_price', 'DESC');
                break;
            case 'oldest':
                $builder->orderBy('properties.created_at', 'ASC');
                break;
            case 'newest':
            default:
                $builder->orderBy('properties.created_at', 'DESC');
                break;
        }

        // 3. Paginate the Results (CodeIgniter handles the limits and offsets automatically!)
        $properties = $builder->paginate($perPage, 'default');
        $pager      = $propertyModel->pager;
        $total      = $pager->getTotal('default');

        // 4. Attach full price details to the paginated properties
        if (!empty($properties)) {
            $propertyIds = array_column($properties, 'id');
            $allPrices = $priceModel->whereIn('property_id', $propertyIds)->findAll();
            $pricesByProperty = [];
            foreach ($allPrices as $price) $pricesByProperty[$price->property_id][] = $price;
            foreach ($properties as $prop) $prop->prices = $pricesByProperty[$prop->id] ?? [];
        }

        $data = [
            'title'      => 'Browse Properties - ' . config('Site')->siteName,
            'properties' => $properties,
            'pager'      => $pager,
            'total'      => $total,
            // Pass current filters back to view to keep dropdowns selected
            'currentSort' => $sort,
            'perPage'     => $perPage,
            'searchQuery' => $searchQuery,
            'location'    => $location
        ];

        return view('frontend/properties_list', $data);
    }
}