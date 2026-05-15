<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class GoogleAuth extends BaseConfig
{
    public string $clientId = '';

    public string $clientSecret = '';

    public string $redirectUri = 'http://localhost/aiartstore.in/google-auth/callback';

    public array $scopes = [
        'https://www.googleapis.com/auth/userinfo.email',
        'https://www.googleapis.com/auth/userinfo.profile',
    ];
}
