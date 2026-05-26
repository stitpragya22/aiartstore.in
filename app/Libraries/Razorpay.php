<?php

namespace App\Libraries;

class Razorpay
{
    private $keyId;
    private $keySecret;
    private $apiUrl;
    private $currency;
    private $mode;

    public function __construct()
    {
        $this->apiUrl = 'https://api.razorpay.com/v1/';
        $this->currency = 'INR';
        $this->mode = 'test';
        $this->loadFromDatabase();
    }

    public function getKeyId()
    {
        return $this->keyId;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getMode()
    {
        return $this->mode;
    }

    public function getWebhookSecret(): string
    {
        return (string) (env('RAZORPAY_WEBHOOK_SECRET')
            ?: env('razorpay.webhookSecret')
            ?: $this->getSetting('razorpay_webhook_secret', ''));
    }

    private function loadFromDatabase()
    {
        $db = db_connect();
        $rows = $db->table('settings')
            ->where('class', 'App\Libraries\Razorpay')
            ->get()
            ->getResultArray();

        $settings = [];
        foreach ($rows as $row) {
            $settings[$row['key']] = $row['value'];
        }

        $this->mode = $settings['razorpay_mode'] ?? 'test';
        $this->currency = $settings['razorpay_currency'] ?? 'INR';

        if ($this->mode === 'live') {
            $this->keyId = $settings['razorpay_live_key_id'] ?? '';
            $this->keySecret = $settings['razorpay_live_key_secret'] ?? '';
        } else {
            $this->keyId = $settings['razorpay_test_key_id'] ?? '';
            $this->keySecret = $settings['razorpay_test_key_secret'] ?? '';
        }
    }

    private function getSetting(string $key, string $default = ''): string
    {
        $db = db_connect();
        $row = $db->table('settings')
            ->where('class', 'App\Libraries\Razorpay')
            ->where('key', $key)
            ->get()
            ->getRowArray();

        return $row['value'] ?? $default;
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

    public function verifyWebhook(string $payload, string $signature): bool
    {
        $secret = $this->getWebhookSecret();
        if ($secret === '' || $signature === '') {
            return false;
        }

        $expectedSign = hash_hmac('sha256', $payload, $secret);

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

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($response === false) {
            return ['error' => $error ?: 'Razorpay request failed'];
        }

        if ($httpCode >= 200 && $httpCode < 300) {
            return json_decode($response, true);
        }

        return ['error' => json_decode($response, true) ?? $response];
    }
}
