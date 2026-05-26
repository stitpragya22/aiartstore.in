<?php

namespace App\Models;

use CodeIgniter\Model;

class ReviewModel extends Model
{
    protected $table = 'reviews';
    protected $primaryKey = 'id';
    protected $useTimestamps = true;
    protected $allowedFields = [
        'user_id', 'product_id', 'rating', 'title', 'review', 'status'
    ];

    public function getApproved($productId)
    {
        return $this->select('reviews.*, users.username')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->where('reviews.product_id', $productId)
            ->where('reviews.status', 'approved')
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll();
    }

    public function getAverageRating($productId)
    {
        $row = $this->selectAvg('rating')
            ->where('product_id', $productId)
            ->where('status', 'approved')
            ->first();
        return $row ? round($row['rating'], 1) : 0;
    }

    public function getRatingCount($productId, $rating = null)
    {
        $q = $this->where('product_id', $productId)->where('status', 'approved');
        if ($rating) $q->where('rating', $rating);
        return $q->countAllResults();
    }

    public function hasPurchased($userId, $productId)
    {
        return model('OrderItemModel')
            ->select('order_items.id')
            ->join('orders', 'orders.id = order_items.order_id')
            ->where('orders.user_id', $userId)
            ->where('orders.status', 'completed')
            ->where('order_items.product_id', $productId)
            ->first() !== null;
    }

    public function hasReviewed($userId, $productId)
    {
        return $this->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first() !== null;
    }

    public function getRecentPending($limit = 10)
    {
        return $this->select('reviews.*, users.username, products.title as product_title')
            ->join('users', 'users.id = reviews.user_id', 'left')
            ->join('products', 'products.id = reviews.product_id', 'left')
            ->where('reviews.status', 'pending')
            ->orderBy('reviews.created_at', 'DESC')
            ->findAll($limit);
    }
}
