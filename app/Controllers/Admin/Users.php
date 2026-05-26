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
        $currentUser = auth()->user();

        $allowedGroups = ['admin', 'developer'];

        $group = $this->request->getPost('group');

        if (!$currentUser->inGroup('superadmin')) {
            return redirect()->to('/admin/users')->with('error', 'Only superadmin can manage user groups');
        }

        if ((int) $id === (int) $currentUser->id) {
            return redirect()->to('/admin/users')->with('error', 'Cannot modify your own group');
        }

        if ($group === 'superadmin') {
            return redirect()->to('/admin/users')->with('error', 'Cannot assign superadmin group via this interface');
        }

        if (!in_array($group, $allowedGroups, true)) {
            return redirect()->to('/admin/users')->with('error', 'Invalid group');
        }

        $user = auth()->getProvider()->findById($id);
        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        if ($user->inGroup($group)) {
            $user->removeGroup($group);
        } else {
            $user->addGroup($group);
        }

        return redirect()->to('/admin/users')->with('message', 'User group updated');
    }
}
