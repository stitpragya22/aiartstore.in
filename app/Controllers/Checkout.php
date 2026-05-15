<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\DownloadModel;
use App\Models\InvoiceModel;
use App\Models\ProductModel;
use App\Libraries\Razorpay;

class Checkout extends BaseController
{
    public function index()
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to checkout');
        }

        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to('/cart')->with('error', 'Your cart is empty');
        }

        $data['cart'] = $cart;
        $data['total'] = array_sum(array_map(function($i) { return $i['price'] * $i['quantity']; }, $cart));
        $data['title'] = 'Checkout';

        return view('checkout/index', $data);
    }

    public function createOrder()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Please login']);
        }

        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cart is empty']);
        }

        $user = auth()->user();
        $total = array_sum(array_map(function($i) { return $i['price'] * $i['quantity']; }, $cart));

        $orderNumber = 'ORD-' . strtoupper(uniqid());
        $orderModel = new OrderModel();

        $orderId = $orderModel->insert([
            'user_id'      => $user->id,
            'order_number' => $orderNumber,
            'subtotal'     => $total,
            'total'        => $total,
            'status'       => 'pending',
        ]);

        $orderItemModel = new OrderItemModel();
        foreach ($cart as $item) {
            $orderItemModel->insert([
                'order_id'     => $orderId,
                'product_id'   => $item['id'],
                'product_name' => $item['title'],
                'price'        => $item['price'],
                'quantity'     => $item['quantity'],
                'subtotal'     => $item['price'] * $item['quantity'],
            ]);
        }

        $razorpay = new Razorpay();
        $receipt = $orderNumber;
        $result = $razorpay->createOrder($total, 'INR', $receipt);

        if (isset($result['error'])) {
            $orderModel->delete($orderId);
            return $this->response->setJSON(['status' => 'error', 'message' => 'Payment gateway error. Please try again.']);
        }

        session()->set('razorpay_order_id', $result['id']);
        session()->set('pending_order_id', $orderId);

        return $this->response->setJSON([
            'status'  => 'success',
            'orderId' => $result['id'],
            'amount'  => $total * 100,
            'keyId'   => config('Razorpay')->keyId,
            'name'    => $user->username ?? $user->email,
            'email'   => $user->email,
        ]);
    }

    public function verify()
    {
        $razorpayOrderId = $this->request->getPost('razorpay_order_id');
        $razorpayPaymentId = $this->request->getPost('razorpay_payment_id');
        $razorpaySignature = $this->request->getPost('razorpay_signature');

        $razorpay = new Razorpay();
        $verified = $razorpay->verifyPayment($razorpayOrderId, $razorpayPaymentId, $razorpaySignature);

        if (!$verified) {
            return redirect()->to('/checkout')->with('error', 'Payment verification failed');
        }

        $pendingOrderId = session()->get('pending_order_id');
        $orderModel = new OrderModel();
        $order = $orderModel->find($pendingOrderId);

        if (!$order) {
            return redirect()->to('/checkout')->with('error', 'Order not found');
        }

        $invoiceNo = 'INV-' . strtoupper(uniqid());

        $orderModel->update($pendingOrderId, [
            'payment_method'  => 'razorpay',
            'payment_id'      => $razorpayPaymentId,
            'payment_status'  => 'completed',
            'status'          => 'completed',
            'invoice_no'      => $invoiceNo,
        ]);

        $invoiceModel = new InvoiceModel();
        $invoiceModel->insert([
            'order_id'   => $pendingOrderId,
            'invoice_no' => $invoiceNo,
            'total'      => $order['total'],
            'status'     => 'paid',
        ]);

        $user = auth()->user();
        $items = model(OrderItemModel::class)->where('order_id', $pendingOrderId)->findAll();

        $downloadModel = new DownloadModel();
        foreach ($items as $item) {
            $downloadModel->insert([
                'user_id'    => $user->id,
                'product_id' => $item['product_id'],
                'order_id'   => $pendingOrderId,
            ]);
        }

        session()->remove('cart');
        session()->remove('razorpay_order_id');
        session()->remove('pending_order_id');

        return redirect()->to('/orders/' . $order['order_number'])->with('message', 'Payment successful! You can now download your purchases.');
    }
}
