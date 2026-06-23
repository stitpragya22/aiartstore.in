<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\SubscriptionPlanModel;

class SubscriptionPlans extends BaseController
{
    private $planModel;

    public function __construct()
    {
        $this->planModel = new SubscriptionPlanModel();
    }

    public function index()
    {
        $data['plans'] = $this->planModel->orderBy('level', 'ASC')->findAll();
        $data['title'] = 'Subscription Plans';
        return view('admin/subscription_plans/index', $data);
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $data = [
                'name'          => $this->request->getPost('name'),
                'slug'          => $this->request->getPost('slug'),
                'description'   => $this->request->getPost('description'),
                'price'         => $this->request->getPost('price') ?? 0,
                'validity_days' => $this->request->getPost('validity_days') ?? 0,
                'level'         => $this->request->getPost('level'),
                'status'        => $this->request->getPost('status') ?? 'active',
            ];

            if (!$this->planModel->save($data)) {
                return redirect()->back()->with('errors', $this->planModel->errors())->withInput();
            }

            return redirect()->to('/admin/subscription-plans')->with('message', 'Plan created successfully');
        }

        $data['title'] = 'Add Subscription Plan';
        return view('admin/subscription_plans/form', $data);
    }

    public function edit($id = null)
    {
        $plan = $this->planModel->find($id);
        if (!$plan) {
            return redirect()->to('/admin/subscription-plans')->with('error', 'Plan not found');
        }

        if ($this->request->is('post')) {
            $data = [
                'id'            => $id,
                'name'          => $this->request->getPost('name'),
                'slug'          => $this->request->getPost('slug'),
                'description'   => $this->request->getPost('description'),
                'price'         => $this->request->getPost('price') ?? 0,
                'validity_days' => $this->request->getPost('validity_days') ?? 0,
                'level'         => $this->request->getPost('level'),
                'status'        => $this->request->getPost('status') ?? 'active',
            ];

            if (!$this->planModel->save($data)) {
                return redirect()->back()->with('errors', $this->planModel->errors())->withInput();
            }

            return redirect()->to('/admin/subscription-plans')->with('message', 'Plan updated successfully');
        }

        $data['plan'] = $plan;
        $data['title'] = 'Edit Subscription Plan';
        return view('admin/subscription_plans/form', $data);
    }

    public function delete($id = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/subscription-plans')->with('error', 'Invalid request');
        }

        $this->planModel->delete($id);
        return redirect()->to('/admin/subscription-plans')->with('message', 'Plan deleted successfully');
    }
}
