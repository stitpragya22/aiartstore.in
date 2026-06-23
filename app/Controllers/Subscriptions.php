<?php

namespace App\Controllers;

use App\Models\SubscriptionPlanModel;
use App\Models\UserSubscriptionModel;

class Subscriptions extends BaseController
{
    private $planModel;
    private $subscriptionModel;

    public function __construct()
    {
        $this->planModel = new SubscriptionPlanModel();
        $this->subscriptionModel = new UserSubscriptionModel();
    }

    public function plans()
    {
        $data['plans'] = $this->planModel->getActive();
        $data['title'] = 'Subscription Plans - AI Art Store';
        $data['meta_description'] = 'Choose from our subscription plans to access premium AI prompts and resources. Unlock your creative potential with AI Art Store.';
        return view('subscriptions/plans', $data);
    }

    public function my()
    {
        $userId = auth()->id();
        $data['subscriptions'] = $this->subscriptionModel->getAllForUser($userId);
        $data['highestLevel'] = $this->subscriptionModel->getHighestLevelForUser($userId);
        $data['title'] = 'My Subscription - AI Art Store';
        $data['meta_description'] = 'View your AI Art Store subscription details, access level, and validity.';
        return view('subscriptions/my', $data);
    }

    public function purchase($planId = null)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Please log in to purchase a subscription');
        }

        $plan = $this->planModel->find($planId);
        if (!$plan || $plan['status'] != 'active') {
            return redirect()->to('/subscriptions/plans')->with('error', 'Plan not found');
        }

        $userId = auth()->id();

        // Check if user already has an active subscription with this or higher level
        $currentLevel = $this->subscriptionModel->getHighestLevelForUser($userId);
        if ($currentLevel >= $plan['level']) {
            return redirect()->to('/subscriptions/my')->with('info', 'You already have access to this plan level');
        }

        if ($this->request->is('post')) {
            // Create Razorpay order
            $razorpay = new \App\Libraries\Razorpay();
            $receipt = 'SUB-' . time() . '-' . $userId;

            $order = $razorpay->createOrder($plan['price'], $razorpay->getCurrency(), $receipt);

            if (!$order || !isset($order['id'])) {
                return redirect()->back()->with('error', 'Failed to create payment order. Please try again.');
            }

            // Save order in orders table
            $orderModel = new \App\Models\OrderModel();
            $orderId = $orderModel->insert([
                'user_id'          => $userId,
                'customer_email'   => auth()->user()->email,
                'order_number'     => $receipt,
                'subtotal'         => $plan['price'],
                'total'            => $plan['price'],
                'payment_method'   => 'razorpay',
                'gateway_order_id' => $order['id'],
                'payment_status'   => 'pending',
                'status'           => 'pending',
            ]);

            return $this->response->setJSON([
                'order_id'       => $order['id'],
                'amount'         => $order['amount'],
                'key_id'         => $razorpay->getKeyId(),
                'currency'       => $order['currency'],
                'receipt'        => $receipt,
                'db_order_id'    => $orderId,
                'plan_name'      => $plan['name'],
                'plan_id'        => $plan['id'],
            ]);
        }

        $data['plan'] = $plan;
        $data['title'] = 'Purchase ' . $plan['name'] . ' - AI Art Store';
        $data['meta_description'] = 'Purchase the ' . $plan['name'] . ' subscription plan and unlock premium AI prompts.';
        return view('subscriptions/purchase', $data);
    }

    public function verify()
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/subscriptions/plans');
        }

        $razorpay = new \App\Libraries\Razorpay();

        $razorpayOrderId = $this->request->getPost('razorpay_order_id');
        $razorpayPaymentId = $this->request->getPost('razorpay_payment_id');
        $razorpaySignature = $this->request->getPost('razorpay_signature');
        $planId = $this->request->getPost('plan_id');
        $dbOrderId = $this->request->getPost('db_order_id');

        // Verify signature
        if (!$razorpay->verifyPayment($razorpayOrderId, $razorpayPaymentId, $razorpaySignature)) {
            return redirect()->to('/subscriptions/plans')->with('error', 'Payment verification failed');
        }

        $plan = $this->planModel->find($planId);
        if (!$plan) {
            return redirect()->to('/subscriptions/plans')->with('error', 'Plan not found');
        }

        $userId = auth()->id();

        // Update order status
        $orderModel = new \App\Models\OrderModel();
        $orderModel->update($dbOrderId, [
            'payment_id'          => $razorpayPaymentId,
            'payment_status'      => 'completed',
            'payment_verified_at' => date('Y-m-d H:i:s'),
            'status'              => 'completed',
        ]);

        // Create subscription
        $startDate = date('Y-m-d H:i:s');
        $endDate = $plan['validity_days'] > 0
            ? date('Y-m-d H:i:s', strtotime($startDate . ' + ' . $plan['validity_days'] . ' days'))
            : '9999-12-31 23:59:59';

        $this->subscriptionModel->save([
            'user_id'    => $userId,
            'plan_id'    => $plan['id'],
            'order_id'   => $dbOrderId,
            'start_date' => $startDate,
            'end_date'   => $endDate,
            'status'     => 'active',
        ]);

        return redirect()->to('/subscriptions/my')->with('message', 'Subscription activated successfully! Welcome to ' . $plan['name'] . '!');
    }
}
