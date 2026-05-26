<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class GoogleAuth extends BaseConfig
{
    public string $clientId = '';

    public string $clientSecret = '';

    public string $redirectUri = '';

    public array $scopes = [
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile',
    ];

    public function __construct()
    {
        parent::__construct();

        $this->clientId     = $this->clientId ?: (string) env('GOOGLE_CLIENT_ID', '');
        $this->clientSecret = $this->clientSecret ?: (string) env('GOOGLE_CLIENT_SECRET', '');

        $this->redirectUri = $this->redirectUri ?: (string) env('GOOGLE_REDIRECT_URI', '');
    }
}
