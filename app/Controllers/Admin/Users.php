<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Users extends BaseController
{
    public function index()
    {
        $userModel = auth()->getProvider();
        $data['users'] = $userModel->orderBy('id', 'DESC')->findAll();
        $data['title'] = 'Manage Users';
        return view('admin/users/index', $data);
    }

    public function toggleGroup($id = null)
    {
        $user = auth()->getProvider()->findById($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        $group = $this->request->getPost('group');

        if ($user->inGroup($group)) {
            $user->removeGroup($group);
        } else {
            $user->addGroup($group);
        }

        return redirect()->to('/admin/users')->with('message', 'User group updated');
    }
}
