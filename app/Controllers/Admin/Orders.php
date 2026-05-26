<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\DownloadModel;

class Orders extends BaseController
{
    public function index()
    {
        $orderModel = new OrderModel();
        $data['orders'] = $orderModel->select('orders.*, users.username, auth_identities.secret as identity_email')
            ->join('users', 'users.id = orders.user_id', 'left')
            ->join('auth_identities', 'auth_identities.user_id = users.id AND auth_identities.type = "email_password"', 'left')
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
        $downloadModel = model(DownloadModel::class);
        $order['downloads'] = $downloadModel->select('downloads.*, products.title as product_title')
            ->join('products', 'products.id = downloads.product_id')
            ->where('downloads.order_id', $id)
            ->findAll();

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

    public function reissueDownload($id = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/orders');
        }

        $downloadModel = model(DownloadModel::class);
        $download = $downloadModel->find($id);

        if (!$download) {
            return redirect()->back()->with('error', 'Download access not found');
        }

        $downloadModel->reissueAccess((int) $id);

        return redirect()->to('/admin/orders/' . $download['order_id'])->with('message', 'Download link reissued');
    }

    public function revokeDownload($id = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/orders');
        }

        $downloadModel = model(DownloadModel::class);
        $download = $downloadModel->find($id);

        if (!$download) {
            return redirect()->back()->with('error', 'Download access not found');
        }

        $downloadModel->revokeAccess((int) $id);

        return redirect()->to('/admin/orders/' . $download['order_id'])->with('message', 'Download access revoked');
    }
}
