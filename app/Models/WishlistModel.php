<?php

namespace App\Models;

use CodeIgniter\Model;

class WishlistModel extends Model
{
    protected $table            = 'wishlists';
    protected $primaryKey       = 'id';
    protected $allowedFields    = ['user_id', 'product_id'];
    protected $useTimestamps    = true;

    // Get all items in a user's wishlist joined with active product info
    public function getUserWishlist(int $userId)
    {
        return $this->select('wishlists.*, products.title, products.slug, products.price, products.image, products.image_watermarked, products.product_type, categories.name as category_name')
            ->join('products', 'products.id = wishlists.product_id')
            ->join('categories', 'categories.id = products.category_id', 'left')
            ->where('wishlists.user_id', $userId)
            ->where('products.status', 'active')
            ->orderBy('wishlists.id', 'DESC')
            ->findAll();
    }

    // Check if a product is in a user's wishlist
    public function isWishlisted(int $userId, int $productId): bool
    {
        return $this->where('user_id', $userId)
            ->where('product_id', $productId)
            ->countAllResults() > 0;
    }
}
