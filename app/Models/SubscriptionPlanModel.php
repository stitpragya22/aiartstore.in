<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionPlanModel extends Model
{
    protected $table            = 'subscription_plans';
    protected $primaryKey       = 'id';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['id', 'name', 'slug', 'description', 'price', 'validity_days', 'level', 'status'];
    protected $useTimestamps    = true;
    protected $validationRules  = [
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'required|min_length[2]|max_length[100]|is_unique[subscription_plans.slug,id,{id}]',
        'price' => 'required|decimal',
        'validity_days' => 'permit_empty|integer',
        'level' => 'required|integer',
        'id'    => 'permit_empty|is_natural_no_zero',
    ];

    public function getActive()
    {
        return $this->where('status', 'active')->orderBy('level', 'ASC')->findAll();
    }

    public function getByLevel(int $level)
    {
        return $this->where('level', $level)->where('status', 'active')->first();
    }
}
