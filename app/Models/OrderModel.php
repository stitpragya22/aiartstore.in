<?php

namespace App\Models;

use CodeIgniter\Model;

class OrderModel extends Model
{
    protected $table            = 'orders';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'user_id', 'order_number', 'subtotal', 'tax', 'discount', 'total',
        'payment_method', 'payment_id', 'payment_status', 'status', 'invoice_no', 'notes'
    ];
    protected $useTimestamps    = true;

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
