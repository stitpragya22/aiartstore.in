<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\SocialMediaSharing;

class Settings extends BaseController
{
    public function index()
    {
        $db = db_connect();

        if ($this->request->is('post')) {
            $settings = [
                'razorpay_mode'             => $this->request->getPost('razorpay_mode'),
                'razorpay_test_key_id'      => $this->request->getPost('razorpay_test_key_id'),
                'razorpay_test_key_secret'  => $this->request->getPost('razorpay_test_key_secret'),
                'razorpay_live_key_id'      => $this->request->getPost('razorpay_live_key_id'),
                'razorpay_live_key_secret'  => $this->request->getPost('razorpay_live_key_secret'),
                'razorpay_webhook_secret'   => $this->request->getPost('razorpay_webhook_secret'),
                'razorpay_currency'         => $this->request->getPost('razorpay_currency'),
                'custom_css'                => $this->request->getPost('custom_css'),
                'custom_js'                 => $this->request->getPost('custom_js'),
                'admin_email'               => $this->request->getPost('admin_email'),
            ];

            foreach ($settings as $key => $value) {
                $class = (in_array($key, ['custom_css', 'custom_js', 'admin_email', 'site_logo', 'site_favicon'])) ? 'App\Views\Layouts' : 'App\Libraries\Razorpay';

                $existing = $db->table('settings')
                    ->where('class', $class)
                    ->where('key', $key)
                    ->get()
                    ->getRow();

                if ($existing) {
                    $db->table('settings')
                        ->where('id', $existing->id)
                        ->update(['value' => $value, 'updated_at' => date('Y-m-d H:i:s')]);
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

            $this->saveUploadedImage('site_logo', 'App\Views\Layouts');
            $this->saveUploadedImage('site_favicon', 'App\Views\Layouts');

            SocialMediaSharing::saveCredentials([
                'facebook_page_id'       => $this->request->getPost('facebook_page_id'),
                'facebook_access_token'  => $this->request->getPost('facebook_access_token'),
                'instagram_business_id'  => $this->request->getPost('instagram_business_id'),
                'instagram_access_token' => $this->request->getPost('instagram_access_token'),
            ]);

            return redirect()->to('/admin/settings')->with('message', 'Settings saved successfully');
        }

        $rows = $db->table('settings')
            ->whereIn('class', ['App\Libraries\Razorpay', 'App\Views\Layouts'])
            ->get()
            ->getResultArray();

        $data['settings'] = [];
        foreach ($rows as $row) {
            $data['settings'][$row['key']] = $row['value'];
        }

        $socialCreds = SocialMediaSharing::getCredentials();
        foreach ($socialCreds as $key => $value) {
            $data['settings'][$key] = $value;
        }

        $data['title'] = 'Payment Settings';
        return view('admin/settings/index', $data);
    }

    public function fetchFacebookPages()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $userToken = $this->request->getPost('user_token');
        if (empty($userToken)) {
            return $this->response->setJSON(['success' => false, 'message' => 'User token is required']);
        }

        $sharer = new SocialMediaSharing();
        $result = $sharer->getPageAccessToken($userToken);

        return $this->response->setJSON($result);
    }

    public function testFacebookToken()
    {
        if (!$this->request->is('post')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Invalid request']);
        }

        $sharer = new SocialMediaSharing();
        $result = $sharer->verifyFacebookToken();

        return $this->response->setJSON($result);
    }

    private function saveUploadedImage(string $field, string $class): void
    {
        $file = $this->request->getFile($field);
        if (!$file || !$file->isValid() || $file->hasMoved()) return;

        if (!in_array($file->getMimeType(), ['image/jpeg', 'image/png', 'image/webp', 'image/gif', 'image/x-icon', 'image/vnd.microsoft.icon'])) return;

        $uploadPath = FCPATH . 'uploads/site';
        if (!is_dir($uploadPath)) mkdir($uploadPath, 0755, true);

        $newName = $field . '_' . $file->getRandomName();
        $file->move($uploadPath, $newName);
        $path = 'uploads/site/' . $newName;

        $db = db_connect();
        $existing = $db->table('settings')
            ->where('class', $class)
            ->where('key', $field)
            ->get()
            ->getRow();

        if ($existing) {
            if ($existing->value && file_exists(FCPATH . $existing->value)) {
                unlink(FCPATH . $existing->value);
            }
            $db->table('settings')
                ->where('id', $existing->id)
                ->update(['value' => $path, 'updated_at' => date('Y-m-d H:i:s')]);
        } else {
            $db->table('settings')->insert([
                'class'      => $class,
                'key'        => $field,
                'value'      => $path,
                'type'       => 'string',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }
    }
}
