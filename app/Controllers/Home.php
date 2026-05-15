<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CategoryModel;

class Home extends BaseController
{
    public function index()
    {
        $productModel = new ProductModel();
        $categoryModel = new CategoryModel();

        $data['featured'] = $productModel->getFeatured();
        $data['categories'] = $categoryModel->getWithProductCount();
        $data['latest'] = $productModel->getActive()->findAll(8);
        $data['title'] = 'Premium AI Art Gallery';

        return view('home', $data);
    }
}
