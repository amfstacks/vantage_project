<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PropertyModel;
use App\Models\AmenityModel;
use App\Models\PropertyAmenityModel;
use App\Models\PropertyImageModel;
use App\Models\PropertyPriceModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class PropertyManager extends BaseController
{
    protected $propertyModel;
    protected $amenityModel;
    protected $propertyAmenityModel;
    protected $propertyImageModel;
    protected $propertyPriceModel;
    protected $db;

    public function __construct()
    {
        // Initialize all required models
        $this->propertyModel = new PropertyModel();
        $this->amenityModel = new AmenityModel();
        $this->propertyAmenityModel = new PropertyAmenityModel();
        $this->propertyImageModel = new PropertyImageModel();
        $this->propertyPriceModel = new propertyPriceModel();
        
        // Initialize database connection for transactions
        $this->db = \Config\Database::connect();
    }

    // 1. Display all properties
   public function index_old()
    {
        // We use a LEFT JOIN to grab the primary image for the thumbnail.
        // If the property has no image, it returns NULL and we handle it gracefully in the view.
        $properties = $this->propertyModel
            ->select('properties.*, property_images.image_path')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->orderBy('properties.created_at', 'DESC')
            ->paginate(10); // Fetches exactly 10 records per page

        $data = [
            'title'      => 'Manage Properties',
            'properties' => $properties,
            'pager'      => $this->propertyModel->pager // Passes the pagination engine to the view
        ];

        return view('admin/properties_list', $data);
    }
    public function index()
    {
        $properties = $this->propertyModel
            ->select('properties.*, property_images.image_path')
            ->join('property_images', 'property_images.property_id = properties.id AND property_images.is_primary = 1', 'left')
            ->orderBy('properties.created_at', 'DESC')
            ->paginate(10);

        // NEW: Fetch dynamic prices for these specific properties
        if (!empty($properties)) {
            // Get all IDs of properties on this page
            $propertyIds = array_column($properties, 'id');
            // Fetch all prices associated with these IDs
            $allPrices = $this->propertyPriceModel->whereIn('property_id', $propertyIds)->findAll();
            
            // Group the prices by property ID
            $pricesByProperty = [];
            foreach ($allPrices as $price) {
                $pricesByProperty[$price->property_id][] = $price;
            }

            // Attach the grouped prices back to the property objects
            foreach ($properties as $prop) {
                $prop->prices = $pricesByProperty[$prop->id] ?? [];
            }
        }

        $data = [
            'title'      => 'Manage Properties',
            'properties' => $properties,
            'pager'      => $this->propertyModel->pager
        ];

        return view('admin/properties_list', $data);
    }

    // 2. Display the Add Property Form
    public function create()
    {
        $data = [
            'title'     => 'Add New Property',
            'amenities' => $this->amenityModel->orderBy('name', 'ASC')->findAll()
        ];
        return view('admin/property_form', $data);
    }

    // 3. Process the Form Submission (Painstakingly DRY and Secure)
    public function store()
    {
        // --- 1. STRICT VALIDATION ---
        $rules = [
            'title'         => 'required|min_length[5]|max_length[255]',
            'purpose'       => 'required|in_list[sale,rent,shortlet]',
            'property_type' => 'required',
            'prices.*.price'      => 'required|numeric',
            'prices.*.price_unit' => 'required',

            'location'      => 'required|max_length[100]',
            'city'          => 'required|max_length[100]',
            'description'   => 'required',
            // Image validation: allow multiple, max 5MB, strict mime types
            'images'        => 'uploaded[images]|max_size[images,5120]|is_image[images]|ext_in[images,jpg,jpeg,png,webp]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        // --- 2. PREPARE CORE PROPERTY DATA ---
        // Determine status based on which button was clicked (Draft vs Publish)
        $action = $this->request->getPost('action');
        $status = ($action === 'publish') ? 'active' : 'pending';

        $propertyData = [
            'user_id'          => session()->get('user_id'), // Logged in admin/seller
            'title'            => $this->request->getPost('title'),
            'purpose'          => $this->request->getPost('purpose'),
            'property_type'    => $this->request->getPost('property_type'),
            // 'price'            => $this->request->getPost('price'),
            // 'price_unit'       => $this->request->getPost('price_unit'),
            // 'discount_price'   => $this->request->getPost('discount_price') ?: null,
            'address'          => $this->request->getPost('address'),
            'location'         => $this->request->getPost('location'),
            'city'             => $this->request->getPost('city'),
            'latitude'         => $this->request->getPost('latitude'),
            'longitude'        => $this->request->getPost('longitude'),
            'bedrooms'         => $this->request->getPost('bedrooms') ?: 0,
            'bathrooms'        => $this->request->getPost('bathrooms') ?: 0,
            'toilets'          => $this->request->getPost('toilets') ?: 0,
            'area_sqm'         => $this->request->getPost('area_sqm') ?: null,
            'description'      => $this->request->getPost('description'), // Includes Quill HTML
            'video_url'        => $this->request->getPost('video_url'),
            'virtual_tour_url' => $this->request->getPost('virtual_tour_url'),
            'meta_title'       => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'status'           => $status
        ];

        // --- 3. BEGIN DATABASE TRANSACTION ---
        // If anything fails from here down, NO data is saved to the database.
        $this->db->transStart();

        try {
            // A. Insert the main property record
            $this->propertyModel->insert($propertyData);
            $propertyId = $this->propertyModel->getInsertID();

            // B. Process Amenities (Pivot Table)
            $selectedAmenities = $this->request->getPost('amenities');
            if (!empty($selectedAmenities) && is_array($selectedAmenities)) {
                $amenityBatch = [];
                foreach ($selectedAmenities as $amenityId) {
                    $amenityBatch[] = [
                        'property_id' => $propertyId,
                        'amenity_id'  => $amenityId
                    ];
                }
                $this->propertyAmenityModel->insertBatch($amenityBatch);
            }
            $pricesPost = $this->request->getPost('prices');
            if (!empty($pricesPost) && is_array($pricesPost)) {
                $priceBatch = [];
                foreach ($pricesPost as $p) {
                    if (!empty($p['price'])) { // Only save if a price was entered
                        $priceBatch[] = [
                            'property_id'    => $propertyId, // use $id if in update()
                            'price'          => $p['price'],
                            'price_unit'     => $p['price_unit'],
                            'discount_price' => !empty($p['discount_price']) ? $p['discount_price'] : null
                        ];
                    }
                }
                if (!empty($priceBatch)) $this->propertyPriceModel->insertBatch($priceBatch);
            }

            // C. Process Multiple Image Uploads
            if ($imageFiles = $this->request->getFiles()) {
                $isPrimary = 1; // The very first successful image becomes the thumbnail

                foreach ($imageFiles['images'] as $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        // Generate a secure random name (e.g., 1637283.jpg)
                        $newName = $img->getRandomName();
                        
                        // FCPATH points to your 'public' folder
                        $uploadPath = FCPATH . 'uploads/properties';
                        $img->move($uploadPath, $newName);

                        // Save image path to database
                        $this->propertyImageModel->insert([
                            'property_id' => $propertyId,
                            'image_path'  => 'uploads/properties/' . $newName,
                            'is_primary'  => $isPrimary
                        ]);

                        $isPrimary = 0; // Subsequent images are just gallery images
                    }
                }
            }

            // --- 4. COMMIT TRANSACTION ---
            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                // If the transaction failed, throw an error to catch block
                throw new DatabaseException('Transaction failed during property save.');
            }

            // Success!
            $message = ($status === 'active') ? 'Property published successfully!' : 'Property saved as draft.';
            return redirect()->to('/admin/properties')->with('success', $message);

        } catch (\Exception $e) {
            // If an error occurs, the transaction automatically rolls back.
            // We return the user to the form with their input and the error message.
            return redirect()->back()->withInput()->with('error', 'Error saving property: ' . $e->getMessage());
        }
    }


    // 4. Load the Edit Form
    public function edit($id)
    {
        $property = $this->propertyModel->find($id);
        if (!$property) throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();

        // Get associated amenities as a simple array of IDs [1, 4, 7]
        $propertyAmenities = $this->propertyAmenityModel->where('property_id', $id)->findAll();
        $selectedAmenities = array_column($propertyAmenities, 'amenity_id');

        $data = [
            'title'             => 'Edit Property',
            'property'          => $property,
            'amenities'         => $this->amenityModel->orderBy('name', 'ASC')->findAll(),
            'selectedAmenities' => $selectedAmenities,
            'existingImages'    => $this->propertyImageModel->where('property_id', $id)->findAll(),
            'propertyPrices'    => $this->propertyPriceModel->where('property_id', $id)->findAll()
        ];

        return view('admin/property_form', $data);
    }

    // 5. Process the Update
    public function update($id)
    {
        // Validation is similar to store, but 'images' are no longer required
        $rules = [
            'title'         => 'required|min_length[5]|max_length[255]',
            'prices.*.price'      => 'required|numeric',
            'prices.*.price_unit' => 'required',
            // (Add your other rules here just like in store method...)
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', $this->validator->listErrors());
        }

        $action = $this->request->getPost('action');
        
        $propertyData = [
            'title'            => $this->request->getPost('title'),
            'purpose'          => $this->request->getPost('purpose'),
            'property_type'    => $this->request->getPost('property_type'),
            'price'            => $this->request->getPost('price'),
            'price_unit'       => $this->request->getPost('price_unit'),
            'discount_price'   => $this->request->getPost('discount_price') ?: null,
            'address'          => $this->request->getPost('address'),
            'location'         => $this->request->getPost('location'),
            'city'             => $this->request->getPost('city'),
            'latitude'         => $this->request->getPost('latitude'),
            'longitude'        => $this->request->getPost('longitude'),
            'bedrooms'         => $this->request->getPost('bedrooms') ?: 0,
            'bathrooms'        => $this->request->getPost('bathrooms') ?: 0,
            'toilets'          => $this->request->getPost('toilets') ?: 0,
            'area_sqm'         => $this->request->getPost('area_sqm') ?: null,
            'description'      => $this->request->getPost('description'),
            'video_url'        => $this->request->getPost('video_url'),
            'virtual_tour_url' => $this->request->getPost('virtual_tour_url'),
            'meta_title'       => $this->request->getPost('meta_title'),
            'meta_description' => $this->request->getPost('meta_description'),
            'status'           => ($action === 'publish') ? 'active' : 'pending'
        ];

        $this->db->transStart();
        try {
            // Update Main Property
            $this->propertyModel->update($id, $propertyData);

            // Sync Amenities (Delete old, insert new)
            $this->propertyAmenityModel->where('property_id', $id)->delete();
            $selectedAmenities = $this->request->getPost('amenities');
            if (!empty($selectedAmenities)) {
                $amenityBatch = [];
                foreach ($selectedAmenities as $amenityId) {
                    $amenityBatch[] = ['property_id' => $id, 'amenity_id' => $amenityId];
                }
                $this->propertyAmenityModel->insertBatch($amenityBatch);
            }

            $this->propertyPriceModel->where('property_id', $id)->delete();
             $pricesPost = $this->request->getPost('prices');
            if (!empty($pricesPost) && is_array($pricesPost)) {
                $priceBatch = [];
                foreach ($pricesPost as $p) {
                    if (!empty($p['price'])) { // Only save if a price was entered
                        $priceBatch[] = [
                            'property_id'    => $id, // use $id if in update()
                            'price'          => $p['price'],
                            'price_unit'     => $p['price_unit'],
                            'discount_price' => !empty($p['discount_price']) ? $p['discount_price'] : null
                        ];
                    }
                }
                if (!empty($priceBatch)) $this->propertyPriceModel->insertBatch($priceBatch);
            }

            // Add New Images (if any)
            if ($imageFiles = $this->request->getFiles()) {
                // Check if property already has a primary image
                $hasPrimary = $this->propertyImageModel->where('property_id', $id)->where('is_primary', 1)->first();
                $isPrimary = $hasPrimary ? 0 : 1;

                foreach ($imageFiles['images'] as $img) {
                    if ($img->isValid() && !$img->hasMoved()) {
                        $newName = $img->getRandomName();
                        $img->move(FCPATH . 'uploads/properties', $newName);
                        
                        $this->propertyImageModel->insert([
                            'property_id' => $id,
                            'image_path'  => 'uploads/properties/' . $newName,
                            'is_primary'  => $isPrimary
                        ]);
                        $isPrimary = 0;
                    }
                }
            }

            $this->db->transComplete();
            if ($this->db->transStatus() === false) throw new \Exception('Transaction failed.');
            
            return redirect()->to('/admin/properties')->with('success', 'Property updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Error updating property: ' . $e->getMessage());
        }
    }

    // 6. Delete Full Property
    public function delete($id)
    {
        $property = $this->propertyModel->find($id);
        if (!$property) return redirect()->to('/admin/properties')->with('error', 'Property not found.');

        // 1. Get images and delete actual files from server
        $images = $this->propertyImageModel->where('property_id', $id)->findAll();
        foreach ($images as $img) {
            if (file_exists(FCPATH . $img->image_path)) {
                unlink(FCPATH . $img->image_path);
            }
        }

        // 2. Delete property (Database foreign keys ON DELETE CASCADE will auto-delete DB image & amenity records)
        $this->propertyModel->delete($id);

        return redirect()->to('/admin/properties')->with('success', 'Property deleted permanently.');
    }

    // 7. Delete Single Image from Gallery
    public function deleteImage($imageId)
    {
        $image = $this->propertyImageModel->find($imageId);
        if ($image) {
            if (file_exists(FCPATH . $image->image_path)) unlink(FCPATH . $image->image_path);
            $this->propertyImageModel->delete($imageId);
            return redirect()->back()->with('success', 'Image removed from gallery.');
        }
        return redirect()->back()->with('error', 'Image not found.');
    }
}