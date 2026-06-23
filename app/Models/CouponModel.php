<?php

namespace App\Models;

use CodeIgniter\Model;

class CouponModel extends Model
{
    protected $table = 'coupons';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'id', 'code', 'type', 'value', 'min_amount', 'max_uses', 'used_count',
        'starts_at', 'expires_at', 'status'
    ];
    protected $validationRules = [
        'code'  => 'required|min_length[3]|max_length[50]|is_unique[coupons.code,id,{id}]',
        'type'  => 'required|in_list[percentage,fixed]',
        'value' => 'required|numeric|greater_than[0]',
        'id'    => 'permit_empty|is_natural_no_zero',
    ];

    public function validateCoupon($code, $orderTotal = 0)
    {
        $coupon = $this->where('code', $code)->where('status', 'active')->first();
        if (!$coupon) return ['valid' => false, 'message' => 'Invalid coupon code'];

        if ($coupon['expires_at'] && strtotime($coupon['expires_at']) < time()) {
            return ['valid' => false, 'message' => 'Coupon has expired'];
        }

        if ($coupon['starts_at'] && strtotime($coupon['starts_at']) > time()) {
            return ['valid' => false, 'message' => 'Coupon is not yet valid'];
        }

        if ($coupon['max_uses'] > 0 && $coupon['used_count'] >= $coupon['max_uses']) {
            return ['valid' => false, 'message' => 'Coupon usage limit reached'];
        }

        if ($orderTotal < $coupon['min_amount']) {
            return ['valid' => false, 'message' => 'Minimum order amount of ' . formatPrice($coupon['min_amount']) . ' required'];
        }

        $discount = $coupon['type'] === 'percentage'
            ? round($orderTotal * $coupon['value'] / 100, 2)
            : min($coupon['value'], $orderTotal);

        return ['valid' => true, 'coupon' => $coupon, 'discount' => $discount];
    }

    public function incrementUsage($id)
    {
        return $this->set('used_count', 'used_count + 1', false)->update($id);
    }
}
