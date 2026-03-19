<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyAmenityModel extends Model
{
    protected $table         = 'property_amenities';
    protected $allowedFields = ['property_id', 'amenity_id'];
    public $timestamps       = false;
}