<?php

namespace App\Libraries;

class Razorpay
{
    private $keyId;
    private $keySecret;
    private $apiUrl;

    public function __construct()
    {
        $config = config('Razorpay');
        $this->keyId = $config->keyId;
        $this->keySecret = $config->keySecret;
        $this->apiUrl = $config->apiUrl;
    }

    public function createOrder($amount, $currency = 'INR', $receipt = null)
    {
        $receipt = $receipt ?? 'rcpt_' . uniqid();
        $data = [
            'amount'   => $amount * 100,
            'currency' => $currency,
            'receipt'  => $receipt,
        ];

        return $this->apiRequest('orders', $data);
    }

    public function verifyPayment($orderId, $paymentId, $signature)
    {
        $expectedSign = hash_hmac('sha256', $orderId . '|' . $paymentId, $this->keySecret);
        return hash_equals($expectedSign, $signature);
    }

    public function fetchPayment($paymentId)
    {
        return $this->apiRequest("payments/{$paymentId}", [], 'GET');
    }

    private function apiRequest($endpoint, $data = [], $method = 'POST')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->apiUrl . $endpoint);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERPWD, $this->keyId . ':' . $this->keySecret);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        }

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true);
        }

        return ['error' => json_decode($response, true) ?? $response];
    }
}
