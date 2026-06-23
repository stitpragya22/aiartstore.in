<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\UserSubscriptionModel;
use App\Models\SubscriptionPlanModel;

class UserSubscriptions extends BaseController
{
    private $subscriptionModel;
    private $planModel;

    public function __construct()
    {
        $this->subscriptionModel = new UserSubscriptionModel();
        $this->planModel = new SubscriptionPlanModel();
    }

    public function index()
    {
        $subscriptions = $this->subscriptionModel
            ->select('user_subscriptions.*, subscription_plans.name as plan_name, subscription_plans.level as plan_level, users.email as user_email, users.username as user_name')
            ->join('subscription_plans', 'subscription_plans.id = user_subscriptions.plan_id')
            ->join('users', 'users.id = user_subscriptions.user_id')
            ->orderBy('user_subscriptions.created_at', 'DESC')
            ->findAll();

        $data['subscriptions'] = $subscriptions;
        $data['title'] = 'User Subscriptions';
        return view('admin/user_subscriptions/index', $data);
    }

    public function create()
    {
        if ($this->request->is('post')) {
            $userModel = auth()->getProvider();
            $user = $userModel->findById($this->request->getPost('user_id'));
            if (!$user) {
                return redirect()->back()->with('error', 'User not found')->withInput();
            }

            $plan = $this->planModel->find($this->request->getPost('plan_id'));
            if (!$plan) {
                return redirect()->back()->with('error', 'Plan not found')->withInput();
            }

            $startDate = $this->request->getPost('start_date') ?? date('Y-m-d H:i:s');
            $endDate = $plan['validity_days'] > 0
                ? date('Y-m-d H:i:s', strtotime($startDate . ' + ' . $plan['validity_days'] . ' days'))
                : '9999-12-31 23:59:59';

            $data = [
                'user_id'    => $user->id,
                'plan_id'    => $plan['id'],
                'start_date' => $startDate,
                'end_date'   => $endDate,
                'status'     => 'active',
            ];

            $this->subscriptionModel->save($data);
            return redirect()->to('/admin/user-subscriptions')->with('message', 'Subscription assigned successfully');
        }

        $userModel = auth()->getProvider();
        $data['users'] = $userModel->findAll();
        $data['plans'] = $this->planModel->where('status', 'active')->findAll();
        $data['title'] = 'Assign Subscription';
        return view('admin/user_subscriptions/form', $data);
    }

    public function cancel($id = null)
    {
        if (!$this->request->is('post')) {
            return redirect()->to('/admin/user-subscriptions')->with('error', 'Invalid request');
        }

        $this->subscriptionModel->update($id, ['status' => 'cancelled']);
        return redirect()->to('/admin/user-subscriptions')->with('message', 'Subscription cancelled');
    }
}
