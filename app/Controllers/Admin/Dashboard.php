<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ProductModel;
use App\Models\OrderModel;
use App\Models\CategoryModel;
use App\Models\BlogPostModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $orderModel = new OrderModel();
        $categoryModel = new CategoryModel();

        $blogModel = new BlogPostModel();

        $data['totalProducts'] = $productModel->where('status', 'active')->countAllResults();
        $data['totalOrders'] = $orderModel->countAll();
        $data['totalRevenue'] = $orderModel->selectSum('total')->where('status', 'completed')->get()->getRow()->total ?? 0;
        $data['totalCategories'] = $categoryModel->where('status', 'active')->countAllResults();
        $data['totalUsers'] = auth()->getProvider()->countAll();
        $data['totalBlogPosts'] = $blogModel->where('status', 'published')->countAllResults();
        $data['recentBlogPosts'] = $blogModel->orderBy('id', 'DESC')->findAll(5);

        $data['recentOrders'] = $orderModel->orderBy('id', 'DESC')->findAll(5);
        $data['title'] = 'Dashboard';

        return view('admin/dashboard', $data);
    }
}
