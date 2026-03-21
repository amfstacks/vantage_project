<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyPriceModel;

class PropertyAjax extends BaseController
{
    public function loadAllFeaturedTabs()
    {
        helper('url');
        // sleep(5);
        $propertyModel = new PropertyModel();
        $priceModel = new PropertyPriceModel();
        $db = \Config\Database::connect();

        // 1. Fetch Dynamic Categories
        $purposes = $db->query("SELECT DISTINCT purpose as name FROM properties WHERE status = 'active'")->getResultArray();
        $types    = $db->query("SELECT DISTINCT property_type as name FROM properties WHERE status = 'active'")->getResultArray();

        $allCategories = [];
        foreach($purposes as $p) $allCategories[] = strtolower($p['name']);
        foreach($types as $t) $allCategories[] = strtolower($t['name']);

        $allCategories = array_unique($allCategories);
        shuffle($allCategories);
        $tabCategories = array_slice($allCategories, 0, 6); // Max 6 tabs

        // 2. Fetch up to 10 properties for EACH category
        $tabsData = [];
        $allPropertyIds = [];
        
       foreach ($tabCategories as $category) {
            $props = $propertyModel
                ->select('properties.*, property_images.image_path')
                ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
                ->where('properties.status', 'active')
                ->groupStart()
                    ->where('properties.purpose', $category)
                    ->orWhere('properties.property_type', $category)
                ->groupEnd()
                
                // ---> THE FIX: Forces absolute uniqueness per property <---
                ->groupBy('properties.id') 
                
                ->orderBy('RAND()')
                ->limit(10) 
                ->find();
                
            $tabsData[$category] = $props;
            foreach($props as $p) $allPropertyIds[] = $p->id;
        }

        // 3. Attach prices in bulk for efficiency
        if (!empty($allPropertyIds)) {
            $allPrices = $priceModel->whereIn('property_id', array_unique($allPropertyIds))->findAll();
            $pricesByProperty = [];
            foreach ($allPrices as $price) $pricesByProperty[$price->property_id][] = $price;
            
            foreach ($tabsData as $cat => &$propsArray) {
                foreach ($propsArray as &$prop) {
                    $prop->prices = $pricesByProperty[$prop->id] ?? [];
                }
            }
        }

        // 4. Render the component and return the JSON payload
        $html = view('components/home/featured_tabs', ['tabsData' => $tabsData]);
        
        return $this->response->setJSON(['html' => $html]);
    }

    public function getAmenities_old($propertyId)
    {
        sleep(5);
        $db = \Config\Database::connect();
        
        // Query the pivot table to get the names of the amenities for this property
        $builder = $db->table('property_amenities');
        $builder->select('amenities.name, amenities.icon');
        $builder->join('amenities', 'amenities.id = property_amenities.amenity_id');
        $builder->where('property_amenities.property_id', $propertyId);
        $builder->orderBy('amenities.name', 'ASC');
        
        $amenities = $builder->get()->getResult();

        // Generate the HTML payload
        if (empty($amenities)) {
            $html = '<div class="text-center py-4 text-muted">No specific amenities listed for this property.</div>';
        } else {
            $html = '<div class="row">';
            foreach($amenities as $am) {
                // Use a beautiful gold checkmark for each item
                $html .= '<div class="col-6 mb-3 font-semibold text-gray-700">';
                $html .= '<i class="fa-solid fa-circle-check mr-2" style="color: #D4AF37;"></i> ' . esc($am->name);
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        return $this->response->setJSON(['html' => $html]);
    }
    public function getAmenities($propertyId)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('property_amenities');
        // ---> FETCH BOTH NAME AND ICON DIRECTLY FROM THE DB <---
        $builder->select('amenities.name, amenities.icon'); 
        $builder->join('amenities', 'amenities.id = property_amenities.amenity_id');
        $builder->where('property_amenities.property_id', $propertyId);
        $builder->orderBy('amenities.name', 'ASC');
        
        $amenities = $builder->get()->getResult();

        if (empty($amenities)) {
            $html = '<div class="text-center py-5"><i class="fa-solid fa-box-open fa-3x text-gray-300 mb-3"></i><h5 class="text-muted">No specific amenities listed yet.</h5></div>';
        } else {
            $html = '<div class="row g-3">'; 
            foreach($amenities as $am) {
                // Use the database icon, fallback to a checkmark if empty
                $iconClass = !empty($am->icon) ? esc($am->icon) : 'fa-check';
                
                // The Luxury Premium Pill Design
                $html .= '<div class="col-md-6">';
                $html .= '  <div class="amenity-pill d-flex align-items-center p-3 rounded" style="background: #fdfdfd; border: 1px solid #f1f5f9; transition: all 0.3s ease;">';
                $html .= '      <div class="d-flex align-items-center justify-content-center rounded-circle mr-3 shadow-sm" style="width: 42px; height: 42px; background: rgba(212, 175, 55, 0.1); border: 1px solid rgba(212, 175, 55, 0.2);">';
                $html .= '          <i class="fa-solid ' . $iconClass . '" style="color: #D4AF37; font-size: 16px;"></i>';
                $html .= '      </div>';
                $html .= '      <span class="font-bold text-dark" style="font-size: 15px;">' . esc($am->name) . '</span>';
                $html .= '  </div>';
                $html .= '</div>';
            }
            $html .= '</div>';
        }

        return $this->response->setJSON(['html' => $html]);
    }

    public function loadRecentProperties()
    {
        $propertyModel = new \App\Models\PropertyModel();
        $priceModel = new \App\Models\PropertyPriceModel();

        // 1. Fetch the 5 most recently created properties
        $recentProperties = $propertyModel
            ->select('properties.*, property_images.image_path')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->where('properties.status', 'active')
            ->groupBy('properties.id') // Prevent duplicates
            ->orderBy('properties.created_at', 'DESC') // Sort by newest first
            ->limit(5)
            ->find();

        // 2. Attach prices
        if (!empty($recentProperties)) {
            $propertyIds = array_column($recentProperties, 'id');
            $allPrices = $priceModel->whereIn('property_id', array_unique($propertyIds))->findAll();
            $pricesByProperty = [];
            foreach ($allPrices as $price) $pricesByProperty[$price->property_id][] = $price;
            
            foreach ($recentProperties as $prop) {
                $prop->prices = $pricesByProperty[$prop->id] ?? [];
            }
        }

        // 3. Generate HTML Payload
        $html = view('components/home/recent_slider', ['recentProperties' => $recentProperties]);
        
        return $this->response->setJSON(['html' => $html]);
    }
}