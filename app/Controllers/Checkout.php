<?php

namespace App\Controllers;

use App\Models\OrderModel;
use App\Models\OrderItemModel;
use App\Models\DownloadModel;
use App\Models\InvoiceModel;
use App\Models\ProductModel;
use App\Models\CouponModel;
use App\Models\PaymentEventModel;
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

        if (auth()->loggedIn()) {
            $cart = array_filter($cart, function($item) {
                return !isProductPurchased($item['id']);
            });
            if (empty($cart)) {
                // Clean up any leftover coupon data when the cart becomes empty
                session()->remove('cart');
                session()->remove('coupon_code');
                session()->remove('coupon_discount');
                return redirect()->to('/shop')->with('message', 'All items in your cart are already owned');
            }
        }

        // Calculate totals
        $total = array_sum(array_map(function($i) { return $i['price'] * $i['quantity']; }, $cart));
        $couponCode = session()->get('coupon_code');
        $couponDiscount = session()->get('coupon_discount', 0);
        $data['cart'] = $cart;
        $data['total'] = $total;
        $data['coupon_code'] = $couponCode;
        $data['coupon_discount'] = $couponDiscount;
        $data['grand_total'] = $total - $couponDiscount;
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

        foreach ($cart as $item) {
            if (isProductPurchased($item['id'])) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'You already own: ' . esc($item['title'])]);
            }
        }

        $couponCode = session()->get('coupon_code');
        $couponDiscount = session()->get('coupon_discount', 0);

        $productModel = model(ProductModel::class);
        $total = 0;
        foreach ($cart as $id => &$item) {
            $product = $productModel->find($id);
            if (!$product || $product['status'] !== 'active') {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Product unavailable: ' . esc($item['title'])]);
            }
            if (isProductPurchased($id)) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'You already own: ' . esc($item['title'])]);
            }
            $item['price'] = (float) $product['price'];
            $item['quantity'] = !empty($product['is_digital']) ? 1 : $item['quantity'];
            $total += $item['price'] * $item['quantity'];
        }
        unset($item);

        $finalTotal = max($total - $couponDiscount, 0);
        if ($finalTotal == 0) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid total']);
        }

        $orderNumber = 'ORD-' . strtoupper(uniqid());
        $orderModel = new OrderModel();
        $orderItemModel = new OrderItemModel();
        $razorpay = new Razorpay();

        try {
            $orderId = $orderModel->insert([
                'user_id'      => $user->id,
                'order_number' => $orderNumber,
                'subtotal'     => $total,
                'discount'     => $couponDiscount,
                'total'        => $finalTotal,
                'coupon_code'  => $couponCode,
                'status'       => 'pending',
                'customer_email' => $user->email,
            ]);

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

            $currency = $razorpay->getCurrency();
            $result = $razorpay->createOrder($finalTotal, $currency, $orderNumber);

            if (isset($result['error'])) {
                $orderModel->delete($orderId);
                log_message('error', 'Razorpay createOrder failed: ' . json_encode($result['error']));
                return $this->response->setJSON(['status' => 'error', 'message' => 'Payment gateway error. Please try again.']);
            }

            $orderModel->update($orderId, ['gateway_order_id' => $result['id']]);

            session()->remove('razorpay_order_id');
            session()->remove('pending_order_id');
            session()->remove('pending_order_total');
            session()->set('razorpay_order_id', $result['id']);
            session()->set('pending_order_id', $orderId);
            session()->set('pending_order_total', $finalTotal);

            return $this->response->setJSON([
                'status'   => 'success',
                'orderId'  => $result['id'],
                'amount'   => $finalTotal * 100,
                'keyId'    => $razorpay->getKeyId(),
                'currency' => $currency,
                'name'     => $user->username ?? $user->email,
                'email'    => $user->email,
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Checkout createOrder exception: ' . $e->getMessage());
            if (isset($orderId)) $orderModel->delete($orderId);
            return $this->response->setJSON(['status' => 'error', 'message' => 'An error occurred. Please try again.']);
        }
    }

    public function verify()
    {
        try {
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

            // Remove gateway order ID mismatch check (gateway_order_id column no longer used)
            // if (!empty($order['gateway_order_id']) && $order['gateway_order_id'] !== $razorpayOrderId) {
            //     return redirect()->to('/checkout')->with('error', 'Payment order mismatch');
            // }


            // Amount validation: verify paid amount matches order total
            $payment = $razorpay->fetchPayment($razorpayPaymentId);
            if (isset($payment['error'])) {
                log_message('error', 'Razorpay fetchPayment failed: ' . json_encode($payment['error']));
                return redirect()->to('/checkout')->with('error', 'Payment verification failed. Contact support.');
            }

            $paidAmount = ($payment['amount'] ?? 0) / 100;
            $fulfilled = $this->fulfillPaidOrder($order, $razorpayPaymentId, $paidAmount, 'browser');

            if (!$fulfilled) {
                return redirect()->to('/checkout')->with('error', 'Payment amount mismatch. Please contact support.');
            }

            session()->remove('cart');
            session()->remove('razorpay_order_id');
            session()->remove('pending_order_id');
            session()->remove('pending_order_total');
            session()->remove('coupon_code');
            session()->remove('coupon_discount');

            return redirect()->to('/orders/' . $order['order_number'])->with('message', 'Payment successful! Check your email for download links.');

        } catch (\Exception $e) {
            log_message('error', 'Checkout verify exception: ' . $e->getMessage());
            return redirect()->to('/checkout')->with('error', 'An error occurred during payment verification. Please contact support.');
        }
    }

    public function webhook()
    {
        $payload = (string) $this->request->getBody();
        $signature = (string) $this->request->getHeaderLine('X-Razorpay-Signature');
        $razorpay = new Razorpay();

        if (!$razorpay->verifyWebhook($payload, $signature)) {
            log_message('error', 'Invalid Razorpay webhook signature');
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error']);
        }

        $event = json_decode($payload, true);
        if (!is_array($event)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'invalid_json']);
        }

        $eventName = $event['event'] ?? 'unknown';
        $payment = $event['payload']['payment']['entity'] ?? [];
        $gatewayOrderId = $payment['order_id'] ?? null;
        $gatewayPaymentId = $payment['id'] ?? null;

        $eventModel = model(PaymentEventModel::class);
        $eventId = $eventModel->insert([
            'event'              => $eventName,
            'gateway_order_id'   => $gatewayOrderId,
            'gateway_payment_id' => $gatewayPaymentId,
            'payload'            => $payload,
            'status'             => 'received',
        ]);

        if ($eventName === 'payment.failed') {
            if ($gatewayOrderId) {
                model(OrderModel::class)
                    ->where('gateway_order_id', $gatewayOrderId)
                    ->set([
                        'payment_status' => 'failed',
                        'status'         => 'cancelled',
                        'notes'          => 'Razorpay payment.failed webhook received',
                    ])
                    ->update();
            }

            $eventModel->update($eventId, ['status' => 'processed']);
            return $this->response->setJSON(['status' => 'ok']);
        }

        if ($eventName !== 'payment.captured') {
            $eventModel->update($eventId, ['status' => 'ignored', 'message' => 'Event not handled']);
            return $this->response->setJSON(['status' => 'ignored']);
        }

        if (!$gatewayOrderId || !$gatewayPaymentId) {
            $eventModel->update($eventId, ['status' => 'failed', 'message' => 'Missing payment or order id']);
            return $this->response->setStatusCode(422)->setJSON(['status' => 'missing_ids']);
        }

        $order = model(OrderModel::class)->where('gateway_order_id', $gatewayOrderId)->first();
        if (!$order) {
            $eventModel->update($eventId, ['status' => 'failed', 'message' => 'Order not found']);
            return $this->response->setStatusCode(404)->setJSON(['status' => 'order_not_found']);
        }

        $paidAmount = ((float) ($payment['amount'] ?? 0)) / 100;
        $fulfilled = $this->fulfillPaidOrder($order, $gatewayPaymentId, $paidAmount, 'webhook');

        $eventModel->update($eventId, [
            'status'  => $fulfilled ? 'processed' : 'failed',
            'message' => $fulfilled ? null : 'Amount mismatch or fulfillment failed',
        ]);

        return $this->response->setJSON(['status' => $fulfilled ? 'ok' : 'failed']);
    }

    public function validateCoupon()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['valid' => false, 'message' => 'Invalid request']);
        }

        $code = $this->request->getPost('code');
        if (!$code) {
            session()->remove('coupon_code');
            session()->remove('coupon_discount');
            return $this->response->setJSON(['valid' => false, 'message' => 'Coupon removed']);
        }

        $cart = session()->get('cart') ?? [];
        $total = array_sum(array_map(function($i) { return $i['price'] * $i['quantity']; }, $cart));

        $couponModel = new CouponModel();
        $result = $couponModel->validateCoupon($code, $total);

        if ($result['valid']) {
            session()->set('coupon_code', $code);
            session()->set('coupon_discount', $result['discount']);
            return $this->response->setJSON([
                'valid' => true,
                'discount' => $result['discount'],
                'formatted_discount' => formatPrice($result['discount']),
                'grand_total' => $total - $result['discount'],
                'formatted_grand_total' => formatPrice($total - $result['discount']),
                'message' => 'Coupon applied! You saved ' . formatPrice($result['discount']),
            ]);
        }

        session()->remove('coupon_code');
        session()->remove('coupon_discount');
        return $this->response->setJSON(['valid' => false, 'message' => $result['message']]);
    }

    private function fulfillPaidOrder(array $order, string $paymentId, float $paidAmount, string $source): bool
    {
        $expectedAmount = (float) $order['total'];
        $orderId = (int) $order['id'];
        $orderModel = model(OrderModel::class);

        if (abs($paidAmount - $expectedAmount) > 1) {
            log_message('error', "Amount mismatch via {$source}: paid={$paidAmount}, expected={$expectedAmount}, order={$order['order_number']}");
            $orderModel->update($orderId, [
                'payment_status' => 'failed',
                'status'         => 'cancelled',
                'notes'          => 'Amount mismatch: paid ' . $paidAmount . ' vs expected ' . $expectedAmount,
            ]);

            return false;
        }

        if ($order['payment_status'] === 'completed' && $order['status'] === 'completed') {
            return true;
        }

        $invoiceNo = $order['invoice_no'] ?: 'INV-' . strtoupper(uniqid());
        $orderModel->update($orderId, [
            'payment_method'      => 'razorpay',
            'payment_id'          => $paymentId,
            'payment_status'      => 'completed',
            'payment_verified_at' => date('Y-m-d H:i:s'),
            'status'              => 'completed',
            'invoice_no'          => $invoiceNo,
        ]);

        $invoiceModel = model(InvoiceModel::class);
        if (!$invoiceModel->where('order_id', $orderId)->first()) {
            $invoiceModel->insert([
                'order_id'   => $orderId,
                'invoice_no' => $invoiceNo,
                'total'      => $order['total'],
                'status'     => 'paid',
            ]);
        }

        $items = model(OrderItemModel::class)->where('order_id', $orderId)->findAll();
        $downloadModel = model(DownloadModel::class);
        foreach ($items as $item) {
            $existing = $downloadModel->getDownload((int) $order['user_id'], (int) $item['product_id'], $orderId);
            if (!$existing) {
                $downloadModel->createAccess((int) $order['user_id'], (int) $item['product_id'], $orderId);
            }
        }

        if (!empty($order['coupon_code'])) {
            $couponModel = model(CouponModel::class);
            $coupon = $couponModel->where('code', $order['coupon_code'])->first();
            if ($coupon) {
                $couponModel->incrementUsage($coupon['id']);
            }
        }

        $freshOrder = $orderModel->find($orderId);
        if (empty($freshOrder['fulfillment_sent_at'])) {
            try {
                sendOrderConfirmation($freshOrder, $items);
                sendDownloadLinks($freshOrder, $items);
                sendAdminNotification($freshOrder);
                $orderModel->update($orderId, ['fulfillment_sent_at' => date('Y-m-d H:i:s')]);
            } catch (\Exception $e) {
                log_message('error', 'Email sending failed after payment fulfillment: ' . $e->getMessage());
            }
        }

        return true;
    }
}
