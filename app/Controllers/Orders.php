<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\InvoiceModel;

class Orders extends BaseController
{
    public function index()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        $user = auth()->user();
        $orderModel = new OrderModel();
        $data['orders'] = $orderModel->getUserOrders($user->id);
        $data['title'] = 'My Orders';

        return view('orders/index', $data);
    }

    public function detail($orderNumber = null)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login');
        }

        if (!$orderNumber) {
            return redirect()->to('/orders');
        }

        $orderModel = new OrderModel();
        $order = $orderModel->getByOrderNumber($orderNumber);

        if (!$order || $order['user_id'] !== auth()->user()->id) {
            return redirect()->to('/orders')->with('error', 'Order not found');
        }

        $order['items'] = model(OrderItemModel::class)->where('order_id', $order['id'])->findAll();
        $order['invoice'] = model(InvoiceModel::class)->where('order_id', $order['id'])->first();

        $data['order'] = $order;
        $data['title'] = 'Order #' . $orderNumber;

        return view('orders/detail', $data);
    }
}
