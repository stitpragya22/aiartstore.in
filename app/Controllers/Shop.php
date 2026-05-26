<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;
use App\Models\ReviewModel;

class Shop extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        $category = $this->request->getGet('category');
        $search = $this->request->getGet('search');
        $minPrice = $this->request->getGet('min_price');
        $maxPrice = $this->request->getGet('max_price');
        $sort = $this->request->getGet('sort');

        $maxDbPrice = $productModel->getMaxPrice($category);
        $maxPriceDefault = (int)ceil($maxDbPrice);

        $query = $productModel->getActive();

        if ($category) {
            $query->where('products.category_id', $category);
        }

        if ($search) {
            $query->groupStart()
                ->like('products.title', $search)
                ->orLike('products.tags', $search)
                ->orLike('products.description', $search)
            ->groupEnd();
        }

        if ($minPrice !== null && $minPrice !== '') {
            $query->where('products.price >=', (float)$minPrice);
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('products.price <=', (float)$maxPrice);
        }

        switch ($sort) {
            case 'price_asc':  $query->orderBy('products.price', 'ASC'); break;
            case 'price_desc': $query->orderBy('products.price', 'DESC'); break;
            case 'name_asc':   $query->orderBy('products.title', 'ASC'); break;
            case 'name_desc':  $query->orderBy('products.title', 'DESC'); break;
            case 'date_asc':   $query->orderBy('products.id', 'ASC'); break;
            default:           $query->orderBy('products.id', 'DESC');
        }

        $data['products'] = $query->paginate(12);
        $data['pager'] = $productModel->pager;
        $data['categories'] = $categoryModel->getWithProductCount();
        $data['selectedCategory'] = $category;
        $data['search'] = $search;
        $data['min_price'] = $minPrice;
        $data['max_price'] = $maxPrice;
        $data['max_price_default'] = $maxPriceDefault;
        $data['sort'] = $sort;
        $data['title'] = 'Browse Art';

        return view('shop/index', $data);
    }

    public function category($slug = null)
    {
        if (!$slug) {
            return redirect()->to('/shop');
        }

        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        $category = $categoryModel->where('slug', $slug)->where('status', 'active')->first();

        if (!$category) {
            return redirect()->to('/shop')->with('error', 'Category not found');
        }

        $search = $this->request->getGet('search');
        $minPrice = $this->request->getGet('min_price');
        $maxPrice = $this->request->getGet('max_price');
        $sort = $this->request->getGet('sort');

        $maxDbPrice = $productModel->getMaxPrice($category['id']);
        $maxPriceDefault = (int)ceil($maxDbPrice);

        $query = $productModel->getActive();
        $query->where('products.category_id', $category['id']);

        if ($search) {
            $query->groupStart()
                ->like('products.title', $search)
                ->orLike('products.tags', $search)
                ->orLike('products.description', $search)
            ->groupEnd();
        }

        if ($minPrice !== null && $minPrice !== '') {
            $query->where('products.price >=', (float)$minPrice);
        }
        if ($maxPrice !== null && $maxPrice !== '') {
            $query->where('products.price <=', (float)$maxPrice);
        }

        switch ($sort) {
            case 'price_asc':  $query->orderBy('products.price', 'ASC'); break;
            case 'price_desc': $query->orderBy('products.price', 'DESC'); break;
            case 'name_asc':   $query->orderBy('products.title', 'ASC'); break;
            case 'name_desc':  $query->orderBy('products.title', 'DESC'); break;
            case 'date_asc':   $query->orderBy('products.id', 'ASC'); break;
            default:           $query->orderBy('products.id', 'DESC');
        }

        $data['products'] = $query->paginate(12);
        $data['pager'] = $productModel->pager;
        $data['categories'] = $categoryModel->getWithProductCount();
        $data['selectedCategory'] = $category['slug'];
        $data['search'] = $search;
        $data['min_price'] = $minPrice;
        $data['max_price'] = $maxPrice;
        $data['max_price_default'] = $maxPriceDefault;
        $data['sort'] = $sort;
        $data['meta_title'] = $category['meta_title'] ?: esc($category['name']) . ' Art';
        $data['meta_description'] = $category['meta_description'] ?: 'Browse our collection of ' . esc($category['name']) . ' AI-generated art. High-resolution digital downloads available.';
        $data['title'] = $category['meta_title'] ?: esc($category['name']) . ' Art';

        return view('shop/index', $data);
    }

    public function detail($slug = null)
    {
        if (!$slug) {
            return redirect()->to('/shop');
        }

        $productModel = new ProductModel();
        $product = $productModel->getBySlug($slug);

        if (!$product) {
            return redirect()->to('/shop')->with('error', 'Product not found');
        }

        $related = $productModel->getActive()
            ->where('products.id !=', $product['id'])
            ->where('products.category_id', $product['category_id'])
            ->findAll(4);

        $data['product'] = $product;
        $data['related'] = $related;
        $data['title'] = $product['title'];
        $data['meta_image'] = $product['image_watermarked']
            ? 'uploads/products/' . $product['image_watermarked']
            : ($product['image'] ? 'uploads/products/' . $product['image'] : '');

        // Reviews
        $reviewModel = new ReviewModel();
        $data['reviews'] = $reviewModel->getApproved($product['id']);
        $data['avg_rating'] = $reviewModel->getAverageRating($product['id']);
        $data['rating_counts'] = [];
        for ($i = 5; $i >= 1; $i--) {
            $data['rating_counts'][$i] = $reviewModel->getRatingCount($product['id'], $i);
        }

        $data['can_review'] = false;
        $data['has_reviewed'] = false;
        if (auth()->loggedIn()) {
            $user = auth()->user();
            $data['can_review'] = $reviewModel->hasPurchased($user->id, $product['id']);
            $data['has_reviewed'] = $reviewModel->hasReviewed($user->id, $product['id']);
        }

        return view('shop/detail_rich', $data);
    }

    public function submitReview($productId)
    {
        if (!auth()->loggedIn()) {
            return redirect()->to('/login')->with('error', 'Please login to review');
        }

        $reviewModel = new ReviewModel();
        $user = auth()->user();

        if (!$reviewModel->hasPurchased($user->id, $productId)) {
            return redirect()->back()->with('error', 'You can only review products you have purchased');
        }

        if ($reviewModel->hasReviewed($user->id, $productId)) {
            return redirect()->back()->with('error', 'You have already reviewed this product');
        }

        $this->validate([
            'rating' => 'required|numeric|greater_than[0]|less_than[6]',
            'review' => 'permit_empty|min_length[10]',
        ]);

        $reviewModel->save([
            'user_id'    => $user->id,
            'product_id' => $productId,
            'rating'     => $this->request->getPost('rating'),
            'title'      => $this->request->getPost('title'),
            'review'     => $this->request->getPost('review'),
            'status'     => 'pending',
        ]);

        return redirect()->back()->with('message', 'Review submitted! It will appear after moderation.');
    }
}

