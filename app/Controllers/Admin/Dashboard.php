<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyPriceModel;
use App\Models\PropertyRequestModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $priceModel = new PropertyPriceModel();
        $db = \Config\Database::connect();

        $totalProperties = (new PropertyModel())->countAllResults();
        $activeProperties = (new PropertyModel())->where('status', 'active')->countAllResults();
        $pendingApproval = (new PropertyModel())->where('status', 'pending')->countAllResults();
        $soldProperties = (new PropertyModel())->where('status', 'sold')->countAllResults();

        $newRequests = 0;
        $recentRequests = [];
        if ($db->tableExists('property_requests')) {
            $newRequests = (new PropertyRequestModel())->where('status', 'new')->countAllResults();
            $recentRequests = (new PropertyRequestModel())
                ->select('property_requests.*, properties.title AS property_title, properties.slug AS property_slug')
                ->join('properties', 'properties.id = property_requests.property_id', 'left')
                ->orderBy('property_requests.created_at', 'DESC')
                ->limit(5)
                ->find();
        }

        $recentProperties = $propertyModel
            ->select("properties.*, (SELECT pi.image_path FROM property_images pi WHERE pi.property_id = properties.id ORDER BY pi.is_primary DESC, pi.id ASC LIMIT 1) AS image_path", false)
            ->orderBy('properties.created_at', 'DESC')
            ->limit(5)
            ->find();

        if ($recentProperties !== []) {
            $ids = array_map(static fn ($property) => (int) $property->id, $recentProperties);
            $prices = $priceModel->whereIn('property_id', $ids)->findAll();
            $grouped = [];
            foreach ($prices as $price) {
                $grouped[$price->property_id][] = $price;
            }
            foreach ($recentProperties as $property) {
                $property->prices = $grouped[$property->id] ?? [];
            }
        }

        return view('admin/dashboard', [
            'title' => 'Dashboard',
            'totalProperties' => $totalProperties,
            'activeProperties' => $activeProperties,
            'pendingApproval' => $pendingApproval,
            'soldProperties' => $soldProperties,
            'newRequests' => $newRequests,
            'recentProperties' => $recentProperties,
            'recentRequests' => $recentRequests,
        ]);
    }
}
