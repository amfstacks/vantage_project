<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyPriceModel;
use App\Models\PropertyPurposeModel;
use App\Models\PropertyTypeModel;

class Home extends BaseController
{
    public function index()
    {
        $propertyModel = new PropertyModel();
        $priceModel = new PropertyPriceModel();
        $db = \Config\Database::connect();

        $properties = $propertyModel
            ->select("properties.*, pp.name AS purpose_name, pt.name AS property_type_name, (SELECT pi.image_path FROM property_images pi WHERE pi.property_id = properties.id ORDER BY pi.is_primary DESC, pi.id ASC LIMIT 1) AS image_path", false)
            ->join('property_purposes pp', 'pp.id = properties.purpose_id', 'left')
            ->join('property_types pt', 'pt.id = properties.property_type_id', 'left')
            ->where('properties.status', 'active')
            ->orderBy('properties.created_at', 'DESC')
            ->limit(6)
            ->find();

        $this->attachPrices($properties, $priceModel);

        $locations = $db->table('properties')
            ->select('location, COUNT(*) AS property_count')
            ->where('status', 'active')
            ->where('location !=', '')
            ->groupBy('location')
            ->orderBy('property_count', 'DESC')
            ->limit(8)
            ->get()
            ->getResult();

        // Fetch location imagery in one query, then choose one image per location.
        // We intentionally allow ANY image attached to an active property in that location,
        // not only the primary image, so the section feels fresh and visually rich.
        if ($locations !== []) {
            $locationNames = array_values(array_filter(array_map(static fn ($row) => trim((string) $row->location), $locations)));
            $imagesByLocation = [];

            if ($locationNames !== []) {
                $imageRows = $db->table('property_images pi')
                    ->select('p.location, pi.image_path')
                    ->join('properties p', 'p.id = pi.property_id')
                    ->where('p.status', 'active')
                    ->whereIn('p.location', $locationNames)
                    ->where('pi.image_path !=', '')
                    ->get()
                    ->getResult();

                foreach ($imageRows as $imageRow) {
                    $imagesByLocation[$imageRow->location][] = $imageRow->image_path;
                }
            }

            foreach ($locations as $location) {
                $candidates = $imagesByLocation[$location->location] ?? [];
                $location->image_path = $candidates !== []
                    ? $candidates[array_rand($candidates)]
                    : null;
            }
        }

        $activeCount = (new PropertyModel())->where('status', 'active')->countAllResults();
        $salePurpose = (new PropertyPurposeModel())->where('slug', 'sale')->first();
        $shortletPurpose = (new PropertyPurposeModel())->where('slug', 'shortlet')->first();
        $saleCount = $salePurpose
            ? (new PropertyModel())->where('status', 'active')->where('purpose_id', (int) $salePurpose->id)->countAllResults()
            : (new PropertyModel())->where('status', 'active')->where('purpose', 'sale')->countAllResults();
        $shortletCount = $shortletPurpose
            ? (new PropertyModel())->where('status', 'active')->where('purpose_id', (int) $shortletPurpose->id)->countAllResults()
            : (new PropertyModel())->where('status', 'active')->where('purpose', 'shortlet')->countAllResults();

        $purposes = (new PropertyPurposeModel())
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        $types = (new PropertyTypeModel())
            ->where('is_active', 1)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('name', 'ASC')
            ->findAll();

        return view('frontend/home', [
            'title' => config('Site')->defaultTitle,
            'meta_description' => 'Discover carefully curated properties for sale, rent and shortlet in Abuja with Vantage Luxe Realty. Search premium listings, view media and request a viewing online.',
            'canonical_url' => base_url('/'),
            'featuredProperties' => $properties,
            'locations' => $locations,
            'activeCount' => $activeCount,
            'saleCount' => $saleCount,
            'shortletCount' => $shortletCount,
            'purposes' => $purposes,
            'types' => $types,
        ]);
    }

    private function attachPrices(array &$properties, PropertyPriceModel $priceModel): void
    {
        if ($properties === []) {
            return;
        }

        $ids = array_map(static fn ($property) => (int) $property->id, $properties);
        $prices = $priceModel
            ->select('property_prices.*, property_purposes.name AS purpose_name, property_purposes.slug AS purpose_slug')
            ->join('property_purposes', 'property_purposes.id = property_prices.purpose_id', 'left')
            ->whereIn('property_prices.property_id', $ids)
            ->orderBy('property_prices.id', 'ASC')
            ->findAll();

        $grouped = [];
        foreach ($prices as $price) {
            $grouped[$price->property_id][] = $price;
        }
        foreach ($properties as $property) {
            $property->prices = $grouped[$property->id] ?? [];
        }
    }
}
