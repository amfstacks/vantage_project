<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyImageModel extends Model
{
    protected $table         = 'property_images';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $allowedFields = ['property_id', 'image_path', 'is_primary'];
    
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = ''; // No updated field needed for images
}