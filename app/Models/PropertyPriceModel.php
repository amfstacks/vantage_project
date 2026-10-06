<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyPriceModel extends Model
{
    protected $table = 'property_prices';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'property_id',
        'purpose_id',
        'price',
        'price_unit',
        'discount_price',
        'discount_percentage',
    ];
    public $timestamps = false;
    protected $returnType = 'object';
}
