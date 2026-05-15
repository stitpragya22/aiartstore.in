<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Razorpay extends BaseConfig
{
    public string $keyId = 'rzp_test_XXXXXXXXXXXXXXXX';

    public string $keySecret = 'YOUR_SECRET_HERE';

    public string $apiUrl = 'https://api.razorpay.com/v1/';

    public string $currency = 'INR';
}
