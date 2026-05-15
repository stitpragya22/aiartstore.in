<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderItemModel extends Model
{
    protected $table            = 'order_items';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['order_id', 'product_id', 'product_name', 'price', 'quantity', 'subtotal'];
    protected $useTimestamps    = false;
}
