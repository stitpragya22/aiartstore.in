<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomRequestMessageModel extends Model
{
    protected $table            = 'custom_request_messages';
    protected $primaryKey       = 'id';
    protected $useTimestamps    = false;
    protected $allowedFields    = ['request_id', 'sender', 'message', 'file', 'created_at'];
}
