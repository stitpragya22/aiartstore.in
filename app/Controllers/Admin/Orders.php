<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;

class Orders extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $data['orders'] = $orderModel->select('orders.*, users.username, users.email')
            ->join('users', 'users.id = orders.user_id', 'left')
            ->orderBy('orders.id', 'DESC')
            ->findAll();
        $data['title'] = 'Manage Orders';
        return view('admin/orders/index', $data);
    }

    public function detail($id = null)
    {
        $orderModel = new OrderModel();
        $order = $orderModel->find($id);
        if (!$order) {
            return redirect()->to('/admin/orders')->with('error', 'Order not found');
        }

        $order['items'] = model(OrderItemModel::class)->where('order_id', $id)->findAll();
        $order['user'] = auth()->getProvider()->findById($order['user_id']);
        $order['invoice'] = model(\App\Models\InvoiceModel::class)->where('order_id', $id)->first();

        $data['order'] = $order;
        $data['title'] = 'Order #' . $order['order_number'];
        return view('admin/orders/detail', $data);
    }

    public function updateStatus($id = null)
    {
        if ($this->request->is('post')) {
            $orderModel = new OrderModel();
            $orderModel->update($id, [
                'status' => $this->request->getPost('status'),
            ]);
            return redirect()->to('/admin/orders/' . $id)->with('message', 'Order status updated');
        }
        return redirect()->to('/admin/orders');
    }
}
