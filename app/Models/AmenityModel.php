<?php

namespace App\Models;

use CodeIgniter\Model;

class AmenityModel extends Model
{
    protected $table         = 'amenities';
    protected $primaryKey    = 'id';
    protected $returnType    = 'object';
    protected $allowedFields = ['name', 'icon'];
    public $timestamps       = false;
}