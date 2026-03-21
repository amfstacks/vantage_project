<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyModel extends Model
{
    protected $table            = 'properties';
    protected $primaryKey       = 'id';
    protected $returnType       = 'object'; // Using objects is cleaner for views
    protected $useAutoIncrement = true;
    
    // DRY: Only these fields can be mass-assigned via forms
    // protected $allowedFields    = [
    //     'user_id', 'title', 'description', 'price', 'purpose', 
    //     'property_type', 'location', 'bedrooms', 'bathrooms', 'status'
    // ];

    protected $allowedFields    = [
        'user_id', 
        'title', 
        'slug',
        'description', 
        'price', 
        'price_unit',        // Added
        'discount_price',    // Added
        'purpose', 
        'property_type', 
        'address',           // Added
        'location', 
        'city',              // Added
        'latitude',          // Added
        'longitude',         // Added
        'bedrooms', 
        'bathrooms', 
        'toilets',           // Added
        'area_sqm',          // Added
        'video_url',         // Added
        'virtual_tour_url',  // Added
        'meta_title',        // Added
        'meta_description',  // Added
        'status'
    ];

    // CI4 built-in timestamp handling
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}