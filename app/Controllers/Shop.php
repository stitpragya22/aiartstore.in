<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Shop extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        $category = $this->request->getGet('category');
        $search = $this->request->getGet('search');

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

        $data['products'] = $query->paginate(12);
        $data['pager'] = $productModel->pager;
        $data['categories'] = $categoryModel->getWithProductCount();
        $data['selectedCategory'] = $category;
        $data['search'] = $search;
        $data['title'] = 'Browse Art';

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

        return view('shop/detail', $data);
    }
}
