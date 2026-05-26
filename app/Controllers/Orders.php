<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\InvoiceModel;
use App\Models\DownloadModel;

class Orders extends BaseController
{
    public function index()
    {
        if (!auth()->loggedIn()) {
            return $this->redirectToLogin();
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
            return $this->redirectToLogin();
        }

        if (!$orderNumber) {
            return redirect()->to('/orders');
        }

        $orderModel = new OrderModel();
        $order = $orderModel->getByOrderNumber($orderNumber);

        if (!$order || (int)$order['user_id'] !== (int)auth()->user()->id) {
            return redirect()->to('/orders')->with('error', 'Order not found');
        }

        $order['items'] = model(OrderItemModel::class)->where('order_id', $order['id'])->findAll();
        $order['invoice'] = model(InvoiceModel::class)->where('order_id', $order['id'])->first();

        $downloadModel = model(DownloadModel::class);
        $downloads = $downloadModel->where('order_id', $order['id'])
            ->where('user_id', auth()->user()->id)
            ->findAll();

        $order['downloads'] = [];
        foreach ($downloads as $download) {
            $download = $downloadModel->ensureToken($download);
            $download['is_available'] = $downloadModel->isAvailable($download);
            $order['downloads'][$download['product_id']] = $download;
        }

        $data['order'] = $order;
        $data['title'] = 'Order #' . $orderNumber;

        return view('orders/detail', $data);
    }

    private function redirectToLogin()
    {
        session()->setTempdata('beforeLoginUrl', current_url(), 300);

        return redirect()->to('/login')->withHeaders([
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
        ]);
    }
}
