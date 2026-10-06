<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyPurposeModel extends Model
{
    protected $table = 'property_purposes';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'is_active',
        'sort_order',
    ];
}
