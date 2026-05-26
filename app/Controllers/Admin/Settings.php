<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

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
            ];

            foreach ($settings as $key => $value) {
                $existing = $db->table('settings')
                    ->where('class', 'App\Libraries\Razorpay')
                    ->where('key', $key)
                    ->get()
                    ->getRow();

                if ($existing) {
                    $db->table('settings')
                        ->where('id', $existing->id)
                        ->update(['value' => $value, 'updated_at' => date('Y-m-d H:i:s')]);
                } else {
                    $db->table('settings')->insert([
                        'class'      => 'App\Libraries\Razorpay',
                        'key'        => $key,
                        'value'      => $value,
                        'type'       => 'string',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
                }
            }

            return redirect()->to('/admin/settings')->with('message', 'Settings saved successfully');
        }

        $rows = $db->table('settings')
            ->where('class', 'App\Libraries\Razorpay')
            ->get()
            ->getResultArray();

        $data['settings'] = [];
        foreach ($rows as $row) {
            $data['settings'][$row['key']] = $row['value'];
        }

        $data['title'] = 'Payment Settings';
        return view('admin/settings/index', $data);
    }
}
