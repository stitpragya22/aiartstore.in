<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\Shield\Models\UserModel;
use CodeIgniter\Shield\Models\UserIdentityModel;
use CodeIgniter\Shield\Entities\User;
use Config\GoogleAuth as GoogleAuthConfig;

class GoogleAuth extends BaseController
{
    public function login()
    {
        $config = config(GoogleAuthConfig::class);

        if (! $this->isConfigured($config)) {
            return redirect()->to('/login')->with('error', 'Google login is not configured yet.');
        }

        $client = $this->createClient($config);
        $client->setScopes($config->scopes);
        $client->setAccessType('offline');
        $client->setPrompt('select_account');

        $authUrl = $client->createAuthUrl();

        return redirect()->to($authUrl);
    }

    public function callback()
    {
        $config = config(GoogleAuthConfig::class);

        if (! $this->isConfigured($config)) {
            return redirect()->to('/login')->with('error', 'Google login is not configured yet.');
        }

        $code = $this->request->getGet('code');

        if (!$code) {
            return redirect()->to('/login')->with('error', 'Google authentication cancelled or failed.');
        }

        try {
            $client = $this->createClient($config);

            $token = $client->fetchAccessTokenWithAuthCode($code);

            if (isset($token['error'])) {
                return redirect()->to('/login')->with('error', 'Failed to authenticate with Google.');
            }

            $client->setAccessToken($token);

            $oauthService = new \Google\Service\Oauth2($client);
            $googleUser = $oauthService->userinfo->get();

            $googleEmail = $googleUser->getEmail();
            $googleName = $googleUser->getName();
            $googleId = $googleUser->getId();
            $googleAvatar = $googleUser->getPicture();

            if (!$googleEmail) {
                return redirect()->to('/login')->with('error', 'Could not retrieve email from Google account.');
            }

            $userModel = model(UserModel::class);
            $identityModel = model(UserIdentityModel::class);

            $identity = $identityModel->getIdentityBySecret('google_oauth', $googleEmail);

            if ($identity) {
                $user = $userModel->find($identity->user_id);

                if (!$user || !$user->active) {
                    return redirect()->to('/login')->with('error', 'Account is disabled or not found.');
                }

                $identityModel->update($identity->id, [
                    'secret2' => $googleId,
                    'extra' => json_encode(['name' => $googleName, 'avatar' => $googleAvatar]),
                    'last_used_at' => date('Y-m-d H:i:s'),
                ]);
            } else {
                $existingIdentity = $identityModel->getIdentityBySecret('email_password', $googleEmail);
                if ($existingIdentity) {
                    $existingIdentityModel = $identityModel->find($existingIdentity->id);
                    $identityModel->insert([
                        'user_id' => $existingIdentity->user_id,
                        'type' => 'google_oauth',
                        'secret' => $googleEmail,
                        'secret2' => $googleId,
                        'extra' => json_encode(['name' => $googleName, 'avatar' => $googleAvatar]),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    $user = $userModel->find($existingIdentity->user_id);
                } else {
                    $username = $this->generateUniqueUsername($googleName);

                    $user = new User([
                        'username' => $username,
                        'email' => $googleEmail,
                        'active' => 1,
                    ]);

                    $userModel->save($user);
                    $userId = $userModel->getInsertID();

                    $identityModel->insert([
                        'user_id' => $userId,
                        'type' => 'google_oauth',
                        'secret' => $googleEmail,
                        'secret2' => $googleId,
                        'extra' => json_encode(['name' => $googleName, 'avatar' => $googleAvatar]),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);

                    $user = $userModel->find($userId);
                }
            }

            $authenticator = auth('session')->getAuthenticator();
            $authenticator->login($user);

            return redirect()->to(config('Auth')->loginRedirect())->with('message', 'Logged in successfully with Google.');
        } catch (\Exception $e) {
            log_message('error', 'Google Auth error: ' . $e->getMessage());
            return redirect()->to('/login')->with('error', 'An error occurred during Google authentication. Please try again.');
        }
    }

    private function generateUniqueUsername(string $name): string
    {
        $base = preg_replace('/[^a-zA-Z0-9]/', '', $name);
        $base = substr($base, 0, 25);
        if (empty($base)) {
            $base = 'user';
        }

        $username = $base;
        $userModel = model(UserModel::class);
        $counter = 1;

        while ($userModel->where('username', $username)->first()) {
            $username = $base . $counter;
            $counter++;
        }

        return $username;
    }

    private function isConfigured(GoogleAuthConfig $config): bool
    {
        return $config->clientId !== ''
            && $config->clientSecret !== '';
    }

    private function createClient(GoogleAuthConfig $config): \Google\Client
    {
        $client = new \Google\Client();
        $client->setClientId($config->clientId);
        $client->setClientSecret($config->clientSecret);
        $client->setRedirectUri($this->getRedirectUri($config));

        return $client;
    }

    private function getRedirectUri(GoogleAuthConfig $config): string
    {
        return $config->redirectUri !== ''
            ? $config->redirectUri
            : site_url('auth/google/callback');
    }
}
