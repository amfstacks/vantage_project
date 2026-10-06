<?php

namespace App\Models;

use CodeIgniter\Model;

class PropertyRequestModel extends Model
{
    protected $table = 'property_requests';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $useAutoIncrement = true;
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $allowedFields = [
        'property_id',
        'request_reference',
        'full_name',
        'phone',
        'email',
        'request_type',
        'preferred_date',
        'message',
        'status',
        'source_url',
        'client_ip_hash',
    ];
}
