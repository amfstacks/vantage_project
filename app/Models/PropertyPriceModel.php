<?php
namespace App\Models;
use CodeIgniter\Model;

class PropertyPriceModel extends Model
{
    protected $table         = 'property_prices';
    protected $allowedFields = ['property_id', 'price', 'price_unit', 'discount_price'];
    public $timestamps       = false;
    protected $returnType    = 'object';
}