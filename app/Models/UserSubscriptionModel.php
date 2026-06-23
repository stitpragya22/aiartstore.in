<?php

namespace App\Models;

use CodeIgniter\Model;

class UserSubscriptionModel extends Model
{
    protected $table            = 'user_subscriptions';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['user_id', 'plan_id', 'order_id', 'start_date', 'end_date', 'status'];
    protected $useTimestamps    = true;

    public function getActiveForUser(int $userId)
    {
        return $this->select('user_subscriptions.*, subscription_plans.name as plan_name, subscription_plans.level as plan_level')
            ->join('subscription_plans', 'subscription_plans.id = user_subscriptions.plan_id')
            ->where('user_subscriptions.user_id', $userId)
            ->where('user_subscriptions.status', 'active')
            ->where('user_subscriptions.end_date >=', date('Y-m-d H:i:s'))
            ->orderBy('user_subscriptions.end_date', 'DESC')
            ->findAll();
    }

    public function getHighestLevelForUser(int $userId): int
    {
        $row = $this->select('MAX(subscription_plans.level) as max_level')
            ->join('subscription_plans', 'subscription_plans.id = user_subscriptions.plan_id')
            ->where('user_subscriptions.user_id', $userId)
            ->where('user_subscriptions.status', 'active')
            ->where('user_subscriptions.end_date >=', date('Y-m-d H:i:s'))
            ->first();
        return $row['max_level'] ?? 0;
    }

    public function getAllForUser(int $userId)
    {
        return $this->select('user_subscriptions.*, subscription_plans.name as plan_name, subscription_plans.level as plan_level')
            ->join('subscription_plans', 'subscription_plans.id = user_subscriptions.plan_id')
            ->where('user_subscriptions.user_id', $userId)
            ->orderBy('user_subscriptions.created_at', 'DESC')
            ->findAll();
    }
}
