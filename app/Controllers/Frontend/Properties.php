<?php

namespace App\Controllers\Frontend;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\PropertyPriceModel;
use App\Models\PropertyImageModel;

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
                 ->orLike('properties.purpose', $searchQuery)
                 ->orLike('properties.property_type', $searchQuery)
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

    public function show_Old($slug)
    {
        $propertyModel = new PropertyModel();
        
        // 1. Fetch the main property by its slug
        $property = $propertyModel->where('slug', $slug)
                                  ->where('status', 'active')
                                  ->first();

        // 404 Error if the property doesn't exist or isn't active
        if (!$property) {
            throw PageNotFoundException::forPageNotFound("Property not found.");
        }
// var_dump($property);
// exit;
        // 2. Fetch all images for the gallery
        $imageModel = new PropertyImageModel();
        $images = $imageModel->where('property_id', $property->id)->findAll();

        // 3. Fetch pricing and calculate the display price
        $priceModel = new PropertyPriceModel();
        $prices = $priceModel->where('property_id', $property->id)->findAll();
        $property->prices = $prices; // Attach to object

        // 4. Fetch Amenities with their custom icons
        $db = \Config\Database::connect();
        $amenities = $db->table('property_amenities')
            ->select('amenities.name, amenities.icon')
            ->join('amenities', 'amenities.id = property_amenities.amenity_id')
            ->where('property_amenities.property_id', $property->id)
            ->orderBy('amenities.name', 'ASC')
            ->get()->getResult();

        // 5. Fetch the Agent/Seller details (Mocked if UserModel isn't ready yet)
        // Assuming you have a users table, otherwise this is a placeholder
        $agent = $db->table('users')->select('first_name, last_name, email')->where('id', $property->user_id)->get()->getRow();
$agent = [];
        $data = [
            'title'     => esc($property->title) . ' - ' . config('Site')->siteName,
            'property'  => $property,
            'images'    => $images,
            'amenities' => $amenities,
            'agent'     => $agent
        ];

        return view('frontend/property_single', $data);
    }

    public function show($slug)
    {
        $propertyModel = new \App\Models\PropertyModel();
        
        // 1. Fetch the main property by its slug
        $property = $propertyModel->where('slug', $slug)
                                  ->where('status', 'active')
                                  ->first();

        // 404 Error if the property doesn't exist
        if (!$property) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Property not found.");
        }

        // 2. Fetch all images
        $imageModel = new \App\Models\PropertyImageModel();
        $images = $imageModel->where('property_id', $property->id)->findAll();

        // 3. Fetch pricing
        $priceModel = new \App\Models\PropertyPriceModel();
        $prices = $priceModel->where('property_id', $property->id)->findAll();
        $property->prices = $prices; 

        // 4. Fetch Amenities
        $db = \Config\Database::connect();
        $amenities = $db->table('property_amenities')
            ->select('amenities.name, amenities.icon')
            ->join('amenities', 'amenities.id = property_amenities.amenity_id')
            ->where('property_amenities.property_id', $property->id)
            ->orderBy('amenities.name', 'ASC')
            ->get()->getResult();

        // 5. SAFE Agent Fetch (Prevents crashes if user_id is missing or null)
        $agent = null;
        if (isset($property->user_id) && !empty($property->user_id)) {
            // Failsafe: check if users table exists before querying
            if ($db->tableExists('users')) {
                $agent = $db->table('users')
                            ->select('first_name, last_name, email') // Added phone & avatar so they don't throw errors
                            ->where('id', $property->user_id)
                            ->get()->getRow();
            }
        }
$primaryImage = !empty($images) ? base_url($images[0]->image_path) : base_url('assets/img/logo/logo1.png');
        $seoTitle = !empty($property->meta_title) 
                    ? $property->meta_title 
                    : $property->title . ' | ' . config('Site')->siteName;
        
        // Description Fallback (Strip HTML tags, remove line breaks, and cut to 150 chars)
        $cleanDescription = trim(preg_replace('/\s+/', ' ', strip_tags($property->description)));
        $seoDescription = !empty($property->meta_description) 
                          ? $property->meta_description 
                          : mb_substr($cleanDescription, 0, 150) . '...';

        $data = [
            'title'            => $seoTitle,
            'meta_description' => $seoDescription,
            'og_image'         => $primaryImage,
            'property'  => $property,
            'images'    => $images,
            'amenities' => $amenities,
            'agent'     => $agent // This is now safely an Object or NULL
        ];
// var_dump($property->title);
// exit;
        return view('frontend/property_single', $data);
    }
}