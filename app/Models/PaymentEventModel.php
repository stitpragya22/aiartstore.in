<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentEventModel extends Model
{
    protected $table         = 'payment_events';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'provider', 'event', 'gateway_order_id', 'gateway_payment_id',
        'payload', 'status', 'message',
    ];
    protected $useTimestamps = true;
}
