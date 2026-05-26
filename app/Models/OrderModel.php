<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_id', 'customer_email', 'coupon_code', 'order_number', 'subtotal', 'tax', 'discount', 'total',
        'payment_method', 'gateway_order_id', 'payment_id', 'payment_status', 'payment_verified_at',
        'fulfillment_sent_at', 'status', 'invoice_no', 'notes'
    ];
    protected $useTimestamps    = true;
    protected $validationRules  = [
        'order_number' => 'required|max_length[50]',
        'total'        => 'required|numeric|greater_than_equal_to[0]',
        'user_id'      => 'required|is_natural_no_zero',
    ];

    public function getUserOrders($userId)
    {
        return $this->where('user_id', $userId)->orderBy('id', 'DESC')->findAll();
    }

    public function getWithItems($id)
    {
        $order = $this->find($id);
        if ($order) {
            $order['items'] = model('OrderItemModel')->where('order_id', $id)->findAll();
        }
        return $order;
    }

    public function getByOrderNumber($orderNumber)
    {
        return $this->where('order_number', $orderNumber)->first();
    }
}
