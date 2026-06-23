<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\CouponModel;

class Coupons extends BaseController
{
    public function index()
    {
        $model = new CouponModel();
        $data['coupons'] = $model->orderBy('id', 'DESC')->findAll();
        $data['title'] = 'Coupons';
        return view('admin/coupons/index', $data);
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $model = new CouponModel();
            if (!$model->save([
                'code'       => strtoupper($this->request->getPost('code')),
                'type'       => $this->request->getPost('type'),
                'value'      => $this->request->getPost('value'),
                'min_amount'  => $this->request->getPost('min_amount') ?? 0,
                'max_uses'   => $this->request->getPost('max_uses') ?? 0,
                'starts_at'  => $this->request->getPost('starts_at') ?: null,
                'expires_at' => $this->request->getPost('expires_at') ?: null,
                'status'     => $this->request->getPost('status') ?? 'active',
            ])) {
                return redirect()->back()->with('errors', $model->errors())->withInput();
            }
            return redirect()->to('/admin/coupons')->with('message', 'Coupon created');
        }
        $data['title'] = 'Add Coupon';
        return view('admin/coupons/form', $data);
    }

    public function edit($id)
    {
        $model = new CouponModel();
        $coupon = $model->find($id);
        if (!$coupon) return redirect()->to('/admin/coupons')->with('error', 'Not found');

        if ($this->request->is('post')) {
            if (!$model->update($id, [
                'id'         => $id,
                'code'       => strtoupper($this->request->getPost('code')),
                'type'       => $this->request->getPost('type'),
                'value'      => $this->request->getPost('value'),
                'min_amount'  => $this->request->getPost('min_amount') ?? 0,
                'max_uses'   => $this->request->getPost('max_uses') ?? 0,
                'starts_at'  => $this->request->getPost('starts_at') ?: null,
                'expires_at' => $this->request->getPost('expires_at') ?: null,
                'status'     => $this->request->getPost('status') ?? 'active',
            ])) {
                return redirect()->back()->with('errors', $model->errors())->withInput();
            }
            return redirect()->to('/admin/coupons')->with('message', 'Coupon updated');
        }
        $data['coupon'] = $coupon;
        $data['title'] = 'Edit Coupon';
        return view('admin/coupons/form', $data);
    }

    public function delete($id)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/coupons')->with('error', 'Invalid request');
        }

        $model = new CouponModel();
        $model->delete($id);
        return redirect()->to('/admin/coupons')->with('message', 'Coupon deleted');
    }
}
