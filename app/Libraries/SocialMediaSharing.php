<?php

namespace App\Libraries;

class SocialMediaSharing
{
    private string $facebookPageId;
    private string $facebookAccessToken;
    private string $instagramBusinessId;
    private string $instagramAccessToken;
    private string $settingsClass = 'App\Libraries\SocialMedia';

    public function __construct()
    {
        $this->loadCredentials();
    }

    private function loadCredentials(): void
    {
        $db = db_connect();
        $rows = $db->table('settings')
            ->where('class', $this->settingsClass)
            ->get()
            ->getResultArray();

        $creds = [];
        foreach ($rows as $row) {
            $creds[$row['key']] = $row['value'];
        }

        $this->facebookPageId = $creds['facebook_page_id'] ?? '';
        $this->facebookAccessToken = $creds['facebook_access_token'] ?? '';
        $this->instagramBusinessId = $creds['instagram_business_id'] ?? '';
        $this->instagramAccessToken = $creds['instagram_access_token'] ?? '';
    }

    public function isFacebookConfigured(): bool
    {
        return !empty($this->facebookPageId) && !empty($this->facebookAccessToken);
    }

    public function isInstagramConfigured(): bool
    {
        return !empty($this->instagramBusinessId) && !empty($this->instagramAccessToken);
    }

    public function shareToFacebookLink(array $prompt): array
    {
        if (!$this->isFacebookConfigured()) {
            return ['success' => false, 'message' => 'Facebook credentials not configured'];
        }

        $title = $prompt['seo_title'] ?: $prompt['title'];
        $description = $prompt['seo_description'] ?: 'Check out this AI prompt at AI Art Store';
        $slug = $prompt['slug'] ?? strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $prompt['title']), '-'));
        $url = site_url('/prompts/' . $prompt['id'] . '/' . $slug);

        $message = $title . "\n\n" . $description . "\n\n" . $url;

        $apiUrl = "https://graph.facebook.com/v25.0/{$this->facebookPageId}/feed";

        $postData = [
            'message'      => $message,
            'link'         => $url,
            'access_token' => $this->facebookAccessToken,
        ];

        $result = $this->callGraphApi($apiUrl, $postData);
        if ($result['success'] && isset($result['data']['id'])) {
            $parts = explode('_', $result['data']['id']);
            $result['data']['post_url'] = 'https://www.facebook.com/' . $this->facebookPageId . '/posts/' . end($parts);
        }
        return $result;
    }

    public function shareToFacebookPhoto(array $prompt, array $images): array
    {
        if (!$this->isFacebookConfigured()) {
            return ['success' => false, 'message' => 'Facebook credentials not configured'];
        }

        if (empty($images)) {
            return ['success' => false, 'message' => 'No images to share'];
        }

        $title = $prompt['seo_title'] ?: $prompt['title'];
        $slug = $prompt['slug'] ?? strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $prompt['title']), '-'));
        $url = site_url('/prompts/' . $prompt['id'] . '/' . $slug);
        $caption = $title . "\n\n" . $url;

        $result = $this->uploadPhoto($images[0], $caption);
        if ($result['success']) {
            return $result;
        }

        return $this->shareToFacebookLink($prompt);
    }

    public function shareToFacebookGallery(array $prompt, array $images): array
    {
        if (!$this->isFacebookConfigured()) {
            return ['success' => false, 'message' => 'Facebook credentials not configured'];
        }

        if (empty($images)) {
            return ['success' => false, 'message' => 'No images to share'];
        }

        $title = $prompt['seo_title'] ?: $prompt['title'];
        $slug = $prompt['slug'] ?? strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $prompt['title']), '-'));
        $url = site_url('/prompts/' . $prompt['id'] . '/' . $slug);
        $message = $title . "\n\n" . $url;

        $apiUrl = "https://graph.facebook.com/v25.0/{$this->facebookPageId}/photos?access_token=" . urlencode($this->facebookAccessToken);

        $mediaIds = [];
        foreach ($images as $image) {
            $filePath = FCPATH . 'uploads/prompts/' . $image['image'];
            if (!is_file($filePath)) continue;

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL            => $apiUrl,
                CURLOPT_POST           => true,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_SSL_VERIFYPEER => true,
                CURLOPT_TIMEOUT        => 60,
                CURLOPT_POSTFIELDS     => [
                    'published' => 'false',
                    'source'    => new \CURLFile($filePath),
                ],
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($httpCode >= 200 && $httpCode < 300) {
                $data = json_decode($response, true);
                if (!isset($data['error']) && isset($data['id'])) {
                    $mediaIds[] = $data['id'];
                }
            }
        }

        if (empty($mediaIds)) {
            return $this->shareToFacebookLink($prompt);
        }

        $attachedMedia = [];
        foreach ($mediaIds as $fbid) {
            $attachedMedia[] = ['media_fbid' => $fbid];
        }

        $publishUrl = "https://graph.facebook.com/v25.0/{$this->facebookPageId}/feed";
        $publishData = [
            'message'        => $message,
            'attached_media' => json_encode($attachedMedia),
            'access_token'   => $this->facebookAccessToken,
        ];

        $result = $this->callGraphApi($publishUrl, $publishData);
        if ($result['success'] && isset($result['data']['id'])) {
            $parts = explode('_', $result['data']['id']);
            $result['data']['post_url'] = 'https://www.facebook.com/' . $this->facebookPageId . '/posts/' . end($parts);
        }
        return $result;
    }

    private function uploadPhoto(array $image, string $caption): array
    {
        $filePath = FCPATH . 'uploads/prompts/' . $image['image'];

        if (!is_file($filePath)) {
            $imageUrl = base_url('uploads/prompts/' . $image['image']);
            $apiUrl = "https://graph.facebook.com/v25.0/{$this->facebookPageId}/photos";
            $postData = [
                'url'          => $imageUrl,
                'caption'      => $caption,
                'access_token' => $this->facebookAccessToken,
            ];
            return $this->callGraphApi($apiUrl, $postData);
        }

        $apiUrl = "https://graph.facebook.com/v25.0/{$this->facebookPageId}/photos?access_token=" . urlencode($this->facebookAccessToken);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $apiUrl,
            CURLOPT_POST           => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT        => 60,
            CURLOPT_POSTFIELDS     => [
                'caption'  => $caption,
                    'source'   => new \CURLFile($filePath),
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'message' => 'cURL error: ' . $error];
        }

        $data = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300 && !isset($data['error'])) {
            if (isset($data['id'])) {
                $data['post_url'] = 'https://www.facebook.com/photo.php?fbid=' . $data['id'];
            }
            return ['success' => true, 'message' => 'Photo posted successfully', 'data' => $data];
        }

        $errorMsg = $data['error']['message'] ?? ($data['message'] ?? 'Unknown API error');
        return ['success' => false, 'message' => 'API error: ' . $errorMsg];
    }

    public function verifyFacebookToken(): array
    {
        if (!$this->isFacebookConfigured()) {
            return ['success' => false, 'message' => 'Facebook not configured'];
        }

        $apiUrl = "https://graph.facebook.com/v25.0/{$this->facebookPageId}?fields=id,name,access_token&access_token=" . urlencode($this->facebookAccessToken);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode >= 200 && $httpCode < 300) {
            $data = json_decode($response, true);
            if (!isset($data['error'])) {
                return ['success' => true, 'message' => 'Token valid for page: ' . ($data['name'] ?? $this->facebookPageId)];
            }
        }

        $data = json_decode($response, true);
        $errorMsg = $data['error']['message'] ?? 'Unknown error';
        return ['success' => false, 'message' => 'Token verification failed: ' . $errorMsg];
    }

    public function getPageAccessToken(string $userToken): array
    {
        $apiUrl = "https://graph.facebook.com/v25.0/me/accounts?access_token=" . urlencode($userToken);

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'message' => 'cURL error: ' . $error];
        }

        $data = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300 && !isset($data['error'])) {
            $pages = [];
            foreach ($data['data'] ?? [] as $page) {
                $pages[] = [
                    'id'           => $page['id'],
                    'name'         => $page['name'],
                    'access_token' => $page['access_token'],
                ];
            }
            return ['success' => true, 'data' => $pages];
        }

        $errorMsg = $data['error']['message'] ?? ($data['message'] ?? 'Unknown API error');
        return ['success' => false, 'message' => 'API error: ' . $errorMsg];
    }

    public function shareToInstagram(array $prompt, array $images): array
    {
        if (!$this->isInstagramConfigured()) {
            return ['success' => false, 'message' => 'Instagram credentials not configured'];
        }

        if (empty($images)) {
            return ['success' => false, 'message' => 'No images to share'];
        }

        $firstImage = $images[0];
        $imageUrl = base_url('uploads/prompts/' . $firstImage['image']);

        $title = $prompt['seo_title'] ?: $prompt['title'];
        $slug = $prompt['slug'] ?? strtolower(trim(preg_replace('/[^a-z0-9]+/', '-', $prompt['title']), '-'));
        $url = site_url('/prompts/' . $prompt['id'] . '/' . $slug);

        $caption = $title . "\n\nDownload and try this prompt at " . $url . "\n\n#aiart #aiprompts #digitalart #aiartstore";

        $createUrl = "https://graph.facebook.com/v25.0/{$this->instagramBusinessId}/media";
        $createData = [
            'image_url'    => $imageUrl,
            'caption'      => $caption,
            'access_token' => $this->instagramAccessToken,
        ];

        $createResult = $this->callGraphApi($createUrl, $createData);
        if (!$createResult['success']) {
            return $createResult;
        }

        $creationId = $createResult['data']['id'] ?? null;
        if (!$creationId) {
            return ['success' => false, 'message' => 'Failed to get media creation ID'];
        }

        sleep(3);

        $publishUrl = "https://graph.facebook.com/v25.0/{$this->instagramBusinessId}/media_publish";
        $publishData = [
            'creation_id'  => $creationId,
            'access_token' => $this->instagramAccessToken,
        ];

        return $this->callGraphApi($publishUrl, $publishData);
    }

    private function callGraphApi(string $url, array $postData): array
    {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL            => $url,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($postData),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT        => 30,
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            return ['success' => false, 'message' => 'cURL error: ' . $error];
        }

        $data = json_decode($response, true);

        if ($httpCode >= 200 && $httpCode < 300 && !isset($data['error'])) {
            return ['success' => true, 'message' => 'Posted successfully', 'data' => $data];
        }

        $errorMsg = $data['error']['message'] ?? ($data['message'] ?? 'Unknown API error');
        return ['success' => false, 'message' => 'API error: ' . $errorMsg];
    }

    public static function saveCredentials(array $credentials): void
    {
        $db = db_connect();
        $class = 'App\Libraries\SocialMedia';
        $allowed = ['facebook_page_id', 'facebook_access_token', 'instagram_business_id', 'instagram_access_token'];

        foreach ($allowed as $key) {
            if (!array_key_exists($key, $credentials)) continue;

            $value = $credentials[$key];
            $existing = $db->table('settings')
                ->where('class', $class)
                ->where('key', $key)
                ->get()
                ->getRow();

            if ($existing) {
                $db->table('settings')
                    ->where('id', $existing->id)
                    ->update([
                        'value'      => $value,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            } else {
                $db->table('settings')->insert([
                    'class'      => $class,
                    'key'        => $key,
                    'value'      => $value,
                    'type'       => 'string',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }
    }

    public static function getCredentials(): array
    {
        $db = db_connect();
        $rows = $db->table('settings')
            ->where('class', 'App\Libraries\SocialMedia')
            ->get()
            ->getResultArray();

        $creds = [];
        foreach ($rows as $row) {
            $creds[$row['key']] = $row['value'];
        }

        return $creds;
    }
}
