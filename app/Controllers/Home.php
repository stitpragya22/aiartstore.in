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
        $data['curated_categories'] = $categoryModel->getCurated();
        $data['latest'] = $productModel->getActive()->findAll(8);
        $data['title'] = 'Premium AI Art Gallery — 500+ Unique Digital Artworks';
        $data['meta_description'] = 'Discover 500+ premium AI-generated artworks for instant download. Browse abstract, fantasy, cyberpunk, landscape, and portrait art. Curated AI art for creators and designers.';

        return view('home', $data);
    }
}
