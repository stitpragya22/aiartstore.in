<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\WishlistModel;

class Wishlist extends BaseController
{
    protected $wishlistModel;

    public function __construct()
    {
        $this->wishlistModel = model(WishlistModel::class);
    }

    public function index()
    {
        $userId = auth()->user()->id;
        $data['wishlist'] = $this->wishlistModel->getUserWishlist($userId);
        $data['title'] = 'My Wishlist';

        return view('wishlist/index', $data);
    }

    public function toggle()
    {
        if (!auth()->loggedIn()) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Please login to manage your wishlist'
            ]);
        }

        $productId = (int) $this->request->getPost('product_id');

        if ($productId < 1) {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Invalid product'
            ]);
        }

        // Verify product exists and is active
        $product = model(ProductModel::class)->find($productId);
        if (!$product || $product['status'] !== 'active') {
            return $this->response->setJSON([
                'status'  => 'error',
                'message' => 'Product not found or inactive'
            ]);
        }

        $userId = auth()->user()->id;
        
        // Check if already wishlisted
        $existing = $this->wishlistModel->where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($existing) {
            // Remove it
            $this->wishlistModel->delete($existing['id']);
            $action = 'removed';
        } else {
            // Add it
            $this->wishlistModel->insert([
                'user_id'    => $userId,
                'product_id' => $productId
            ]);
            $action = 'added';
        }

        // Get updated count
        $count = $this->wishlistModel->where('user_id', $userId)->countAllResults();

        return $this->response->setJSON([
            'status' => 'success',
            'action' => $action,
            'count'  => $count
        ]);
    }
}
